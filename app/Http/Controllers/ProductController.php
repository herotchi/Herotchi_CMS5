<?php

namespace App\Http\Controllers;

use App\Http\Requests\Product\IndexRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Models\Product;
use App\Models\FirstCategory;
use App\Models\SecondCategory;
use App\Models\Tag;


class ProductController extends Controller
{
    //
    public function index(IndexRequest $request): View
    {
        $firstCategoryModel = new FirstCategory();
        $firstCategories = $firstCategoryModel->getLists();

        $secondCategoryModel = new SecondCategory();
        $secondCategories = $secondCategoryModel->getLists();

        $tagModel = new Tag();
        $tags = $tagModel->getLists();

        $input = $request->validated();

        // 検索条件セッションを持っていて、GETパラメータがない場合
        if ($request->session()->has('public_product') && !$request->hasAny($request->forms)) {
            //　セッションから検索条件を取得する
            $input = $request->session()->pull('public_product');
        }
        $request->session()->put('public_product', $input);

        $productModel = new Product();
        $lists = $productModel->getLists($input);

        return view('product.index', compact('firstCategories', 'secondCategories', 'tags', 'input', 'lists'));
    }


    public function show(Product $product): View
    {
        return view('product.show', compact('product'));
    }
}
