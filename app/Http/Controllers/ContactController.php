<?php

namespace App\Http\Controllers;

use App\Http\Requests\Contact\CreateRequest;
use App\Http\Requests\Contact\ConfirmRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;
use App\Mail\Contact as ContactMail;

use App\Models\Contact;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    //
    public function create(): View
    {
        return view('contact.create');
    }


    public function confirm(CreateRequest $request): View
    {
        $input = $request->validated();
        $request->session()->put('input', $input);

        return view('contact.confirm', compact('input'));
    }


    public function store(ConfirmRequest $request): RedirectResponse
    {
        $input = $request->validated();
        
        if ($request->input('submit') === 'submit') {
        } else {
            return redirect()->route('contact.create')->withInput($input);
        }

        $no = '';
        DB::transaction(function () use ($input, &$no) {
            $model = new Contact();
            $no = $model->insertContact($input);
        });

        // メール送信処理
        Mail::to(Auth::user()->email)->send(new ContactMail($input, $no));

        $request->session()->forget('input');
        
        return redirect()->route('contact.complete')->with('no', $no);
    }


    public function complete(Request $request): View|RedirectResponse
    {
        if ($request->session()->has('no')) {
            $no = $request->session()->get('no');
        } else {
            return redirect()->route('top')->with('msg_failure', 'セッション期限が切れました。');
        }

        return view('contact.complete', compact('no'));
    }
}
