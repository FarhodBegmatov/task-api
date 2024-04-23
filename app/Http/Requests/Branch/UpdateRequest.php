<?php

namespace App\Http\Requests\Branch;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'nullable|string',
            'files' => 'nullable|array',
            'files.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max per image
            'region' => 'nullable|string',
            'district' => 'nullable|string',
            'brand_id' => 'nullable|exists:brands,id'
        ];
    }
}
