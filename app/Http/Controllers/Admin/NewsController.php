<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\News\CreateRequest;
use App\Http\Requests\Admin\News\IndexRequest;
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


    public function index(IndexRequest $request): View
    {
        $input = $request->validated();
        $model = new News();
        $lists = $model->getAdminLists($input);

        return view('admin.news.index', compact(['lists', 'input']));
    }


    public function show(News $news): View
    {
        return view('admin.news.show', compact('news'));
    }


    public function edit()
    {
        var_dump(__LINE__);
    }
}
