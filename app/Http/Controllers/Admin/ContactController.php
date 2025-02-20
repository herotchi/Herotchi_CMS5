<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Contact\IndexRequest;
use App\Http\Requests\Admin\Contact\StatusUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Arr;

use App\Consts\ContactConsts;
use App\Models\Contact;

class ContactController extends Controller
{
    //
    public function index(IndexRequest $request): View|RedirectResponse
    {
        $input = $request->validated();

        // 検索条件セッションを持っていて、GETパラメータがない場合
        if ($request->session()->has('contact') && !$request->hasAny($request->forms)) {
            //　セッションから検索条件を取得する
            $input = $request->session()->pull('contact');
        }
        $request->session()->put('contact', $input);

        // CSV出力の場合、検索条件をセッションに保持してリダイレクト
        if ($request->input('csv_export') === 'csv_export') {
            //$request->session()->put('csv_export', $input);
            return redirect()->route('admin.contact.csv_export');
        }

        $model = new Contact();
        $lists = $model->getAdminLists($input);

        return view('admin.contact.index', compact('lists', 'input'));
    }


    public function show(Contact $contact): View
    {
        return view('admin.contact.show', compact('contact'));
    }


    public function status_update(StatusUpdateRequest $request, Contact $contact)
    {
        $model = new Contact();
        $contact = $model->updateContactStatus($request->validated(), $contact);

        return redirect()->route('admin.contact.show', $contact)->with('msg_success', 'ステータスを更新しました。');
    }


    public function csv_export(Request $request)
    {
        // レコードを取得
        $input = $request->session()->pull('contact');
        $model = new Contact();
        $query = $model->getAdminCsvExport($input);

        // ヘッダー情報
        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => 'attachment;'
        ];

        // ファイル名
        $fileName = 'お問い合わせ';
        if (Arr::exists($input, 'created_at_from') && $input['created_at_from'] && Arr::exists($input, 'created_at_to') && $input['created_at_to']) {
            $fileName = $fileName . $input['created_at_from'] . '～' . $input['created_at_to'] . '.csv';
        } elseif (Arr::exists($input, 'created_at_from') && $input['created_at_from']) {
            $fileName = $fileName . $input['created_at_from'] . '～' . '.csv';
        } elseif (Arr::exists($input, 'created_at_to') && $input['created_at_to']) {
            $fileName = $fileName . '～' . $input['created_at_to'] . '.csv';
        } else {
            $fileName = $fileName . '.csv';
        }

        $callback = function() use ($query)
        {
            // ストリームを作成してファイルに書き込めるようにする
            $stream = fopen('php://output', 'w');
            // CSVのヘッダ行の定義
            $head = [
                'お問い合わせNO',
                '投稿日',
                '氏名',
                'メールアドレス',
                'お問い合わせ内容',
                'ステータス',
            ];

            // UTF-8からSJISにフォーマットを変更してExcelの文字化け対策
            mb_convert_variables('SJIS', 'UTF-8', $head);
            fputcsv($stream, $head);

            // CSVファイルのデータレコードにお問い合わせ情報を挿入
            foreach ($query->cursor() as $contact) {
                $data = [
                    $contact->no,
                    $contact->created_at->format('Y年m月d日H時i分s秒'),
                    $contact->user->name,
                    $contact->user->email,
                    $contact->body,
                    ContactConsts::STATUS_LIST[$contact->status],
                ];

                mb_convert_variables('SJIS', 'UTF-8', $data);
                fputcsv($stream, $data);
            }

            fclose($stream);
        };

        return response()->streamDownload($callback, $fileName, $headers);
    }
}
