<?php

namespace App\Http\Requests\Admin\News;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator as AfterValidator;
use Illuminate\Validation\Rule;

use App\Consts\NewsConsts;

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
            // urlとoverviewの厳正なバリデートは後で行う
            'title' => 'bail|required|string|min:' . NewsConsts::TITLE_LENGTH_MIN . '|max:' . NewsConsts::TITLE_LENGTH_MAX,
            'link_flg' => ['bail', 'required', 'integer', Rule::in(array_keys(NewsConsts::LINK_FLG_LIST))],
            'url' => 'bail|nullable',
            'overview' => 'bail|nullable',
            'release_date' => 'bail|required|date_format:Y-m-d|after_or_equal:2023/01/01|before_or_equal:2037/12/31',
            'release_flg' => ['bail', 'required', 'integer', Rule::in(array_keys(NewsConsts::RELEASE_FLG_LIST))],
        ];
    }


    public function after(): array
    {
        return [
            function (Validator $validator) {
                $data = $validator->valid();

                // リンク設定の値によってURLと概要のどちらかのバリデーションが行われる
                if ($validator->errors()->has('link_flg') === false) {

                    // リンク設定ありの場合、URLのバリデーションを行う
                    if ($data['link_flg'] == NewsConsts::LINK_FLG_ON) {

                        $urlValidator = AfterValidator::make(
                            ['url' => $data['url']],
                            ['url' => [
                                    'bail',
                                    'required',
                                    'string',
                                    'url:https',
                                    'max:' . NewsConsts::URL_LENGTH_MAX
                            ]]
                        );
                
                        if ($urlValidator->fails()) {
                            $validator->errors()->add('url', 'リンク設定' . NewsConsts::LINK_FLG_LIST[NewsConsts::LINK_FLG_ON] . 'の場合、' . $urlValidator->errors()->first('url'));
                        }

                    // リンク設定なしの場合、概要のバリデーションを行う
                    } elseif ($data['link_flg'] == NewsConsts::LINK_FLG_OFF) {

                        $overviewValidator = AfterValidator::make(
                            ['overview' => $data['overview']],
                            ['overview' => [
                                    'bail',
                                    'required',
                                    'string',
                                    'max:' . NewsConsts::OVERVIEW_LENGTH_MAX
                            ]]
                        );
                
                        if ($overviewValidator->fails()) {
                            $validator->errors()->add('overview', 'リンク設定' . NewsConsts::LINK_FLG_LIST[NewsConsts::LINK_FLG_OFF] . 'の場合、' . $overviewValidator->errors()->first('overview'));
                        }

                    }
                }
            }
        ];
    }
}
