<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\SecondCategory\CreateRequest;
use App\Http\Requests\Admin\SecondCategory\IndexRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Models\FirstCategory;
use App\Models\SecondCategory;
use App\Consts\SecondCategoryConsts;



class SecondCategoryController extends Controller
{
    //
    public function create(): View
    {
        $model = new FirstCategory();
        $firstCategories = $model->getLists();

        return view('admin.second_category.create', compact('firstCategories'));
    }


    public function store(CreateRequest $request): RedirectResponse
    {
        $model = new SecondCategory();
        $model->insertSecondCategory($request->validated());

        return redirect()->route('admin.second_category.index')->with('msg_success', '中カテゴリを登録しました。');
    }


    public function index(IndexRequest $request)
    {
        $input = $request->validated();

        $firstCategoryModel = new FirstCategory();
        $firstCategories = $firstCategoryModel->getLists();

        $secondCategoryModel = new SecondCategory();
        $lists = $secondCategoryModel->getAdminLists($input);

        return view('admin.second_category.index', compact(['firstCategories', 'lists', 'input']));
    }
}
