<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVideoRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'file' => [
                'required',
                'file',
                'mimetypes:' . implode("," , config('videos.mime-types')),
                'max:' . config('videos.max-upload-size') / 1024, // in KB
            ],
            'tags' => ['nullable', 'array', 'max:5', 'distinct'],
        ];
    }
}
