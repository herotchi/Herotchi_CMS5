<?php

namespace Tests\Feature\Contact;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Mail;
use App\Mail\Contact as ContactMail;

use App\Models\User;
use App\Models\Contact;

use App\Consts\ContactConsts;

class StoreTest extends TestCase
{
    use RefreshDatabase;

    //private $_url;
    private $_web;

    public function setUp(): void
    {
        parent::setUp();

        // ログイン用ユーザー
        $this->_web = User::factory()->create([
            'email' => 'test1@test.co.jp',
            'password' => Hash::make('test1test1'),
        ]);

        // basic認証
        $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('test1test1:test1test1'),
        ]);

        //$this->_url = route('contact.store');
    }


    /** @test */
    public function バリデーション():void
    {
        $this->actingAs($this->_web, 'web');

        // リダイレクト
        $this->from(route('contact.create'))->post(route('contact.confirm'), [
            'body' => ''
        ])->assertRedirect(route('contact.create'));

        // お問い合わせ内容
        // 空
        $this->post(route('contact.confirm'), ['body' => ''])->assertInvalid(['body' => 'お問い合わせ内容は必須項目です。']);
        // 2000文字より多い
        $this->post(route('contact.confirm'), ['body' => str_repeat('a', ContactConsts::BODY_LENGTH_MAX + 1)])->assertInvalid(['body' => 'お問い合わせ内容の文字数は、2000文字以下である必要があります。']);

    }


    /** @test */
    public function 確認画面バリデーション(): void
    {
        $this->actingAs($this->_web, 'web');

        // 入力値セッションなし
        $response = $this->from(route('contact.confirm'))->post(route('contact.store'), ['body' => '']);
        $response->assertSessionHas('msg_failure', 'セッション期限が切れました。');
        $response->assertRedirect(route('top'));

        // セッションあり
        // リダイレクト
        $response = $this->from(route('contact.confirm'))->withSession(['input' => ['body' => '']])->post(route('contact.store'));
        $response->assertRedirect(route('contact.create'));
    
        // お問い合わせ内容
        // 空
        $this->withSession(['input' => ['body' => '']])->post(route('contact.store'))->assertInvalid(['body' => 'お問い合わせ内容は必須項目です。']);
        // 2000文字より多い
        $this->withSession(['input' => ['body' => str_repeat('a', ContactConsts::BODY_LENGTH_MAX + 1)]])->post(route('contact.store'))->assertInvalid(['body' => 'お問い合わせ内容の文字数は、2000文字以下である必要があります。']);

    }


    /** @test */
    public function 正常な画面遷移(): void
    {
        $this->actingAs($this->_web, 'web');

        // 入力画面から確認画面
        $response = $this->from(route('contact.create'))->post(route('contact.confirm'), ['body' => 'aaa']);
        $response->assertValid();
        $response->assertStatus(200)->assertSee('お問い合わせ確認');

        // 確認画面から入力画面
        $response = $this->from(route('contact.confirm'))->post(route('contact.store'), ['body' => 'aaa']);
        $response->assertRedirect(route('contact.create'));
        // 実際にリダイレクト後のページに GET リクエストを送って、その内容を確認
        $response = $this->get(route('contact.create'));
        $response->assertSee('aaa'); // "aaa" が表示されているか確認
    }


    /** @test */
    public function 正常な処理(): void
    {
        $this->actingAs($this->_web, 'web');

        // メール送信チェック
        Mail::fake();
        // メールが送られていないことを確認
        Mail::assertNothingSent();

        $this->from(route('contact.create'))->post(route('contact.confirm'), ['body' => 'aaa']);
        $response = $this->from(route('contact.confirm'))->post(route('contact.store'), ['submit' => ' submit']);

        // リダイレクト
        $response->assertRedirect(route('contact.complete'));
        $response = $this->get(route('contact.complete'));
        $response->assertSee('000000000001'); // お問い合わせ番号が表示されているか確認

        // DB存在チェック
        $this->assertDatabaseHas('contacts', [
            'no' => '000000000001',
            'body' => 'aaa',
        ]);

        // メッセージが指定したユーザーに届いたことをアサート
        Mail::assertSent(ContactMail::class, function ($mail) {
            return $mail->hasTo('test1@test.co.jp');
        });

        // メールが1回送信されたことをアサート
        Mail::assertSent(ContactMail::class, 1);

        // もう一度アクセスしなおすとセッション切れでトップページへ遷移
        $response = $this->get(route('contact.complete'));
        $response->assertRedirect(route('top'));
        // セッションにメッセージが存在するか確認
        $response->assertSessionHas('msg_failure', 'セッション期限が切れました。');
    }

}
