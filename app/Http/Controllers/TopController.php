<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Models\News;
use App\Models\Media;

class TopController extends Controller
{
    //
    public function index(): View
    {
        $newsModel = new News();
        $news = $newsModel->getTopNews();

        $mediaModel = new Media();
        $carousels = $mediaModel->getCarousels();
        $pickUps = $mediaModel->getPickUps();

        return view('top', compact('news', 'carousels', 'pickUps'));
    }


    public function terms_of_use()
    {
        return view('terms_of_use');
    }


    public function privacy_policy()
    {
        return view('privacy_policy');
    }
}
