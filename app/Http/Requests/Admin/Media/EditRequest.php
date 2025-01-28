<?php

namespace App\Http\Requests\Admin\Media;

use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;

use App\Consts\MediaConsts;

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
            'media_flg' => ['bail', 'required', 'integer', Rule::in(array_keys(MediaConsts::MEDIA_FLG_LIST))],
            // 画像ファイルは過去にアップロードされたファイルをそのまま使う可能性がある
            'image' => 'bail|nullable|file|image|mimes:jpg,png|max:' . MediaConsts::IMAGE_FILE_MAX,
            'alt' => 'bail|required|string|max:' . MediaConsts::ALT_LENGTH_MAX,
            'url' => 'bail|required|string|url:https|max:' . MediaConsts::URL_LENGTH_MAX,
            'release_flg' => ['bail', 'required', 'integer', Rule::in(array_keys(MediaConsts::RELEASE_FLG_LIST))],
        ];
    }


    public function attributes()
    {
        return [
            'image' => 'メディア画像'
        ];
    }
}
