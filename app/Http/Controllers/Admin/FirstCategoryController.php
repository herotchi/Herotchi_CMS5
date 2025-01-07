<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FirstCategory\CreateRequest;
use App\Http\Requests\Admin\FirstCategory\IndexRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Models\FirstCategory;
use App\Consts\FirstCategoryConsts;

class FirstCategoryController extends Controller
{
    //
    public function create(): View
    {
        return view('admin.first_category.create');
    }


    public function store(CreateRequest $request): RedirectResponse
    {
        $model = new FirstCategory();
        $model->insertFirstCategory($request->validated());

        return redirect()->route('admin.first_category.index')->with('msg_success', '大カテゴリを登録しました。');
    }


    public function index(IndexRequest $request): View
    {
        $input = $request->validated();
        $model = new FirstCategory();
        $lists = $model->getAdminLists($input);

        return view('admin.first_category.index', compact(['lists', 'input']));
    }


    public function show(FirstCategory $firstCategory): View
    {
        return view('admin.first_category.show', compact('firstCategory'));
    }


    public function edit(FirstCategory $firstCategory): View
    {
        return view('admin.first_category.edit', compact('firstCategory'));
    }
}
