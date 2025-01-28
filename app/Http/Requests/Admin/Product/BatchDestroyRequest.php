<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Validator;

class BatchDestroyRequest extends FormRequest
{
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
            'delete_ids' => 'bail|required|array',
            'delete_ids.*' => 'bail|required|integer|exists:products,id',
        ];
    }


    public function after(): array
    {
        return [
            function (Validator $validator) {
                if ($validator->errors()->has('delete_flg')) {
                    $this->session()->flash('msg_failure', $validator->errors()->first());
                } elseif ($validator->errors()->has('delete_flg.*')) {
                    $this->session()->flash('msg_failure', '不正な値が入力されました。');

                }
            }
        ];
    }
}
