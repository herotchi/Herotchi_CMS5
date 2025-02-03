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
        $productModel = new Product();
        $lists = $productModel->getLists($input);

        return view('product.index', compact('firstCategories', 'secondCategories', 'tags', 'input', 'lists'));
    }


    public function show(Product $product)
    {
        var_dump(__LINE__);
    }
}
