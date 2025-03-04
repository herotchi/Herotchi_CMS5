<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // ベーシック認証
        $middleware->append(\App\Http\Middleware\BasicAuthMiddleware::class);
        
        // 非ログインユーザー用のリダイレクト設定
        $middleware->redirectGuestsTo(function (Request $request) {
            // パスがadmin/から始まる場合、管理者ログイン画面へリダイレクト
            if ($request->is('admin/*')) {
                return route('admin.create');
            }

            // そうでない場合は一般ユーザーログイン画面へリダイレクト
            return route('login');
        });

        // ログインユーザー用のリダイレクト設定
        $middleware->redirectUsersTo(function () {

            // 管理者だった場合、書籍一覧画面へリダイレクト
            if (Auth::guard('admin')->check()) {
                return route('admin.top');
            }

            // 一般ユーザーだった場合はダッシュボードへリダイレクト
            return route('top');
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
