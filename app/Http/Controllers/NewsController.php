<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Models\News;

class NewsController extends Controller
{
    //
    public function index(): View
    {
        $model = new News();
        $lists = $model->getLists();

        return view('news.index', compact('lists'));
    }


    public function show()
    {

    }
}
