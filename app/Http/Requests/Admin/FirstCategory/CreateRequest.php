<?php

namespace App\Http\Requests\Admin\FirstCategory;

use Illuminate\Foundation\Http\FormRequest;

use App\Consts\FirstCategoryConsts;

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
            'name' => 'bail|required|string|max:' . FirstCategoryConsts::NAME_LENGTH_MAX . '|unique:first_categories,name',
        ];
    }

    public function attributes()
    {
        return [
            'name' => '大カテゴリ名',
        ];
    }
}
