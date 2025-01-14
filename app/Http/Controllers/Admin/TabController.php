<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tab\CreateRequest;
//use App\Http\Requests\Admin\Tab\IndexRequest;
//use App\Http\Requests\Admin\Tab\EditRequest;
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


    public function index()
    {
        var_dump(__LINE__);
    }
}
