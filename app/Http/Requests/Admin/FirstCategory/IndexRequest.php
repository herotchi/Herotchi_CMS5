<?php

namespace App\Http\Requests\Admin\FirstCategory;

use Illuminate\Foundation\Http\FormRequest;

use App\Consts\FirstCategoryConsts;
use Illuminate\Support\Arr;

class IndexRequest extends FormRequest
{
    private $_forms = [
        'name',
    ];

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
            'name' => 'bail|nullable|string|max:' . FirstCategoryConsts::NAME_LENGTH_MAX,
        ];
    }


    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key = null, $default = null);

        foreach ($this->_forms as $_form) {
            if (!Arr::exists($data, $_form)) {
                $data[$_form] = null;
            }
        }

        return $data;
    }


    public function attributes()
    {
        return [
            'name' => '大カテゴリ名',
        ];
    }
}

