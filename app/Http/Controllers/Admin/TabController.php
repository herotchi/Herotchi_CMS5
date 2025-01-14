<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tab\CreateRequest;
use App\Http\Requests\Admin\Tab\IndexRequest;
use App\Http\Requests\Admin\Tab\EditRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Models\Tab;
use App\Consts\TabConsts;

class TabController extends Controller
{
    //
    public function create(): View
    {
        return view('admin.tab.create');
    }


    public function store(CreateRequest $request): RedirectResponse
    {
        $model = new Tab();
        $model->insertTab($request->validated());

        return redirect()->route('admin.tab.index')->with('msg_success', 'タブを登録しました。');
    }


    public function index(IndexRequest $request): View
    {
        $input = $request->validated();
        $model = new Tab();
        $lists = $model->getAdminLists($input);

        return view('admin.tab.index', compact('lists', 'input'));
    }


    public function show(Tab $tab): View
    {
        return view('admin.tab.show', compact('tab'));
    }


    public function edit(Tab $tab): View
    {
        return view('admin.tab.edit', compact('tab'));
    }


    public function update(EditRequest $request, Tab $tab): RedirectResponse
    {
        $model = new Tab();
        $tab = $model->updateTab($request->validated(), $tab);

        return redirect()->route('admin.tab.show', $tab)->with('msg_success', 'タブを編集しました。');
    }


    public function destroy(Tab $tab): RedirectResponse
    {
        // 製品と紐づいているタブがある場合は削除できないように仕様追加
        $tab->delete();

        return redirect()->route('admin.tab.index')->with('msg_success', 'タブを削除しました。');
    }
}
