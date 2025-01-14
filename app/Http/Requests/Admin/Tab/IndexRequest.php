<?php

namespace App\Http\Requests\Admin\Tab;

use Illuminate\Foundation\Http\FormRequest;

use App\Consts\TabConsts;
use Illuminate\Support\Arr;

class IndexRequest extends FormRequest
{
    private $forms = [
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
            'name' => 'bail|nullable|string|max:' . TabConsts::NAME_LENGTH_MAX,
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
            'name' => 'タブ名',
        ];
    }
}
