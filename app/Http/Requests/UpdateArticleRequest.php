<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateArticleRequest extends FormRequest
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
            'title' => 'sometimes|string|min:15|max:100',
            'body' => 'sometimes|string|min:350',
            'category' => 'sometimes|string|in:Technology,Business,Health,Sports,Entertainment,Environment',
            'image' => 'nullable|image|mimes:png,jpeg,jpg|max:5012'
        ];
    }
}
