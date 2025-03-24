<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Admin\SecondCategory\CreateRequest;
use App\Http\Requests\Admin\SecondCategory\IndexRequest;
use App\Http\Requests\Admin\SecondCategory\EditRequest;
use App\Http\Requests\Admin\SecondCategory\CsvImportRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use App\Models\FirstCategory;
use App\Models\SecondCategory;
use App\Consts\SecondCategoryConsts;
use App\Consts\FirstCategoryConsts;

use SplFileObject;
use DateTime;

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

        // 検索条件セッションを持っていて、GETパラメータがない場合
        if ($request->session()->has('second_category') && !$request->hasAny($request->forms)) {
            //　セッションから検索条件を取得する
            $input = $request->session()->pull('second_category');
        }
        $request->session()->put('second_category', $input);

        $firstCategoryModel = new FirstCategory();
        $firstCategories = $firstCategoryModel->getLists();

        $secondCategoryModel = new SecondCategory();
        $lists = $secondCategoryModel->getAdminLists($input);

        return view('admin.second_category.index', compact('firstCategories', 'lists', 'input'));
    }



    public function show(SecondCategory $secondCategory): View
    {
        $deleteFlg = true;
        if ($secondCategory->products()->exists()) {
            $deleteFlg = false;
        }

        return view('admin.second_category.show', compact('secondCategory', 'deleteFlg'));
    }


    public function edit(SecondCategory $secondCategory): View
    {
        $model = new FirstCategory();
        $firstCategories = $model->getLists();

        return view('admin.second_category.edit', compact('secondCategory', 'firstCategories'));
    }


    public function update(EditRequest $request, SecondCategory $secondCategory): RedirectResponse
    {
        $model = new SecondCategory();
        $secondCategory = $model->updateSecondCategory($request->validated(), $secondCategory);

        return redirect()->route('admin.second_category.show', $secondCategory)->with('msg_success', '中カテゴリを編集しました。');
    }


    public function destroy(SecondCategory $secondCategory): RedirectResponse
    {
        // 製品と紐づいている中カテゴリは削除できない
        if ($secondCategory->products()->exists()) {
            return redirect()->route('admin.second_category.show', $secondCategory)->with('msg_failure', 'この中カテゴリは削除できません。');
        } else {
            $secondCategory->delete();
        }

        return redirect()->route('admin.second_category.index', $secondCategory)->with('msg_success', '中カテゴリを削除しました。');
    }


    public function csv_upload(): View
    {
        return view('admin.second_category.csv_upload');
    }


    public function csv_import(CsvImportRequest $request): RedirectResponse
    {
        $input = $request->validated();
        $today = new DateTime();
        $fileName = $today->format('YmdHis') . '.csv';

        if($request->hasFile('csv_file')) {
            if($request->csv_file->getClientOriginalExtension() !== "csv") {
                return redirect()->route('admin.second_category.csv_upload')->with('msg_failure', 'CSVファイル以外のファイルがアップロードされました。')->withInput();
            }
            $request->csv_file->storeAs(SecondCategoryConsts::CSV_FILE_DIR, $fileName, 'public');
        } else {
            return redirect()->route('admin.second_category.csv_upload')->with('msg_failure', 'CSVファイルの取得に失敗しました。')->withInput();
        }

        $csvs = new SplFileObject(storage_path('app/public/'. SecondCategoryConsts::CSV_FILE_DIR . '/' . $fileName));
        $csvs->setFlags(SplFileObject::READ_CSV | SplFileObject::READ_AHEAD);

        $rules = [
            'first_category_name' => [
                'bail', 
                'required',
                'string',
                'max:' . FirstCategoryConsts::NAME_LENGTH_MAX
            ],
            'name' => [
                'bail', 
                'required',
                'string',
                'max:' . SecondCategoryConsts::NAME_LENGTH_MAX
            ],
        ];
        $attributes = [
            'first_category_name' => '大カテゴリ名',
            'name' => '中カテゴリ名',
        ];
        $errorMessages = [];
        $lines = [];

        // 大カテゴリ名存在チェック用
        $firstCategoryModel = new FirstCategory();
        $firstCategories = $firstCategoryModel::all();
        $firstCategoryNames = $firstCategories->pluck('name', 'id')->toArray();

        // 中カテゴリ名ユニークチェック用
        $secondCategoryModel = new SecondCategory();
        $secondCategories = $secondCategoryModel::all();
        $secondCategoryNames = [];
        foreach ($secondCategories as $secondCategory) {
            $secondCategoryNames[$secondCategory->first_category_id][] = $secondCategory->name;
        }

        // CSVファイル内の重複チェック用
        $inputNames = [];

        foreach ($csvs as $line => $csv) {

            // 文字コード変換
            if ($input['code'] == SecondCategoryConsts::CSV_CODE_SJIS) {
                mb_convert_variables('UTF-8','SJIS', $csv);
            } else {
                // UTF-8 BOM付きのファイルならBOMを消す
                $csv = preg_replace('/^\xEF\xBB\xBF/', '', $csv);
            }

            // ヘッダー行
            if ($line === 0) {
                if (array_values(SecondCategoryConsts::CSV_HEADER) !== $csv) {
                    $errorMessages['csv_file'][] = $line + 1 . '行目：ヘッダーの項目名が違っています。';
                }
                continue;
            }

            // 1行あたりの項目数が足りない場合
            $filteredArray = array_filter($csv, function ($value) {
                return $value !== NULL;
            });
            if (count($filteredArray) !== count(SecondCategoryConsts::CSV_HEADER)) {
                $errorMessages['csv_file'][] = $line + 1 . '行目：項目に過不足があります。';
                continue;
            }

            // バリデート用にCSVデータを整形する
            foreach (array_keys(SecondCategoryConsts::CSV_HEADER) as $key => $value) {
                $lines[$line + 1][$value] = $csv[$key];
            }

            $validator = Validator::make($lines[$line + 1], $rules, [], $attributes);

            // バリデーションエラーがあった場合
            if($validator->fails()) {
                // エラーメッセージを「xx行目：エラーメッセージ」の形に整える
                $errorMessages['csv_file'][] = $line + 1 . '行目：' . $validator->errors()->first();

            } elseif (!in_array($lines[$line + 1]['first_category_name'], $firstCategoryNames, true)) {
                // 大カテゴリが存在しない場合
                $errorMessages['csv_file'][] = $line + 1 . '行目：存在しない大カテゴリ名が入力されています。';

            } elseif (array_key_exists(array_search($lines[$line + 1]['first_category_name'], $firstCategoryNames, true), $secondCategoryNames)
            && in_array($lines[$line + 1]['name'], $secondCategoryNames[array_search($lines[$line + 1]['first_category_name'], $firstCategoryNames, true)], true)) {
                // 大カテゴリが存在する場合、それと紐づく中カテゴリも重複しているかチェックする
                $errorMessages['csv_file'][] = $line + 1 . '行目：同じ大カテゴリ内で中カテゴリ名が重複しています。';

            } elseif (array_key_exists(array_search($lines[$line + 1]['first_category_name'], $firstCategoryNames, true), $inputNames)
            && in_array($lines[$line + 1]['name'], $inputNames[array_search($lines[$line + 1]['first_category_name'], $firstCategoryNames, true)], true)) {
                // CSVファイル内に同じ大カテゴリが存在する場合、それと紐づく中カテゴリも重複しているかチェックする
                $errorMessages['csv_file'][] = $line + 1 . '行目：CSVファイル内の同じ大カテゴリ内で中カテゴリ名が重複しています。';
            } else {
                // 入力エラーがない場合
                $lines[$line + 1]['first_category_id'] = array_search($lines[$line + 1]['first_category_name'], $firstCategoryNames, true);
                $lines[$line + 1]['created_at'] = $today->format('Y-m-d H:i:s');
                $lines[$line + 1]['updated_at'] = $today->format('Y-m-d H:i:s');

                $inputNames[$lines[$line + 1]['first_category_id']][] = $lines[$line + 1]['name'];
            }

            // 不要な配列の要素を削除する
            unset($lines[$line + 1]['first_category_name']);
        }
        
        // CSVファイル内で入力エラーがあった場合
        if (count($errorMessages) > 0) {
            $csvs = null;
            Storage::disk('public')->delete(SecondCategoryConsts::CSV_FILE_DIR . '/' . $fileName);

            return redirect()->route('admin.second_category.csv_upload')->withErrors($errorMessages)->withInput();
        }

        DB::transaction(function () use ($lines) {
            DB::table('second_categories')->insert($lines);
        });

        $csvs = null;
        Storage::disk('public')->delete(SecondCategoryConsts::CSV_FILE_DIR . '/' . $fileName);

        return redirect()->route('admin.second_category.index')->with('msg_success', '中カテゴリを一括登録しました。');
    }
}
