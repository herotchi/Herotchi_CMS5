<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\News\CreateRequest;
use App\Http\Requests\Admin\News\IndexRequest;
use App\Http\Requests\Admin\News\EditRequest;
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

        return view('admin.news.index', compact('lists', 'input'));
    }


    public function show(News $news): View
    {
        return view('admin.news.show', compact('news'));
    }


    public function edit(News $news): View
    {
        return view('admin.news.edit', compact('news'));
    }


    public function update(EditRequest $request, News $news): RedirectResponse
    {
        $model = new News();
        $news = $model->updateNews($request->validated(), $news);

        return redirect()->route('admin.news.show', $news)->with('msg_success', 'お知らせを編集しました。');
    }


    public function destroy(News $news): RedirectResponse
    {
        $news->delete();

        return redirect()->route('admin.news.index')->with('msg_success', 'お知らせを削除しました。');
    }
}
