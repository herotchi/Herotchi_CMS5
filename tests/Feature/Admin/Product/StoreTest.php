<?php

namespace Tests\Feature\Admin\Product;

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

use App\Consts\ProductConsts;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    private $_url;
    private $_admin;
    private $_firstCategories;
    private $_secondCategories;
    private $_tags;

    public function setUp(): void
    {
        parent::setUp();

        // ログイン用ユーザー
        $this->_admin = Admin::factory()->create([
            'login_id' => 'test1test1',
            'password' => Hash::make('test1test1'),
        ]);

        $this->_firstCategories = FirstCategory::factory(2)->create();

        $this->_secondCategories[0] = SecondCategory::factory()->create([
            'first_category_id' => $this->_firstCategories[0]->id,
        ]);
        $this->_secondCategories[1] = SecondCategory::factory()->create([
            'first_category_id' => $this->_firstCategories[1]->id,
        ]);

        $this->_tags = Tag::factory(5)->create();

        // basic認証
        $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('test1test1:test1test1'),
        ]);

        $this->_url = route('admin.product.store');
    }


    /** @test */
    public function バリデーション(): void
    {
        // 管理者権限でログイン
        $this->actingAs($this->_admin, 'admin');

        // 入力エラーでリダイレクト確認
        $this->from(route('admin.product.create'))
            ->post($this->_url, ['first_category_id' => ''])
            ->assertRedirect(route('admin.product.create'));

        // 大カテゴリ名
        // 空
        $this->post($this->_url, ['first_category_id' => ''])->assertInvalid(['first_category_id' => '大カテゴリ名は必須']);
        // 整数でない
        $this->post($this->_url, ['first_category_id' => 'a'])->assertInvalid(['first_category_id' => '大カテゴリ名には、整数']);
        // データベースに存在しない
        $this->post($this->_url, ['first_category_id' => 100])->assertInvalid(['first_category_id' => '大カテゴリ名は、有効では']);
        // 正常
        $this->post($this->_url, ['first_category_id' => $this->_firstCategories[0]->id])->assertValid('first_category_id');

        // 中カテゴリ名
        // 空
        $this->post($this->_url, ['second_category_id' => ''])->assertInvalid(['second_category_id' => '中カテゴリ名は必須']);
        // 整数でない
        $this->post($this->_url, ['second_category_id' => 'a'])->assertInvalid(['second_category_id' => '中カテゴリ名には、整数']);
        // データベースに存在しない
        $this->post($this->_url, ['second_category_id' => 100])->assertInvalid(['second_category_id' => '中カテゴリ名は、有効では']);
        // 大カテゴリと紐づいていない
        $this->post($this->_url, [
            'first_category_id' => $this->_firstCategories[0]->id,
            'second_category_id' => $this->_secondCategories[1]->id,
        ])->assertInvalid(['second_category_id' => '大カテゴリと紐づいていない']);
        // 正常
        $this->post($this->_url, ['second_category_id' => $this->_secondCategories[0]->id])->assertValid('second_category_id');
        $this->post($this->_url, [
            'first_category_id' => $this->_firstCategories[0]->id,
            'second_category_id' => $this->_secondCategories[0]->id,
        ])->assertValid('second_category_id');

        // タグ
        // 空
        $this->post($this->_url, ['tag_ids' => ''])->assertInvalid(['tag_ids' => 'タグは必須']);
        // 配列ではない
        $this->post($this->_url, ['tag_ids' => 'a'])->assertInvalid(['tag_ids' => 'タグには、配列']);
        // 整数ではない
        $this->post($this->_url, ['tag_ids' => ['a']])->assertInvalid(['tag_ids.0' => 'タグには、整数']);
        // DBに存在しない
        $this->post($this->_url, ['tag_ids' => [100]])->assertInvalid(['tag_ids.0' => 'タグは、有効では']);
        // 正常
        $this->post($this->_url, ['tag_ids' => [$this->_tags[0]->id]])->assertValid('tag_ids');
        // 複数正常
        $this->post($this->_url, ['tag_ids' => [$this->_tags[0]->id, $this->_tags[1]->id]])->assertValid('tag_ids');

        // 製品名
        // 空
        $this->post($this->_url, ['name' => ''])->assertInvalid(['name' => '製品名は必須']);
        // 文字列
        $this->post($this->_url, ['name' => ['a']])->assertInvalid(['name' => '製品名には、文字列']);
        // 51文字以上
        $this->post($this->_url, ['name' => str_repeat('a', ProductConsts::NAME_LENGTH_MAX + 1)])->assertInvalid(['name' => '製品名の文字数は、' . ProductConsts::NAME_LENGTH_MAX . '文字以下']);
        // 正常
        $this->post($this->_url, ['name' => str_repeat('a', ProductConsts::NAME_LENGTH_MAX)])->assertValid('name');

        // 製品画像
        // ファイル準備
        Storage::fake('public');
        // 空
        $this->post($this->_url, ['image' => ''])->assertInvalid(['image' => '製品画像は必須']);
        // ファイル以外
        $this->post($this->_url, ['image' => 'aaa'])->assertInvalid(['image' => '製品画像には、ファイル形式を指定']);
        // 画像ファイル以外
        $file = UploadedFile::fake()->image('test.txt');
        $this->post($this->_url, ['image' => $file])->assertInvalid(['image' => '製品画像には、画像を指定']);
        // jpg, png以外
        $file = UploadedFile::fake()->image('test.gif');
        $this->post($this->_url, ['image' => $file])->assertInvalid(['image' => '製品画像には、以下のファイルタイプを指定してください。jpg, png']);
        // ファイルサイズ5120キロバイトより大きい
        $file = UploadedFile::fake()->image('test.png')->size(ProductConsts::IMAGE_FILE_MAX + 1);
        $this->post($this->_url, ['image' => $file])->assertInvalid(['image' => '製品画像は、'. ProductConsts::IMAGE_FILE_MAX . ' KB以下']);
        // 正常
        $file = UploadedFile::fake()->image('test.png');
        $this->post($this->_url, ['image' => $file])->assertValid('image');

        // 製品詳細
        $this->post($this->_url, ['detail' => ''])->assertInvalid(['detail' => '製品詳細は必須']);
        // 文字列
        $this->post($this->_url, ['detail' => ['a']])->assertInvalid(['detail' => '製品詳細には、文字列']);
        // 51文字以上
        $this->post($this->_url, ['detail' => str_repeat('a', ProductConsts::DETAIL_LENGTH_MAX + 1)])->assertInvalid(['detail' => '製品詳細の文字数は、' . ProductConsts::DETAIL_LENGTH_MAX . '文字以下']);
        // 正常
        $this->post($this->_url, ['detail' => str_repeat('a', ProductConsts::DETAIL_LENGTH_MAX)])->assertValid('detail');

        // 表示設定
        // 空
        $this->post($this->_url, ['release_flg' => ''])->assertInvalid(['release_flg' => '表示設定は必須']);
        // 整数でない
        $this->post($this->_url, ['release_flg' => 'a'])->assertInvalid(['release_flg' => '表示設定には、整数']);
        // リストに存在しない
        $this->post($this->_url, ['release_flg' => 100])->assertInvalid(['release_flg' => '表示設定は、有効では']);
        // 正常
        $this->post($this->_url, ['release_flg' => ProductConsts::RELEASE_FLG_ON])->assertValid('release_flg');
        $this->post($this->_url, ['release_flg' => ProductConsts::RELEASE_FLG_OFF])->assertValid('release_flg');
    }

    /** @test */
    public function 画像ファイル存在チェック(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // 正常レコード
        Storage::fake('public');
        $fileName = 'test_product_image.png';
        // ダミーファイルを作成（実際には物理ファイルは作られない）
        $file = UploadedFile::fake()->image($fileName);
        $data = [
            'first_category_id' => $this->_firstCategories[0]->id,
            'second_category_id' => $this->_secondCategories[0]->id,
            'tag_ids' => [$this->_tags[0]->id, $this->_tags[1]->id],
            'name' => 'test1',
            'image' => $file,
            'detail' => fake()->sentence(),
            'release_flg' => ProductConsts::RELEASE_FLG_ON,
        ];

        $response = $this->from(route('admin.product.create'))->post($this->_url, $data);

        // リダイレクトの確認
        $response->assertRedirect(route('admin.product.index'));
        // セッションにメッセージが存在するか確認
        $response->assertSessionHas('msg_success', '製品情報を登録しました。');

        Storage::disk('public')->assertExists(ProductConsts::IMAGE_FILE_DIR . '/' . $file->hashName());
    }

    /** @test */
    public function DB存在チェック(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // 正常レコード
        Storage::fake('public');
        $fileName = 'test_product_image.png';
        // ダミーファイルを作成（実際には物理ファイルは作られない）
        $file = UploadedFile::fake()->image($fileName);
        $data = [
            'first_category_id' => $this->_firstCategories[0]->id,
            'second_category_id' => $this->_secondCategories[0]->id,
            'tag_ids' => [$this->_tags[0]->id, $this->_tags[1]->id],
            'name' => 'test1',
            'image' => $file,
            'detail' => fake()->sentence(),
            'release_flg' => ProductConsts::RELEASE_FLG_ON,
        ];

        $response = $this->from(route('admin.product.create'))->post($this->_url, $data);

        // リダイレクトの確認
        $response->assertRedirect(route('admin.product.index'));
        // セッションにメッセージが存在するか確認
        $response->assertSessionHas('msg_success', '製品情報を登録しました。');


        $this->assertDatabaseHas('products', [
            'name' => 'test1',
            'image' => 'storage/' . ProductConsts::IMAGE_FILE_DIR . '/' . $file->hashName(),
        ]);
    }
    
}
