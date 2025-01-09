<?php

namespace App\Http\Requests\Admin\SecondCategory;

use Illuminate\Foundation\Http\FormRequest;

use App\Consts\SecondCategoryConsts;
use Illuminate\Support\Arr;

class IndexRequest extends FormRequest
{
    private $forms = [
        'first_category_id',
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
            'first_category_id' => 'bail|nullable|integer|exists:first_categories,id',
            'name' => 'bail|nullable|string|max:' . SecondCategoryConsts::NAME_LENGTH_MAX,
        ];
    }


    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key = null, $default = null);

        foreach ($this->forms as $form) {
            if (!Arr::exists($data, $form)) {
                $data[$form] = null;
            }
        }

        return $data;
    }


    public function attributes()
    {
        return [
            'name' => '中カテゴリ名',
        ];
    }
}
