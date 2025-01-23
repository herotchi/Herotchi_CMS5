<?php

namespace App\Http\Requests\Admin\Tag;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
use App\Models\Tag;


use App\Consts\TagConsts;

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
                'max:' . TagConsts::NAME_LENGTH_MAX,
                Rule::unique(Tag::class)->ignore($this->route('tag')),
            ],
        ];
    }


    public function attributes()
    {
        return [
            'name' => 'タグ名',
        ];
    }
}
