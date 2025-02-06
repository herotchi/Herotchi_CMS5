<?php

namespace App\Http\Requests\Contact;

use Illuminate\Foundation\Http\FormRequest;

use App\Consts\ContactConsts;

class ConfirmRequest extends FormRequest
{
    /**
     * バリデーション失敗時に、ユーザーをリダイレクトするルート
     *
     * @var string
     */
    protected $redirectRoute = 'contact.create';

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'body' => 'bail|required|string|max:' . ContactConsts::MAIL_BODY_LENGTH_MAX,
        ];
    }


    /**
     * バリーデーションのためにデータを準備
     */
    protected function prepareForValidation(): void
    {
        if ($this->session()->has('input')) {
            $this->merge($this->session()->get('input'));
        } else {
            $this->redirectRoute = 'top';
            session()->flash('msg_failure', 'セッション期限が切れました。');
        }
    }
}
