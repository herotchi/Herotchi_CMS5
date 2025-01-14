<?php

namespace App\Http\Requests\Admin\Tab;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
use App\Models\Tab;


use App\Consts\TabConsts;

class EditRequest extends FormRequest
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
            'name' => [
                'bail',
                'required',
                'string',
                'max:' . TabConsts::NAME_LENGTH_MAX,
                Rule::unique(Tab::class)->ignore($this->route('tab')),
            ],
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'タブ名',
        ];
    }
}
