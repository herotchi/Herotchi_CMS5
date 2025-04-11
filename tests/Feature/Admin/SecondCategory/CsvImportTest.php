<?php

namespace Tests\Feature\Admin\SecondCategory;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

use App\Models\Admin;
use App\Models\FirstCategory;
use App\Models\SecondCategory;
use App\Models\Tag;
use App\Models\Product;

use DateTime;

use App\Consts\SecondCategoryConsts;

class CsvImportTest extends TestCase
{
    use RefreshDatabase;

    private $_url;
    private $_admin;
    private $_firstCategories;
    private $_secondCategories;

    public function setUp(): void
    {
        parent::setUp();

        // ログイン用ユーザー
        $this->_admin = Admin::factory()->create([
            'login_id' => 'test1test1',
            'password' => Hash::make('test1test1'),
        ]);

        $this->_firstCategories = FirstCategory::factory()->createMany([
            ['name' => 'test1'],
            ['name' => 'test2'],
        ]);

        $this->_secondCategories = SecondCategory::factory()->createMany([
            [
                'first_category_id' => $this->_firstCategories[0]->id,
                'name' => 'test1test1'
            ],
            [
                'first_category_id' => $this->_firstCategories[1]->id,
                'name' => 'test2test2'
            ]
        ]);
        
        // basic認証
        $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('test1test1:test1test1'),
        ]);

        $this->_url = route('admin.second_category.csv_import');
    }


    /** @test */
    public function バリデーション(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // リダイレクト
        $this->from(route('admin.second_category.csv_upload'))
            ->post($this->_url, ['code' => ''])
            ->assertRedirect(route('admin.second_category.csv_upload'));

        // 文字コード
        // 空
        $this->post($this->_url, ['code' => ''])->assertInvalid(['code' => '文字コードは必須']);
        // 整数でない
        $this->post($this->_url, ['code' => 'a'])->assertInvalid(['code' => '文字コードには、整数']);
        // リストに存在しない
        $this->post($this->_url, ['code' => 100])->assertInvalid(['code' => '文字コードは、有効では']);
        // 正常
        $this->post($this->_url, ['code' => SecondCategoryConsts::CSV_CODE_SJIS])->assertValid('code');
        $this->post($this->_url, ['code' => SecondCategoryConsts::CSV_CODE_UTF8])->assertValid('code');

        // CSVファイル
        // ファイル準備
        Storage::fake('public');
        // 空
        $this->post($this->_url, ['csv_file' => ''])->assertInvalid(['csv_file' => 'CSVファイルは必須']);
        // ファイル以外
        $this->post($this->_url, ['csv_file' => 'aaa'])->assertInvalid(['csv_file' => 'CSVファイルには、ファイル形式を指定']);
        // テキストファイル以外
        $file = UploadedFile::fake()->image('test.jpg');
        $this->post($this->_url, ['csv_file' => $file])->assertInvalid(['csv_file' => 'CSVファイルには、以下のファイルタイプを指定してください。text/plain, text/csv']);
        // ファイルサイズ1024キロバイトより大きい
        //$file = UploadedFile::fake()->image('test.csv')->size(SecondCategoryConsts::CSV_FILE_MAX + 1);
        $file = UploadedFile::fake()->create('test.csv', SecondCategoryConsts::CSV_FILE_MAX + 1);
        $this->post($this->_url, ['csv_file' => $file])->assertInvalid(['csv_file' => 'CSVファイルは、'. SecondCategoryConsts::CSV_FILE_MAX . ' KB以下']);
        // 正常
        //$file = UploadedFile::fake()->image('test.csv');
        $file = UploadedFile::fake()->create('test.csv');
        $this->post($this->_url, ['csv_file' => $file])->assertValid('csv_file');
    }


    /** @test */
    public function ファイル内ヘッダーバリデーション(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // ヘッダー行ミス
        $content = <<<EOF
        大カテゴリ名a,中カテゴリ名a
        "test1", "test1test1"
        "test2", "test2test2"
        EOF;
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);
        $response = $this->from(route('admin.second_category.csv_upload'))->post($this->_url, [
            'code' => SecondCategoryConsts::CSV_CODE_UTF8,
            'csv_file' => $file
        ]);
        $response->assertInvalid(['csv_file' => '1行目：ヘッダーの項目名が違っています。']);
        // リダイレクト
        $response->assertRedirect(route('admin.second_category.csv_upload'));

        // ヘッダー行に過不足
        $content = <<<EOF
        大カテゴリ名,中カテゴリ名,小カテゴリ名
        "test1", "test1test1"
        "test2", "test2test2"
        EOF;
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);
        $response = $this->post($this->_url, [
            'code' => SecondCategoryConsts::CSV_CODE_UTF8,
            'csv_file' => $file
        ]);
        $response->assertInvalid(['csv_file' => '1行目：ヘッダーの項目名が違っています。']);

    }


    /** @test */
    public function ファイル内大カテゴリ名バリデーション(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // ヘッダー行以外で1行あたりの項目数が足りない場合
        $content = <<<EOF
        大カテゴリ名, 中カテゴリ名
        "test1"
        "test2", "test2test2"
        EOF;
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);
        $response = $this->from(route('admin.second_category.csv_upload'))->post($this->_url, [
            'code' => SecondCategoryConsts::CSV_CODE_UTF8,
            'csv_file' => $file
        ]);
        $response->assertInvalid(['csv_file' => '2行目：項目に過不足があります。']);
        // リダイレクト
        $response->assertRedirect(route('admin.second_category.csv_upload'));

        // 大カテゴリ名が未入力
        $content = <<<EOF
        大カテゴリ名, 中カテゴリ名
        , "test1test1"
        "test2", "test2test2"
        EOF;
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);
        $response = $this->post($this->_url, [
            'code' => SecondCategoryConsts::CSV_CODE_UTF8,
            'csv_file' => $file
        ]);
        $response->assertInvalid(['csv_file' => '2行目：大カテゴリ名は必須項目です。']);

        // 大カテゴリ名が50文字より大きい
        $content = <<<EOF
        大カテゴリ名, 中カテゴリ名
        "test11123456789011234567890112345678901123456789011234567890", "test1test1"
        "test2", "test2test2"
        EOF;
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);
        $response = $this->post($this->_url, [
            'code' => SecondCategoryConsts::CSV_CODE_UTF8,
            'csv_file' => $file
        ]);
        $response->assertInvalid(['csv_file' => '2行目：大カテゴリ名の文字数は、50文字以下である必要があります。']);

        // 大カテゴリ名がDBに存在しない
        $content = <<<EOF
        大カテゴリ名, 中カテゴリ名
        "test12", "test1test1"
        "test2", "test2test2"
        EOF;
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);
        $response = $this->post($this->_url, [
            'code' => SecondCategoryConsts::CSV_CODE_UTF8,
            'csv_file' => $file
        ]);
        $response->assertInvalid(['csv_file' => '2行目：存在しない大カテゴリ名が入力されています。']);
    }


    /** @test */
    public function ファイル内中カテゴリ名バリデーション(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // ヘッダー行以外で1行あたりの項目数が足りない場合
        $content = <<<EOF
        大カテゴリ名, 中カテゴリ名
        "test1", "test1test1"
        "test2"
        EOF;
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);
        $response = $this->from(route('admin.second_category.csv_upload'))->post($this->_url, [
            'code' => SecondCategoryConsts::CSV_CODE_UTF8,
            'csv_file' => $file
        ]);
        $response->assertInvalid(['csv_file' => '3行目：項目に過不足があります。']);
        // リダイレクト
        $response->assertRedirect(route('admin.second_category.csv_upload'));

        // 中カテゴリ名が未入力
        $content = <<<EOF
        大カテゴリ名, 中カテゴリ名
        "test1, "test1test1"
        "test2",
        EOF;
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);
        $response = $this->post($this->_url, [
            'code' => SecondCategoryConsts::CSV_CODE_UTF8,
            'csv_file' => $file
        ]);
        $response->assertInvalid(['csv_file' => '3行目：中カテゴリ名は必須項目です。']);

        // 中カテゴリ名が50文字より大きい
        $content = <<<EOF
        大カテゴリ名, 中カテゴリ名
        "test1", "test1test1"
        "test2", "test2test21123456789011234567890112345678901123456789011234567890"
        EOF;
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);
        $response = $this->post($this->_url, [
            'code' => SecondCategoryConsts::CSV_CODE_UTF8,
            'csv_file' => $file
        ]);
        $response->assertInvalid(['csv_file' => '3行目：中カテゴリ名の文字数は、50文字以下である必要があります。']);

        // DBに既に大カテゴリ名と中カテゴリ名の組み合わせが存在する
        $content = <<<EOF
        大カテゴリ名,中カテゴリ名
        "test1", "test1test1a"
        "test2", "test2test2"
        EOF;
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);
        $response = $this->post($this->_url, [
            'code' => SecondCategoryConsts::CSV_CODE_UTF8,
            'csv_file' => $file
        ]);
        $response->assertInvalid(['csv_file' => '3行目：同じ大カテゴリ内で中カテゴリ名が重複しています。']);

        // CSVファイル内に同じ大カテゴリが存在する場合、それと紐づく中カテゴリも重複しているかチェックする
        $content = <<<EOF
        大カテゴリ名,中カテゴリ名
        "test1", "test1test1a"
        "test1", "test1test1a"
        EOF;
        $file = UploadedFile::fake()->createWithContent('test.csv', $content);
        $response = $this->post($this->_url, [
            'code' => SecondCategoryConsts::CSV_CODE_UTF8,
            'csv_file' => $file
        ]);
        $response->assertInvalid(['csv_file' => '3行目：CSVファイル内の同じ大カテゴリ内で中カテゴリ名が重複しています。']);
        
        // CSVファイルは削除済み
        //Storage::disk('public')->assertMissing(SecondCategoryConsts::CSV_FILE_DIR . '/' . $fileName);
    }


    /** @test */
    public function DB存在チェック(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // 正常レコード
        $content = <<<EOF
        大カテゴリ名,中カテゴリ名
        "test1", "test1test1a"
        "test2", "test2test2b"
        EOF;
        $today = new DateTime();
        $fileName = $today->format('YmdHis') . '.csv';
        $file = UploadedFile::fake()->createWithContent($fileName, $content);

        $response = $this->from(route('admin.second_category.csv_upload'))->post($this->_url, [
            'code' => SecondCategoryConsts::CSV_CODE_UTF8,
            'csv_file' => $file
        ]);
        $response->assertValid();

        // リダイレクトの確認
        $response->assertRedirect(route('admin.second_category.index'));
        // セッションにメッセージが存在するか確認
        $response->assertSessionHas('msg_success', '中カテゴリを一括登録しました。');

        $this->assertDatabaseHas('second_categories', [
            'first_category_id' => $this->_firstCategories[0]->id,
            'name' => 'test1test1',
        ]);

        $this->assertDatabaseHas('second_categories', [
            'first_category_id' => $this->_firstCategories[1]->id,
            'name' => 'test2test2',
        ]);

        // CSVファイルは削除済み
        Storage::disk('public')->assertMissing(SecondCategoryConsts::CSV_FILE_DIR . '/' . $fileName);
    }
}
