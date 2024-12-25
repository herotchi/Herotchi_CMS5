<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthenticatedSessionController;
use App\Http\Controllers\Admin\TopController as AdminTopController;

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

    Route::get('top', [AdminTopController::class, 'index'])->name('admin.top')->middleware('auth:admin');
});


require __DIR__.'/auth.php';
