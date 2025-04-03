<?php

namespace Tests\Feature\Admin\Contact;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

use App\Models\Admin;
use App\Models\User;
use App\Models\Contact;

use App\Consts\ContactConsts;

class IndexTest extends TestCase
{
    use RefreshDatabase;

    private $_url;
    private $_admin;
    private $_users;
    private $_contacts;

    public function setUp(): void
    {
        parent::setUp();

        $this->_url = route('admin.contact.index');

        // ログイン用ユーザー
        $this->_admin = Admin::factory()->create([
            'login_id' => 'test1test3',
            'password' => Hash::make('test3test3'),
        ]);

        $this->_users[0] = User::factory()->create([
            'name'      => 'test0',
            'email'     => 'test0@test.co.jp',
            'password'  => Hash::make('test0test0'),
        ]);
        $this->_users[1] = User::factory()->create([
            'name'      => 'test1',
            'email'     => 'test1@test.co.jp',
            'password'  => Hash::make('test1test1'),
        ]);

        $this->_contacts[0] = Contact::factory()->create([
            'id'            => 1,
            'user_id'       => $this->_users[0]->id,
            'no'            => str_repeat('0', ContactConsts::NO_LENGTH - 1) . '1',
            'body'          => 'おはよう1111ございます。',
            'status'        => ContactConsts::STATUS_NOT_STARTED,
            'created_at'    => '2024-03-25 17:17:17',
        ]);
        $this->_contacts[1] = Contact::factory()->create([
            'id'            => 2,
            'user_id'       => $this->_users[0]->id,
            'no'            => str_repeat('0', ContactConsts::NO_LENGTH - 1) . '2',
            'body'          => 'こんに1112ちは。',
            'status'        => ContactConsts::STATUS_IN_PROGRESS,
            'created_at'    => '2024-05-25 17:17:17',
        ]);
        $this->_contacts[2] = Contact::factory()->create([
            'id'            => 3,
            'user_id'       => $this->_users[1]->id,
            'no'            => str_repeat('0', ContactConsts::NO_LENGTH - 1) . '3',
            'body'          => 'こんば1113んは。',
            'status'        => ContactConsts::STATUS_COMPLETED,
            'created_at'    => '2025-03-25 17:17:17',
        ]);

        // basic認証
        $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('test1test1:test1test1'),
        ]);
    }

    /** @test */
    public function 画面のアクセス制御(): void
    {
        $this->actingAs($this->_admin, 'admin');
        $this->get($this->_url)->assertOk();
    }


    /** @test */
    public function 検索条件なしで全てのレコードが表示される(): void
    {
        $this->actingAs($this->_admin, 'admin');
        // 検索条件なしでGETリクエスト
        $response = $this->get($this->_url);
        
        // ステータスコードが200であることを確認
        $response->assertOk();

        // すべてのレコードが表示されていることを確認
        foreach ($this->_contacts as $contact) {
            $response->assertSee($contact->no);
        }
    }


    /** @test */
    public function お問い合わせ番号に一致するレコードのみが表示される(): void
    {
        $this->actingAs($this->_admin, 'admin');
        
        // 完全一致のみ
        $response = $this->get(route('admin.contact.index', ['no' => str_repeat('0', ContactConsts::NO_LENGTH - 1) . $this->_contacts[1]->id]));
        $response->assertOk();
        $response->assertSee($this->_contacts[1]->no);
        $response->assertDontSee($this->_contacts[0]->no);
        $response->assertDontSee($this->_contacts[2]->no);

        // 不一致
        $response = $this->get(route('admin.contact.index', ['no' => str_repeat('0', ContactConsts::NO_LENGTH - 1) . '4']));
        $response->assertOk();
        foreach ($this->_contacts as $contact) {
            $response->assertDontSee($contact->no);
        }
    }


    /** @test */
    public function お問い合わせ内容に一致するレコードのみが表示される(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // 完全一致
        $response = $this->get(route('admin.contact.index', ['body' => 'おはよう1111ございます。']));
        $response->assertOk();
        $response->assertSee($this->_contacts[0]->no);
        $response->assertDontSee($this->_contacts[1]->no);
        $response->assertDontSee($this->_contacts[2]->no);

        // 部分一致
        $response = $this->get(route('admin.contact.index', ['body' => '111']));
        $response->assertOk();
        $response->assertSee($this->_contacts[0]->no);
        $response->assertSee($this->_contacts[1]->no);
        $response->assertSee($this->_contacts[2]->no);

        // 前方一致
        $response = $this->get(route('admin.contact.index', ['body' => 'こんに']));
        $response->assertOk();
        $response->assertSee($this->_contacts[1]->no);
        $response->assertDontSee($this->_contacts[0]->no);
        $response->assertDontSee($this->_contacts[2]->no);

        // 後方一致
        $response = $this->get(route('admin.contact.index', ['body' => 'んは。']));
        $response->assertOk();
        $response->assertSee($this->_contacts[2]->name);
        $response->assertDontSee($this->_contacts[0]->name);
        $response->assertDontSee($this->_contacts[1]->name);

        // 不一致
        $response = $this->get(route('admin.contact.index', ['body' => 'キボンヌ']));
        $response->assertOk();
        foreach ($this->_contacts as $contact) {
            $response->assertDontSee($contact->no);
        }
    }


    /** @test */
    public function 投稿日～に一致するレコードのみ表示される(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // 1件のみ一致
        $response = $this->get(route('admin.contact.index', ['created_at_from' => '2025-03-25']));
        $response->assertOk();
        $response->assertSee($this->_contacts[2]->no);
        $response->assertDontSee($this->_contacts[0]->no);
        $response->assertDontSee($this->_contacts[1]->no);

        // 2件のみ一致
        $response = $this->get(route('admin.contact.index', ['created_at_from' => '2024-05-25']));
        $response->assertOk();
        $response->assertSee($this->_contacts[1]->no);
        $response->assertSee($this->_contacts[2]->no);
        $response->assertDontSee($this->_contacts[0]->no);

        // 全件一致
        $response = $this->get(route('admin.contact.index', ['created_at_from' => '2024-03-25']));
        $response->assertOk();
        $response->assertSee($this->_contacts[0]->no);
        $response->assertSee($this->_contacts[1]->no);
        $response->assertSee($this->_contacts[2]->no);

        // 0件一致
        $response = $this->get(route('admin.contact.index', ['created_at_from' => '2025-03-26']));
        $response->assertOk();
        $response->assertDontSee($this->_contacts[0]->no);
        $response->assertDontSee($this->_contacts[1]->no);
        $response->assertDontSee($this->_contacts[2]->no);
    }


    /** @test */
    public function ～投稿日に一致するレコードのみ表示される(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // 1件のみ一致
        $response = $this->get(route('admin.contact.index', ['created_at_to' => '2024-03-25']));
        $response->assertOk();
        $response->assertSee($this->_contacts[0]->no);
        $response->assertDontSee($this->_contacts[1]->no);
        $response->assertDontSee($this->_contacts[2]->no);

        // 2件のみ一致
        $response = $this->get(route('admin.contact.index', ['created_at_to' => '2024-05-25']));
        $response->assertOk();
        $response->assertSee($this->_contacts[0]->no);
        $response->assertSee($this->_contacts[1]->no);
        $response->assertDontSee($this->_contacts[2]->no);

        // 全件一致
        $response = $this->get(route('admin.contact.index', ['created_at_to' => '2025-03-25']));
        $response->assertOk();
        $response->assertSee($this->_contacts[0]->no);
        $response->assertSee($this->_contacts[1]->no);
        $response->assertSee($this->_contacts[2]->no);

        // 0件一致
        $response = $this->get(route('admin.contact.index', ['created_at_to' => '2024-03-24']));
        $response->assertOk();
        $response->assertDontSee($this->_contacts[0]->no);
        $response->assertDontSee($this->_contacts[1]->no);
        $response->assertDontSee($this->_contacts[2]->no);
    }


    /** @test */
    public function ステータスに一致するレコードのみ表示される(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // 対応済みのみ
        $response = $this->get(route('admin.contact.index', ['status' => [ContactConsts::STATUS_COMPLETED]]));
        $response->assertOk();
        $response->assertSee($this->_contacts[2]->no);
        $response->assertDontSee($this->_contacts[0]->no);
        $response->assertDontSee($this->_contacts[1]->no);

        // 対応中のみ
        $response = $this->get(route('admin.contact.index', ['status' => [ContactConsts::STATUS_IN_PROGRESS]]));
        $response->assertOk();
        $response->assertSee($this->_contacts[1]->no);
        $response->assertDontSee($this->_contacts[0]->no);
        $response->assertDontSee($this->_contacts[2]->no);

        // 未対応のみ
        $response = $this->get(route('admin.contact.index', ['status' => [ContactConsts::STATUS_NOT_STARTED]]));
        $response->assertOk();
        $response->assertSee($this->_contacts[0]->no);
        $response->assertDontSee($this->_contacts[1]->no);
        $response->assertDontSee($this->_contacts[2]->no);

        // 対応済み、対応中のみ
        $response = $this->get(route('admin.contact.index', ['status' => [ContactConsts::STATUS_COMPLETED, ContactConsts::STATUS_IN_PROGRESS]]));
        $response->assertOk();
        $response->assertSee($this->_contacts[1]->no);
        $response->assertSee($this->_contacts[2]->no);
        $response->assertDontSee($this->_contacts[0]->no);

        // 対応中、未対応のみ
        $response = $this->get(route('admin.contact.index', ['status' => [ContactConsts::STATUS_IN_PROGRESS, ContactConsts::STATUS_NOT_STARTED]]));
        $response->assertOk();
        $response->assertSee($this->_contacts[0]->no);
        $response->assertSee($this->_contacts[1]->no);
        $response->assertDontSee($this->_contacts[2]->no);

        // 対応済み、未対応のみ
        $response = $this->get(route('admin.contact.index', ['status' => [ContactConsts::STATUS_COMPLETED, ContactConsts::STATUS_NOT_STARTED]]));
        $response->assertOk();
        $response->assertSee($this->_contacts[0]->no);
        $response->assertSee($this->_contacts[2]->no);
        $response->assertDontSee($this->_contacts[1]->no);

        // 全て
        $response = $this->get(route('admin.contact.index', ['status' => [ContactConsts::STATUS_COMPLETED, ContactConsts::STATUS_IN_PROGRESS, ContactConsts::STATUS_NOT_STARTED]]));
        $response->assertOk();
        $response->assertSee($this->_contacts[0]->no);
        $response->assertSee($this->_contacts[1]->no);
        $response->assertSee($this->_contacts[2]->no);

        // 検索条件なし
        $response = $this->get(route('admin.contact.index', ['status' => []]));
        $response->assertOk();
        $response->assertSee($this->_contacts[0]->no);
        $response->assertSee($this->_contacts[1]->no);
        $response->assertSee($this->_contacts[2]->no);
    }


    /** @test */
    public function 複数の検索条件(): void
    {
        $this->actingAs($this->_admin, 'admin');

        $response = $this->get(route('admin.contact.index', [
            'no' => str_repeat('0', ContactConsts::NO_LENGTH -1) . $this->_contacts[1]->id,
            'body' => '1112',
            'created_at_from' => '2024-05-25',
            'created_at_to' => '2024-05-26',
            'status' => [ContactConsts::STATUS_IN_PROGRESS]
        ]));
        $response->assertOk();
        $response->assertSee($this->_contacts[1]->no);
        $response->assertDontSee($this->_contacts[0]->no);
        $response->assertDontSee($this->_contacts[2]->no);
    }


    /** @test */
    public function バリデーション(): void
    {
        $this->actingAs($this->_admin, 'admin');

        // お問い合わせ番号
        // リダイレクト
        $response = $this->from(route('admin.contact.index'))->get(route('admin.contact.index', ['no' => 'a']));
        $response->assertRedirect(route('admin.contact.index'));
        // 文字列以外
        $this->get(route('admin.contact.index', ['no' => ['000000000001']]))->assertInvalid(['no' => 'お問い合わせ番号には、文字列を指定してください。']);
        $this->get(route('admin.contact.index', ['no' => 'aaaaaaaaaaaa']))->assertValid();
        // 12文字以外
        $this->get(route('admin.contact.index', ['no' => str_repeat('0', 10) . '1']))->assertInvalid(['no' => 'お問い合わせ番号の文字数は、12文字にしてください。']);
        $this->get(route('admin.contact.index', ['no' => 'aaaaaaaaaaaa']))->assertValid();

        // お問い合わせ内容
        // リダイレクト
        $response = $this->from(route('admin.contact.index'))->get(route('admin.contact.index', ['body' => ['000000000001']]));
        $response->assertRedirect(route('admin.contact.index'));
        // 文字列以外
        $this->get(route('admin.contact.index', ['body' => ['000000000001']]))->assertInvalid(['body' => 'お問い合わせ内容には、文字列を指定してください。']);
        $this->get(route('admin.contact.index', ['body' => 'a']))->assertValid();
        // 50文字より多い
        $this->get(route('admin.contact.index', ['body' => str_repeat('a', ContactConsts::BODY_LIST_LENGTH_MAX + 1)]))->assertInvalid(['body' => 'お問い合わせ内容の文字数は、50文字以下である必要があります。']);
        $this->get(route('admin.contact.index', ['body' => str_repeat('a', ContactConsts::BODY_LIST_LENGTH_MAX)]))->assertValid();

        // 投稿日～
        // リダイレクト
        $response = $this->from(route('admin.contact.index'))->get(route('admin.contact.index', ['created_at_from' => 'a']));
        $response->assertRedirect(route('admin.contact.index'));
        // Y-m-d形式以外
        $this->get(route('admin.contact.index', ['created_at_from' => '2025/04/01']))->assertInvalid(['created_at_from' => "投稿日～の形式が'Y-m-d'と一致しません。"]);
        $this->get(route('admin.contact.index', ['created_at_from' => '2025-04-01']))->assertValid();
        // 2019/01/01より前
        $this->get(route('admin.contact.index', ['created_at_from' => '2018-12-31']))->assertInvalid(['created_at_from' => '投稿日～には、2019/01/01以降の日付を指定してください。']);
        $this->get(route('admin.contact.index', ['created_at_from' => '2019-01-01']))->assertValid();
        // 2037/12/31より後
        $this->get(route('admin.contact.index', ['created_at_from' => '2038-01-01']))->assertInvalid(['created_at_from' => '投稿日～には、2037/12/31以前の日付を指定してください。']);
        $this->get(route('admin.contact.index', ['created_at_from' => '2037-12-31']))->assertValid();

        // ～投稿日
        // リダイレクト
        $response = $this->from(route('admin.contact.index'))->get(route('admin.contact.index', ['created_at_to' => 'a']));
        $response->assertRedirect(route('admin.contact.index'));
        // Y-m-d形式以外
        $this->get(route('admin.contact.index', ['created_at_to' => '2025/04/01']))->assertInvalid(['created_at_to' => "～投稿日の形式が'Y-m-d'と一致しません。"]);
        $this->get(route('admin.contact.index', ['created_at_to' => '2025-04-01']))->assertValid();
        // 2019/01/01より前
        $this->get(route('admin.contact.index', ['created_at_to' => '2018-12-31']))->assertInvalid(['created_at_to' => '～投稿日には、2019/01/01以降の日付を指定してください。']);
        $this->get(route('admin.contact.index', ['created_at_to' => '2019-01-01']))->assertValid();
        // 2037/12/31より後
        $this->get(route('admin.contact.index', ['created_at_to' => '2038-01-01']))->assertInvalid(['created_at_to' => '～投稿日には、2037/12/31以前の日付を指定してください。']);
        $this->get(route('admin.contact.index', ['created_at_to' => '2037-12-31']))->assertValid();
        // 投稿日～より前
        $this->get(route('admin.contact.index', [
            'created_at_from' => '2025-04-02',
            'created_at_to' => '2025-04-01',
        ]))->assertInvalid(['created_at_to' => '～投稿日には、投稿日～以降の日付を指定してください。']);
        $this->get(route('admin.contact.index', [
            'created_at_from' => '2025-04-01',
            'created_at_to' => '2025-04-01',
        ]))->assertValid();

        // ステータス
        // リダイレクト
        $response = $this->from(route('admin.contact.index'))->get(route('admin.contact.index', ['status' => 'a']));
        $response->assertRedirect(route('admin.contact.index'));
        // 配列でない
        $this->get(route('admin.contact.index', ['status' => ContactConsts::STATUS_COMPLETED]))->assertInvalid(['status' => 'ステータスには、配列を指定してください。']);
        $this->get(route('admin.contact.index', ['status' => [ContactConsts::STATUS_COMPLETED]]))->assertValid();
        // リストに存在しない
        $this->get(route('admin.contact.index', ['status' => [4]]))->assertInvalid(['status.0' => '選択されたステータスは、有効ではありません。']);
        $this->get(route('admin.contact.index', ['status' => [ContactConsts::STATUS_COMPLETED]]))->assertValid();
    }



    /** @test */
    public function CSVダウンロード処理(): void
    {
        $this->actingAs($this->_admin, 'admin');
    
        // 一覧画面でCSVダウンロードボタン押下でCSVダウンロードにリダイレクト
        $response = $this->from(route('admin.contact.index'))->get(route('admin.contact.index', ['csv_export' => 'csv_export']));
        $response->assertRedirect(route('admin.contact.csv_export'));

        // 直接アクセスしても検索条件なしでCSV出力される
        $response = $this->get(route('admin.contact.csv_export'));
        $response->assertOk();
        // Content-Type が CSV であることを確認
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        // Content-Disposition にファイル名が設定されていることを確認
        $fileName = 'お問い合わせ.csv';
        $encodedFileName = rawurlencode($fileName);
        $response->assertHeader('Content-Disposition', "attachment; filename=" . $fileName . "; filename*=UTF-8''" . $encodedFileName);
        // レスポンスの内容を取得
        // 出力バッファを開始
        ob_start();
        // ストリームの内容を取得
        $response->sendContent();
        // バッファ内容を取得＆クリア
        $csvContent = ob_get_clean();
        // CSV のヘッダーとデータを確認
        $head = "お問い合わせNO,投稿日,氏名,メールアドレス,お問い合わせ内容,ステータス\n";
        $record_1 = "000000000001,2024年03月25日17時17分17秒," . $this->_users[0]->name . "," . $this->_users[0]->email . "," . "おはよう1111ございます。,未対応\n";
        $record_2 = "000000000002,2024年05月25日17時17分17秒," . $this->_users[0]->name . "," . $this->_users[0]->email . "," . "こんに1112ちは。,対応中\n";
        $record_3 = "000000000003,2025年03月25日17時17分17秒," . $this->_users[1]->name . "," . $this->_users[1]->email . "," . "こんば1113んは。,対応済\n";
        $expectedCsv = $head . $record_1 . $record_2 . $record_3;
        $this->assertEquals($expectedCsv, mb_convert_encoding($csvContent, 'UTF-8', 'SJIS'));
    }


    /** @test */
    public function 検索条件を含めたCSVダウンロード処理(): void
    {
        $this->actingAs($this->_admin, 'admin');
        
        // お問い合わせ番号
        $searchConditions = [
            'no' => '000000000001'
        ];
        session(['contact' => $searchConditions]);
        $response = $this->get(route('admin.contact.csv_export'));
        // 出力バッファを開始
        ob_start();
        // ストリームの内容を取得
        $response->sendContent();
        // バッファ内容を取得＆クリア
        $csvContent = ob_get_clean();
        // CSV のヘッダーとデータを確認
        $head = "お問い合わせNO,投稿日,氏名,メールアドレス,お問い合わせ内容,ステータス\n";
        $record_1 = "000000000001,2024年03月25日17時17分17秒," . $this->_users[0]->name . "," . $this->_users[0]->email . "," . "おはよう1111ございます。,未対応\n";
        $expectedCsv = $head . $record_1;
        $this->assertEquals($expectedCsv, mb_convert_encoding($csvContent, 'UTF-8', 'SJIS'));

        // お問い合わせ内容
        $searchConditions = [
            'body' => '1112'
        ];
        session(['contact' => $searchConditions]);
        $response = $this->get(route('admin.contact.csv_export'));
        // 出力バッファを開始
        ob_start();
        // ストリームの内容を取得
        $response->sendContent();
        // バッファ内容を取得＆クリア
        $csvContent = ob_get_clean();
        // CSV のヘッダーとデータを確認
        $head = "お問い合わせNO,投稿日,氏名,メールアドレス,お問い合わせ内容,ステータス\n";
        $record_2 = "000000000002,2024年05月25日17時17分17秒," . $this->_users[0]->name . "," . $this->_users[0]->email . "," . "こんに1112ちは。,対応中\n";
        $expectedCsv = $head . $record_2;
        $this->assertEquals($expectedCsv, mb_convert_encoding($csvContent, 'UTF-8', 'SJIS'));

        // 投稿日
        $searchConditions = [
            'created_at_from' => '2024-05-25',
            'created_at_to' => '2024-05-26',
        ];
        session(['contact' => $searchConditions]);
        $response = $this->get(route('admin.contact.csv_export'));
        // Content-Disposition にファイル名が設定されていることを確認
        $fileName = 'お問い合わせ' . $searchConditions['created_at_from'] .'～'. $searchConditions['created_at_to'] . '.csv';
        $encodedFileName = rawurlencode($fileName);
        $response->assertHeader('Content-Disposition', "attachment; filename=" . $fileName . "; filename*=UTF-8''" . $encodedFileName);
        // 出力バッファを開始
        ob_start();
        // ストリームの内容を取得
        $response->sendContent();
        // バッファ内容を取得＆クリア
        $csvContent = ob_get_clean();
        // CSV のヘッダーとデータを確認
        $head = "お問い合わせNO,投稿日,氏名,メールアドレス,お問い合わせ内容,ステータス\n";
        $record_2 = "000000000002,2024年05月25日17時17分17秒," . $this->_users[0]->name . "," . $this->_users[0]->email . "," . "こんに1112ちは。,対応中\n";
        $expectedCsv = $head . $record_2;
        $this->assertEquals($expectedCsv, mb_convert_encoding($csvContent, 'UTF-8', 'SJIS'));

        // ステータス
        $searchConditions = [
            'status' => [ContactConsts::STATUS_COMPLETED, ContactConsts::STATUS_NOT_STARTED],
        ];
        session(['contact' => $searchConditions]);
        $response = $this->get(route('admin.contact.csv_export'));
        // 出力バッファを開始
        ob_start();
        // ストリームの内容を取得
        $response->sendContent();
        // バッファ内容を取得＆クリア
        $csvContent = ob_get_clean();
        // CSV のヘッダーとデータを確認
        $head = "お問い合わせNO,投稿日,氏名,メールアドレス,お問い合わせ内容,ステータス\n";
        $record_1 = "000000000001,2024年03月25日17時17分17秒," . $this->_users[0]->name . "," . $this->_users[0]->email . "," . "おはよう1111ございます。,未対応\n";
        $record_3 = "000000000003,2025年03月25日17時17分17秒," . $this->_users[1]->name . "," . $this->_users[1]->email . "," . "こんば1113んは。,対応済\n";
        $expectedCsv = $head . $record_1 . $record_3;
        $this->assertEquals($expectedCsv, mb_convert_encoding($csvContent, 'UTF-8', 'SJIS'));


    }
}
