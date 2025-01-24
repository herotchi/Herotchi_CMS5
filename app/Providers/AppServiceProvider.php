<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;

use Illuminate\Pagination\Paginator;
use App\Consts\NewsConsts;
use App\Consts\FirstCategoryConsts;
use App\Consts\SecondCategoryConsts;
use App\Consts\TagConsts;
use App\Consts\ProductConsts;
use App\Consts\MediaConsts;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Paginator::useBootstrapFive();
        View::share('NewsConsts', NewsConsts::class);
        View::share('FirstCategoryConsts', FirstCategoryConsts::class);
        View::share('SecondCategoryConsts', SecondCategoryConsts::class);
        View::share('TagConsts', TagConsts::class);
        View::share('ProductConsts', ProductConsts::class);
        View::share('MediaConsts', MediaConsts::class);
    }
}
