<?php

namespace App\Http\Requests\Admin\FirstCategory;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
use App\Models\FirstCategory;


use App\Consts\FirstCategoryConsts;

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
                'max:' . FirstCategoryConsts::NAME_LENGTH_MAX,
                Rule::unique(FirstCategory::class)->ignore($this->route('first_category')),
            ],
        ];
    }


    public function attributes()
    {
        return [
            'name' => '大カテゴリ名',
        ];
    }
}
