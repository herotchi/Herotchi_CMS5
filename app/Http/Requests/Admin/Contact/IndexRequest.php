<?php

namespace App\Http\Requests\Admin\Contact;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;

use App\Consts\ContactConsts;

class IndexRequest extends FormRequest
{
    public $forms = [
        'no',
        'body',
        'created_at_from',
        'created_at_to',
        'status',
        'page',
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
            'no' => 'bail|nullable|string|size:' . ContactConsts::NO_LENGTH,
            'body' => 'bail|nullable|string|max:' . ContactConsts::BODY_LIST_LENGTH_MAX,
            'created_at_from' => 'bail|nullable|date_format:Y-m-d|after_or_equal:2019/01/01|before_or_equal:2037/12/31',
            'created_at_to' => 'bail|nullable|date_format:Y-m-d|after_or_equal:2019/01/01|before_or_equal:2037/12/31|after_or_equal:created_at_from',
            'status' => 'bail|nullable|array',
            'status.*' => Rule::in(array_keys(ContactConsts::STATUS_LIST)),
            'page' => 'bail|nullable|integer|numeric',
        ];
    }


    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key = null, $default = null);

        foreach ($this->forms as $form) {
            if (!Arr::exists($data, $form)) {
                if ($form === 'status') {
                    $data[$form] = array();
                } else {
                    $data[$form] = null;
                }
            }
        }

        return $data;
    }
}
