<?php

namespace App\Http\Requests\Admin\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

use App\Models\Admin;

class UpdateRequest extends FormRequest
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
            'name' => ['bail', 'required', 'string', 'max:255'],
            'login_id' => [
                'bail',
                'required',
                'string',
                'max:255',
                Rule::unique(Admin::class)->ignore($this->user('admin')->id),
            ],
        ];
    }


    public function attributes()
    {
        return [
            'name' => '管理者名',
        ];
    }
}
