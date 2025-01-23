<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tag\CreateRequest;
use App\Http\Requests\Admin\Tag\IndexRequest;
use App\Http\Requests\Admin\Tag\EditRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Models\Tag;
use App\Consts\TagConsts;

class TagController extends Controller
{
    //
    public function create(): View
    {
        return view('admin.tag.create');
    }


    public function store(CreateRequest $request): RedirectResponse
    {
        $model = new Tag();
        $model->insertTag($request->validated());

        return redirect()->route('admin.tag.index')->with('msg_success', 'タグを登録しました。');
    }


    public function index(IndexRequest $request): View
    {
        $input = $request->validated();
        $model = new Tag();
        $lists = $model->getAdminLists($input);

        return view('admin.tag.index', compact('lists', 'input'));
    }


    public function show(Tag $tag): View
    {
        $deleteFlg = true;
        if ($tag->products()->exists()) {
            $deleteFlg = false;
        }

        return view('admin.tag.show', compact('tag', 'deleteFlg'));
    }


    public function edit(Tag $tag): View
    {
        return view('admin.tag.edit', compact('tag'));
    }


    public function update(EditRequest $request, Tag $tag): RedirectResponse
    {
        $model = new Tag();
        $tag = $model->updateTag($request->validated(), $tag);

        return redirect()->route('admin.tag.show', $tag)->with('msg_success', 'タグを編集しました。');
    }


    public function destroy(Tag $tag): RedirectResponse
    {
        // 製品と紐づいているタグは削除できない
        if ($tag->products()->exists()) {
            return redirect()->route('admin.tag.show', $tag)->with('msg_failure', 'このタグは削除できません。');
        } else {
            $tag->delete();
        }

        return redirect()->route('admin.tag.index')->with('msg_success', 'タグを削除しました。');
    }
}
