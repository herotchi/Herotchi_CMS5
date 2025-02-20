<?php

namespace App\Http\Requests\Admin\Tag;

use Illuminate\Foundation\Http\FormRequest;

use App\Consts\TagConsts;
use Illuminate\Support\Arr;

class IndexRequest extends FormRequest
{
    public $forms = [
        'name',
        'page'
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
            'name' => 'bail|nullable|string|max:' . TagConsts::NAME_LENGTH_MAX,
            'page' => 'bail|nullable|integer|numeric',
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
            'name' => 'タグ名',
        ];
    }
}
