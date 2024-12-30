<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\News\CreateRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\News;
use App\Consts\NewsConsts;


class NewsController extends Controller
{
    //
    public function create(): View
    {
        return view('admin.news.create');
    }


    public function store(CreateRequest $request): RedirectResponse
    {
        $model = new News();
        $model->insertNews($request->validated());

        return redirect()->route('admin.news.index')->with('msg_success', 'お知らせを登録しました。');
    }


    public function index()
    {
        var_dump(__LINE__);
    }
}
