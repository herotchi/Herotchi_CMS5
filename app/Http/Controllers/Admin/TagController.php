<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tag\CreateRequest;
use App\Http\Requests\Admin\Tag\IndexRequest;
use App\Http\Requests\Admin\Tag\EditRequest;
use App\Http\Requests\Admin\Tag\CsvImportRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\Tag;
use App\Consts\TagConsts;

use SplFileObject;
use DateTime;

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


    public function csv_upload(): View
    {
        return view('admin.tag.csv_upload');
    }


    public function csv_import(CsvImportRequest $request): RedirectResponse
    {
        $input = $request->validated();
        $today = new DateTime();
        $fileName = $today->format('YmdHis') . '.csv';

        if($request->hasFile('csv_file')) {
            if($request->csv_file->getClientOriginalExtension() !== "csv") {
                return redirect()->route('admin.tag.csv_upload')->with('msg_failure', 'CSVファイル以外のファイルがアップロードされました。')->withInput();
            }
            $request->csv_file->storeAs(TagConsts::CSV_FILE_DIR, $fileName, 'public');
        } else {
            return redirect()->route('admin.tag.csv_upload')->with('msg_failure', 'CSVファイルの取得に失敗しました。')->withInput();
        }

        $csvs = new SplFileObject(storage_path('app/public/'. TagConsts::CSV_FILE_DIR . '/' . $fileName));
        $csvs->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD);

        $rules = [
            'name' => [
                'bail', 
                'required',
                'string',
                'max:' . TagConsts::NAME_LENGTH_MAX
            ]
        ];
        $attributes = [
            'name' => 'タグ名',
        ];
        $errorMessages = [];
        $lines = [];

        // 大カテゴリ名ユニークチェック用
        $model = new Tag();
        $tags = $model::all();
        $names = $tags->pluck('name')->toArray();

        // CSVファイル内の重複チェック用
        $inputNames = [];

        foreach ($csvs as $line => $csv) {

            // 文字コード変換
            if ($input['code'] == TagConsts::CSV_CODE_SJIS) {
                mb_convert_variables('UTF-8','SJIS', $csv);
            } else {
                // UTF-8 BOM付きのファイルならBOMを消す
                $csv = preg_replace('/^\xEF\xBB\xBF/', '', $csv);
            }

            // ヘッダー行
            if ($line === 0) {
                if (array_values(TagConsts::CSV_HEADER) !== $csv) {
                    $errorMessages['csv_file'][] = $line + 1 . '行目：ヘッダーの項目名が違っています。';
                }
                continue;
            }

            // 1行あたりの項目数が足りない場合
            $filteredArray = array_filter($csv, function ($value) {
                return $value !== NULL;
            });
            if (count($filteredArray) !== count(TagConsts::CSV_HEADER)) {
                $errorMessages['csv_file'][] = $line + 1 . '行目：項目に過不足があります。';
                continue;
            }

            // バリデート用にCSVデータを整形する
            foreach (array_keys(TagConsts::CSV_HEADER) as $key => $value) {
                $lines[$line + 1][$value] = $csv[$key];
            }

            $validator = Validator::make($lines[$line + 1], $rules, __('validation'), $attributes);
            // バリデーションエラーがあった場合
            if($validator->fails()) {
                // エラーメッセージを「xx行目：エラーメッセージ」の形に整える
                $errorMessages['csv_file'][] = $line + 1 . '行目：' . $validator->errors()->first();
            } elseif (in_array($lines[$line + 1]['name'], $names, true)) {
                // 既にある大カテゴリ名と重複した場合
                $errorMessages['csv_file'][] = $line + 1 . '行目：既に存在するタグ名と重複しています。';
            } elseif (in_array($lines[$line + 1]['name'], $inputNames, true)) {
                // CSVファイル内で重複があった場合
                $errorMessages['csv_file'][] = $line + 1 . '行目：CSVファイル内で重複しています。';
            } else {
                // 入力エラーがない場合
                $lines[$line + 1]['created_at'] = $today->format('Y-m-d H:i:s');
                $lines[$line + 1]['updated_at'] = $today->format('Y-m-d H:i:s');

                $inputNames[] = $lines[$line + 1]['name'];
            }
        }

        // CSVファイル内で入力エラーがあった場合
        if (count($errorMessages) > 0) {
            $csvs = null;
            Storage::disk('public')->delete(TagConsts::CSV_FILE_DIR . '/' . $fileName);

            return redirect()->route('admin.tag.csv_upload')->withErrors($errorMessages)->withInput();
        }

        DB::transaction(function () use ($lines) {
            DB::table('tags')->insert($lines);
        });

        $csvs = null;
        Storage::disk('public')->delete(TagConsts::CSV_FILE_DIR . '/' . $fileName);

        return redirect()->route('admin.tag.index')->with('msg_success', 'タグを一括登録しました。');
    }
}
