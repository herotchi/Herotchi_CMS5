<?php

namespace Tests\Feature\Admin\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    private $_admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->_admin = Admin::factory()->create([
            'login_id' => 'test1test1',
            'password' => Hash::make('test1test1')
        ]);

        $this->withHeaders([
            'Authorization' => 'Basic ' . base64_encode('test1test1:test1test1'),
        ]);
    }

    /** @test */
    public function ログイン画面の表示(): void
    {
        // 200
        $this->get(route('admin.create'))->assertOk();
    }

    /** @test */
    public function ログイン成功(): void
    {
        $this->post(route('admin.store'), [
            'login_id' => 'test1test1',
            'password' => 'test1test1'
        ])->assertRedirect(route('admin.top'));

        $this->assertAuthenticatedAs($this->_admin, 'admin');
    }

    /** @test */
    public function ログイン失敗()
    {
        // ログインIDが一致しない場合
        $this->from(route('admin.store'))->post(route('admin.store'), [
            'login_id' => 'test2test2',
            'password' => 'test1test1'
        ])->assertRedirect(route('admin.create'))
        ->assertInvalid(['login_id' => '認証に失敗しました']);

        // パスワードが一致しない場合
        $this->from(route('admin.store'))->post(route('admin.store'), [
            'login_id' => 'test1test1',
            'password' => 'test2test2'
        ])->assertRedirect(route('admin.create'))
        ->assertInvalid(['login_id' => '認証に失敗しました']);

        $this->assertGuest('admin');
    }

    /** @test */
    public function バリデーション(): void
    {
        $url = route('admin.store');

        // リダイレクト
        $this->from(route('admin.create'))->post($url, ['login_id' => ''])
        ->assertRedirect(route('admin.create'));

        // ID未入力
        $this->post($url, ['login_id' => ''])->assertInvalid(['login_id' => 'ログインIDは必須']);

        $this->post($url, ['login_id' => 'a'])->assertValid('login_id');

        // パスワード未入力
        $this->post($url, ['password' => ''])->assertInvalid(['password' => 'パスワードは必須']);

        $this->post($url, ['password' => 'a'])->assertValid('password');

    }
}
