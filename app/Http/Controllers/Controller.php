<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

abstract class Controller
{
    //
    public function __construct()
    {
        View::share('page', Route::currentRouteName());
    }
}
