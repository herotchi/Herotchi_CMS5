<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

use App\Http\Requests\Admin\Profile\UpdateRequest;

class ProfileController extends Controller
{
    //
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', [
            'user' => $request->user('admin'),
        ]);
    }


    public function update(UpdateRequest $request): RedirectResponse
    {
        $request->user('admin')->fill($request->validated());
        $request->user('admin')->save();

        return Redirect::route('admin.profile.edit')->with('msg_success', 'プロフィールを更新しました。');
    }


    public function password_update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['bail', 'required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user('admin')->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('msg_success', 'パスワードを更新しました。');
    }
}
