<?php

namespace App\Http\Requests\Admin\Product;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Validator;
use Illuminate\Validation\Rule;
use App\Models\SecondCategory;

use App\Consts\ProductConsts;

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
            'first_category_id' => 'bail|required|integer|exists:first_categories,id',
            'second_category_id' => 'bail|required|integer|exists:second_categories,id',
            'tag_ids' => 'bail|required|array',
            'tag_ids.*' => 'integer|exists:tags,id',
            'name' => 'bail|required|string|max:' . ProductConsts::NAME_LENGTH_MAX,
            'image' => 'bail|required|file|image|mimes:jpg,png|max:' . ProductConsts::IMAGE_FILE_MAX,
            'detail' => 'bail|required|string|max:' . ProductConsts::DETAIL_LENGTH_MAX,
            'release_flg' => ['bail', 'required', 'integer', Rule::in(array_keys(ProductConsts::RELEASE_FLG_LIST))],
        ];
    }


    public function attributes()
    {
        return [
            'name' => '製品名',
            'image' => '製品画像'
        ];
    }


    public function after(): array
    {
        return [
            function (Validator $validator) {
                $data = $validator->valid();

                // 製品の大カテゴリと中カテゴリが紐づいているかチェック
                if ($validator->errors()->has('first_category_id') === false && $validator->errors()->has('second_category_id') === false) {
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
