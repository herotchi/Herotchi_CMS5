<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\Media\CreateRequest;
//use App\Http\Requests\Admin\Media\IndexRequest;
//use App\Http\Requests\Admin\Media\EditRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Media;

use App\Consts\MediaConsts;
use App\Http\Requests\Admin\Media\IndexRequest;

class MediaController extends Controller
{
    //
    public function create(): View
    {
        return view('admin.media.create');
    }


    public function store(CreateRequest $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $image = $request->file('image');
            $fileName = $image->hashName();
            $image->storeAs(MediaConsts::IMAGE_FILE_DIR, $fileName, 'public');

            $model = new Media();
            $model->insertMedia($request->validated(), $fileName);
        });

        return redirect()->route('admin.media.index')->with('msg_success', 'メディアを登録しました。');
    }


    public function index(IndexRequest $request): View
    {
        $input = $request->validated();

        $model = new Media();
        $lists = $model->getAdminLists($input);

        return view('admin.media.index', compact('lists', 'input'));
    }


    public function show()
    {
        var_dump(__LINE__);
    }
}
