<?php

namespace App\Http\Requests\Admin\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Consts\TagConsts;

class CsvImportRequest extends FormRequest
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
            'code' => ['bail', 'required', 'integer', Rule::in(array_keys(TagConsts::CSV_CODE_LIST))],
            'csv_file' => 'bail|required|file|mimetypes:text/plain,text/csv|mimes:csv,txt|max:' . TagConsts::CSV_FILE_MAX,
        ];
    }
}
