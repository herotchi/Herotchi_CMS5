<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\Product\CreateRequest;
use App\Http\Requests\Admin\Product\IndexRequest;
use App\Http\Requests\Admin\Product\EditRequest;
//use App\Http\Requests\Admin\Product\BatchDeleteRequest;

use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\FirstCategory;
use App\Models\SecondCategory;
use App\Models\Tab;
use App\Models\News;

use App\Consts\ProductConsts;

class ProductController extends Controller
{
    //
    public function create(): View
    {
        $firstCategoryModel = new FirstCategory();
        $firstCategories = $firstCategoryModel->getLists();

        $secondCategoryModel = new SecondCategory();
        $secondCategories = $secondCategoryModel->getLists();

        $tabModel = new Tab();
        $tabs = $tabModel->getLists();

        return view('admin.product.create', compact('firstCategories', 'secondCategories', 'tabs'));
    }


    public function store(CreateRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $input = $request->validated();
            $image = $request->file('image');
            $fileName = $image->hashName();
            $image->storeAs(ProductConsts::IMAGE_FILE_DIR, $fileName, 'public');

            $productModel = new Product();
            $product = $productModel->insertProduct($input, $fileName);
            $product->tabs()->attach($input['tab_ids']);
            
            $newsModel = new News();
            $newsModel->saveProductNews($product, ProductConsts::PRODUCT_NEWS_INSERT_MESSAGE);
        });

        return redirect()->route('admin.product.index')->with('msg_success', '製品情報を登録しました。');
    }


    public function index(IndexRequest $request): View
    {
        $firstCategoryModel = new FirstCategory();
        $firstCategories = $firstCategoryModel->getLists();

        $secondCategoryModel = new SecondCategory();
        $secondCategories = $secondCategoryModel->getLists();

        $tabModel = new Tab();
        $tabs = $tabModel->getLists();

        $input = $request->validated();
        $productModel = new Product();
        $lists = $productModel->getAdminLists($input);

        return view('admin.product.index', compact('firstCategories', 'secondCategories', 'tabs', 'input', 'lists'));
    }


    public function show(Product $product): View
    {
        return view('admin.product.show', compact('product'));
    }


    public function edit(Product $product): View
    {
        $firstCategoryModel = new FirstCategory();
        $firstCategories = $firstCategoryModel->getLists();

        $secondCategoryModel = new SecondCategory();
        $secondCategories = $secondCategoryModel->getLists();

        $tabModel = new Tab();
        $tabs = $tabModel->getLists();

        return view('admin.product.edit', compact('firstCategories', 'secondCategories', 'tabs', 'product'));
    }


    public function update(EditRequest $request, Product $product): RedirectResponse
    {
        DB::transaction(function () use ($request, $product) {
            $input = $request->validated();
            $image = $request->file('image');
            if ($image) {
                $fileName = $image->hashName();
                $image->storeAs(ProductConsts::IMAGE_FILE_DIR, $fileName, 'public');
            } else {
                $fileName = '';
            }

            $productModel = new Product();
            $product = $productModel->updateProduct($input, $product, $fileName);
            $product->tabs()->sync($input['tab_ids']);

            $newsModel = new News();
            $newsModel->saveProductNews($product, ProductConsts::PRODUCT_NEWS_UPDATE_MESSAGE);
        });

        return redirect()->route('admin.product.show', $product)->with('msg_success', '製品情報を編集しました。');
    }
}
