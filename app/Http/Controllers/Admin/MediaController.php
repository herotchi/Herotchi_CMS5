<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\Media\CreateRequest;
use App\Http\Requests\Admin\Media\IndexRequest;
use App\Http\Requests\Admin\Media\EditRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

use App\Models\Media;

use App\Consts\MediaConsts;

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

        // 検索条件セッションを持っていて、GETパラメータがない場合
        if ($request->session()->has('media') && !$request->hasAny($request->forms)) {
            //　セッションから検索条件を取得する
            $input = $request->session()->pull('media');
        }
        $request->session()->put('media', $input);

        $model = new Media();
        $lists = $model->getAdminLists($input);

        return view('admin.media.index', compact('lists', 'input'));
    }


    public function show(Media $media): View
    {
        return view('admin.media.show', compact('media'));
    }


    public function edit(Media $media): View
    {
        return view('admin.media.edit', compact('media'));
    }


    public function update(EditRequest $request, Media $media) :RedirectResponse
    {
        DB::transaction(function () use ($request, $media) {
            $input = $request->validated();
            $previousImages = explode('/', $media->image);
            $image = $request->file('image');
            if ($image) {
                $fileName = $image->hashName();
                $image->storeAs(MediaConsts::IMAGE_FILE_DIR, $fileName, 'public');
            } else {
                $fileName = '';
            }

            $mediaModel = new Media();
            $media = $mediaModel->updateMedia($input, $fileName, $media);

            if ($fileName !== '') {
                Storage::disk('public')->delete(MediaConsts::IMAGE_FILE_DIR . '/' . $previousImages[2]);
            }
        });

        return redirect()->route('admin.media.show', $media)->with('msg_success', 'メディアを編集しました。');
    }


    public function destroy(Media $media): RedirectResponse
    {
        DB::transaction(function () use ($media) {
            $previousImages = explode('/', $media->image);
            $media->delete();
            Storage::disk('public')->delete(MediaConsts::IMAGE_FILE_DIR . '/' . $previousImages[2]);
        });

        return redirect()->route('admin.media.index')->with('msg_success', 'メディアを削除しました。');
    }
}
