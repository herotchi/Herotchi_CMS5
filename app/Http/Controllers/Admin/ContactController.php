<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\Contact\IndexRequest;
use Illuminate\View\View;

use App\Models\Contact;

class ContactController extends Controller
{
    //
    public function index(IndexRequest $request): View
    {
        $input = $request->validated();

        // CSV出力の場合、検索条件をセッションに保持してリダイレクト
        /*if ($request->input('csv_export') === 'csv_export') {
            $request->session()->put('csv_export', $input);
            return redirect()->route('admin.contact.csv_export');
        }*/

        $model = new Contact();
        $lists = $model->getAdminLists($input);

        return view('admin.contact.index', compact('lists', 'input'));
    }


    public function show(Contact $contact): View
    {
        return view('admin.contact.show', compact('contact'));
    }
}
