<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthenticatedSessionController;
use App\Http\Controllers\Admin\TopController as AdminTopController;
use App\Http\Controllers\Admin\NewsController as AdminNewsController;
use App\Http\Controllers\Admin\FirstCategoryController;
use App\Http\Controllers\Admin\SecondCategoryController;
use App\Http\Controllers\Admin\TabController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\MediaController;
use App\Http\Controllers\Admin\ContactController as AdminContactController;
use App\Http\Controllers\Admin\ProfileController as AdminProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::prefix('admin')->group(function () {
    Route::name('admin.')->controller(AuthenticatedSessionController::class)->group(function () {
        Route::get('login', 'create')->name('create')->middleware('guest:admin');
        Route::post('login', 'store')->name('store')->middleware('guest:admin');
        Route::post('logout', 'destroy')->name('destroy')->middleware('auth:admin');
    });

    Route::name('admin.')->middleware('auth:admin')->group(function () {
        Route::get('top', [AdminTopController::class, 'index'])->name('top');

        Route::prefix('news')->name('news.')->controller(AdminNewsController::class)->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('{news}', 'show')->whereNumber('news')->name('show');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
            Route::get('{news}/edit', 'edit')->name('edit')->whereNumber('news');
            Route::put('{news}', 'update')->name('update')->whereNumber('news');
            Route::delete('{news}', 'destroy')->name('destroy')->whereNumber('news');
        });

        Route::prefix('first_category')->name('first_category.')->controller(FirstCategoryController::class)->group(function () {
            Route::get('', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('store', 'store')->name('store');
        });
    });
/*
    Route::prefix('first_category')->name('first_category.')->controller(FirstCategoryController::class)->group(function () {
        Route::get('', 'index')->name('index')->middleware('auth:admin');
        Route::get('{first_category}', 'show')->whereNumber('first_category')->name('show')->middleware('auth:admin');
        Route::get('create', 'create')->name('create')->middleware('auth:admin');
        Route::post('store', 'store')->name('store')->middleware('auth:admin');
        Route::get('{first_category}/edit', 'edit')->whereNumber('first_category')->name('edit')->middleware('auth:admin');
        Route::put('{first_category}/update', 'update')->whereNumber('first_category')->name('update')->middleware('auth:admin');
        Route::delete('{first_category}/delete', 'delete')->whereNumber('first_category')->name('delete')->middleware('auth:admin');
    });*/

    Route::prefix('second_category')->name('second_category.')->controller(SecondCategoryController::class)->group(function () {
        Route::get('', 'index')->name('index')->middleware('auth:admin');
        Route::get('{second_category}', 'show')->whereNumber('second_category')->name('show')->middleware('auth:admin');
        Route::get('create', 'create')->name('create')->middleware('auth:admin');
        Route::post('store', 'store')->name('store')->middleware('auth:admin');
        Route::get('{second_category}/edit', 'edit')->whereNumber('second_category')->name('edit')->middleware('auth:admin');
        Route::put('{second_category}/update', 'update')->whereNumber('second_category')->name('update')->middleware('auth:admin');
        Route::delete('{second_category}/delete', 'delete')->whereNumber('second_category')->name('delete')->middleware('auth:admin');
    });

    Route::prefix('tab')->name('tab.')->controller(TabController::class)->group(function () {
        Route::get('', 'index')->name('index')->middleware('auth:admin');
        Route::get('{tab}', 'show')->whereNumber('tab')->name('show')->middleware('auth:admin');
        Route::get('create', 'create')->name('create')->middleware('auth:admin');
        Route::post('store', 'store')->name('store')->middleware('auth:admin');
        Route::get('{tab}/edit', 'edit')->whereNumber('tab')->name('edit')->middleware('auth:admin');
        Route::put('{tab}/update', 'update')->whereNumber('tab')->name('update')->middleware('auth:admin');
        Route::delete('{tab}/delete', 'delete')->whereNumber('tab')->name('delete')->middleware('auth:admin');
    });

    Route::prefix('product')->name('product.')->controller(AdminProductController::class)->group(function () {
        Route::get('', 'index')->name('index')->middleware('auth:admin');
        Route::get('{product}', 'show')->whereNumber('product')->name('show')->middleware('auth:admin');
        Route::get('create', 'create')->name('create')->middleware('auth:admin');
        Route::post('store', 'store')->name('store')->middleware('auth:admin');
        Route::get('{product}/edit', 'edit')->whereNumber('product')->name('edit')->middleware('auth:admin');
        Route::put('{product}/update', 'update')->whereNumber('product')->name('update')->middleware('auth:admin');
        Route::delete('{product}/delete', 'delete')->whereNumber('product')->name('delete')->middleware('auth:admin');
    });

    Route::prefix('media')->name('media.')->controller(MediaController::class)->group(function () {
        Route::get('', 'index')->name('index')->middleware('auth:admin');
        Route::get('{media}', 'show')->whereNumber('media')->name('show')->middleware('auth:admin');
        Route::get('create', 'create')->name('create')->middleware('auth:admin');
        Route::post('store', 'store')->name('store')->middleware('auth:admin');
        Route::get('{media}/edit', 'edit')->whereNumber('media')->name('edit')->middleware('auth:admin');
        Route::put('{media}/update', 'update')->whereNumber('media')->name('update')->middleware('auth:admin');
        Route::delete('{media}/delete', 'delete')->whereNumber('media')->name('delete')->middleware('auth:admin');
    });

    Route::prefix('contact')->name('contact.')->controller(AdminContactController::class)->group(function () {
        Route::get('', 'index')->name('index')->middleware('auth:admin');
        Route::get('{contact}', 'show')->whereNumber('contact')->name('show')->middleware('auth:admin');
        Route::get('create', 'create')->name('create')->middleware('auth:admin');
        Route::post('store', 'store')->name('store')->middleware('auth:admin');
        Route::get('{contact}/edit', 'edit')->whereNumber('contact')->name('edit')->middleware('auth:admin');
        Route::put('{contact}/update', 'update')->whereNumber('contact')->name('update')->middleware('auth:admin');
        Route::delete('{contact}/delete', 'delete')->whereNumber('contact')->name('delete')->middleware('auth:admin');
    });

    Route::prefix('profile')->name('profile.')->controller(AdminProfileController::class)->group(function () {
        Route::get('', 'index')->name('index')->middleware('auth:admin');
        Route::get('{user}', 'show')->whereNumber('user')->name('show')->middleware('auth:admin');
        Route::get('create', 'create')->name('create')->middleware('auth:admin');
        Route::post('store', 'store')->name('store')->middleware('auth:admin');
        Route::get('{user}/edit', 'edit')->whereNumber('user')->name('edit')->middleware('auth:admin');
        Route::put('{user}/update', 'update')->whereNumber('user')->name('update')->middleware('auth:admin');
        Route::delete('{user}/delete', 'delete')->whereNumber('user')->name('delete')->middleware('auth:admin');
    });
});


require __DIR__.'/auth.php';
