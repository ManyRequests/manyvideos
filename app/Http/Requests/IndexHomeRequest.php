<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndexHomeRequest extends FormRequest
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
            'perpage' => ['nullable', 'integer', 'min:1', 'max:20'],
            'page' => ['nullable', 'integer', 'min:1', 'max:100'],
            'search' => ['nullable', 'string', 'max:255'],
            'size_min' => ['nullable', 'integer', 'min:1', 'max:500'],
            'size_max' => ['nullable', 'integer', 'min:1', 'max:500'],
            'duration_min' => ['nullable', 'integer', 'min:1', 'max:10'],
            'duration_max' => ['nullable', 'integer', 'min:1', 'max:10'],
        ];
    }
}
