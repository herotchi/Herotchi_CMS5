<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;
use App\Models\SecondCategory;

use App\Consts\ProductConsts;

class IndexRequest extends FormRequest
{
    private $_forms = [
        'first_category_id',
        'second_category_id',
        'tag_ids',
        'keyword',
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
            'second_category_id' => 'bail|nullable|integer|exists:second_categories,id',
            'tag_ids' => 'bail|nullable|array',
            'tag_ids.*' => 'integer|exists:tags,id',
            'keyword' => 'bail|nullable|string|max:' . ProductConsts::KEYWORD_LENGTH_MAX,
        ];
    }


    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key = null, $default = null);

        foreach ($this->_forms as $_form) {
            if (!Arr::exists($data, $_form)) {
                if ($_form === 'tag_ids') {
                    $data[$_form] = array();
                } else {
                    $data[$_form] = null;
                }
            }
        }

        return $data;
    }


    public function after(): array
    {
        return [
            function (Validator $validator) {
                $data = $validator->valid();

                // 製品の大カテゴリと中カテゴリが紐づいているかチェック
                if ($validator->errors()->has('first_category_id') === false && $validator->errors()->has('second_category_id') === false
                 && Arr::exists($data, 'first_category_id') && Arr::exists($data, 'second_category_id') 
                 && $data['first_category_id'] && $data['second_category_id']) {
                    $model = new SecondCategory();
                    $result = $model
                        ->where('first_category_id', $data['first_category_id'])
                        ->where('id', $data['second_category_id'])
                        ->exists();

                    if (!$result) {
                        $validator->errors()->add('second_category_id', '大カテゴリと紐づいていない中カテゴリが選択されました。');
                    }
                }
            }
        ];
    }
}
