<?php

namespace App\Http\Requests\Admin\Media;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
use Illuminate\Support\Arr;

use App\Consts\MediaConsts;

class IndexRequest extends FormRequest
{
    public $forms = [
        'media_flg',
        'alt',
        'release_flg',
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
            'alt' => 'bail|nullable|string|max:' . MediaConsts::ALT_LENGTH_MAX,
            'media_flg' => 'bail|nullable|array',
            'media_flg.*' => Rule::in(array_keys(MediaConsts::MEDIA_FLG_LIST)),
            'release_flg' => 'bail|nullable|array',
            'release_flg.*' => Rule::in(array_keys(MediaConsts::RELEASE_FLG_LIST)),
            'page' => 'bail|nullable|integer|numeric',
        ];
    }


    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key = null, $default = null);

        foreach ($this->forms as $form) {
            if (!Arr::exists($data, $form)) {
                if ($form === 'media_flg' || $form === 'release_flg') {
                    $data[$form] = array();
                } else {
                    $data[$form] = null;
                }
            }
        }

        return $data;
    }
}
