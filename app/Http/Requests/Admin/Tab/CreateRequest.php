<?php

namespace App\Http\Requests\Admin\Tab;

use Illuminate\Foundation\Http\FormRequest;

use App\Consts\TabConsts;

class CreateRequest extends FormRequest
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
            'name' => 'bail|required|string|max:' . TabConsts::NAME_LENGTH_MAX . '|unique:tabs,name',
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'タブ名',
        ];
    }
}
