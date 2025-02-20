<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FirstCategory\CreateRequest;
use App\Http\Requests\Admin\FirstCategory\IndexRequest;
use App\Http\Requests\Admin\FirstCategory\EditRequest;
use App\Http\Requests\Admin\FirstCategory\CsvImportRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\FirstCategory;
use App\Consts\FirstCategoryConsts;

use SplFileObject;
use DateTime;

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

        // 検索条件セッションを持っていて、GETパラメータがない場合
        if ($request->session()->has('first_category') && !$request->hasAny($request->forms)) {
            //　セッションから検索条件を取得する
            $input = $request->session()->pull('first_category');
        }
        $request->session()->put('first_category', $input);

        $model = new FirstCategory();
        $lists = $model->getAdminLists($input);

        return view('admin.first_category.index', compact('lists', 'input'));
    }


    public function show(FirstCategory $firstCategory): View
    {
        $deleteFlg = true;
        if ($firstCategory->second_categories()->exists() || $firstCategory->products()->exists()) {
            $deleteFlg = false;
        }

        return view('admin.first_category.show', compact('firstCategory', 'deleteFlg'));
    }


    public function edit(FirstCategory $firstCategory): View
    {
        return view('admin.first_category.edit', compact('firstCategory'));
    }


    public function update(EditRequest $request, FirstCategory $firstCategory): RedirectResponse
    {
        $model = new FirstCategory();
        $firstCategory = $model->updateFirstCategory($request->validated(), $firstCategory);

        return redirect()->route('admin.first_category.show', $firstCategory)->with('msg_success', '大カテゴリを編集しました。');
    }


    public function destroy(FirstCategory $firstCategory): RedirectResponse
    {
        // 中カテゴリもしくは製品情報と紐づいている大カテゴリは削除できない
        if ($firstCategory->second_categories()->exists() || $firstCategory->products()->exists()) {
            return redirect()->route('admin.first_category.show', $firstCategory)->with('msg_failure', 'この大カテゴリは削除できません。');
        } else {
            $firstCategory->delete();
        }

        return redirect()->route('admin.first_category.index')->with('msg_success', '大カテゴリを削除しました。');
    }


    public function csv_upload(): View
    {
        return view('admin.first_category.csv_upload');
    }


    public function csv_import(CsvImportRequest $request): RedirectResponse
    {
        $input = $request->validated();
        $today = new DateTime();
        $fileName = $today->format('YmdHis') . '.csv';

        if($request->hasFile('csv_file')) {
            if($request->csv_file->getClientOriginalExtension() !== "csv") {
                return redirect()->route('admin.first_category.csv_upload')->with('msg_failure', 'CSVファイル以外のファイルがアップロードされました。')->withInput();
            }
            $request->csv_file->storeAs(FirstCategoryConsts::CSV_FILE_DIR, $fileName, 'public');
        } else {
            return redirect()->route('admin.first_category.csv_upload')->with('msg_failure', 'CSVファイルの取得に失敗しました。')->withInput();
        }

        $csvs = new SplFileObject(storage_path('app/public/'. FirstCategoryConsts::CSV_FILE_DIR . '/' . $fileName));
        $csvs->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD);

        $rules = [
            'name' => [
                'bail', 
                'required',
                'string',
                'max:' . FirstCategoryConsts::NAME_LENGTH_MAX
            ]
        ];
        $attributes = [
            'name' => '大カテゴリ名',
        ];
        $errorMessages = [];
        $lines = [];

        // 大カテゴリ名ユニークチェック用
        $model = new FirstCategory();
        $firstCategories = $model::all();
        $names = $firstCategories->pluck('name')->toArray();

        // CSVファイル内の重複チェック用
        $inputNames = [];

        foreach ($csvs as $line => $csv) {

            // 文字コード変換
            if ($input['code'] == FirstCategoryConsts::CSV_CODE_SJIS) {
                mb_convert_variables('UTF-8','SJIS', $csv);
            } else {
                // UTF-8 BOM付きのファイルならBOMを消す
                $csv = preg_replace('/^\xEF\xBB\xBF/', '', $csv);
            }

            // ヘッダー行
            if ($line === 0) {
                if (array_values(FirstCategoryConsts::CSV_HEADER) !== $csv) {
                    $errorMessages['csv_file'][] = $line + 1 . '行目：ヘッダーの項目名が違っています。';
                }
                continue;
            }

            // 1行あたりの項目数が足りない場合
            $filteredArray = array_filter($csv, function ($value) {
                return $value !== NULL;
            });
            if (count($filteredArray) !== count(FirstCategoryConsts::CSV_HEADER)) {
                $errorMessages['csv_file'][] = $line + 1 . '行目：項目に過不足があります。';
                continue;
            }

            // バリデート用にCSVデータを整形する
            foreach (array_keys(FirstCategoryConsts::CSV_HEADER) as $key => $value) {
                $lines[$line + 1][$value] = $csv[$key];
            }

            $validator = Validator::make($lines[$line + 1], $rules, __('validation'), $attributes);
            // バリデーションエラーがあった場合
            if($validator->fails()) {
                // エラーメッセージを「xx行目：エラーメッセージ」の形に整える
                $errorMessages['csv_file'][] = $line + 1 . '行目：' . $validator->errors()->first();
            } elseif (in_array($lines[$line + 1]['name'], $names, true)) {
                // 既にある大カテゴリ名と重複した場合
                $errorMessages['csv_file'][] = $line + 1 . '行目：既に存在する大カテゴリ名と重複しています。';
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
            Storage::disk('public')->delete(FirstCategoryConsts::CSV_FILE_DIR . '/' . $fileName);

            return redirect()->route('admin.first_category.csv_upload')->withErrors($errorMessages)->withInput();
        }

        DB::transaction(function () use ($lines) {
            DB::table('first_categories')->insert($lines);
        });

        $csvs = null;
        Storage::disk('public')->delete(FirstCategoryConsts::CSV_FILE_DIR . '/' . $fileName);

        return redirect()->route('admin.first_category.index')->with('msg_success', '大カテゴリを一括登録しました。');
    }
}
