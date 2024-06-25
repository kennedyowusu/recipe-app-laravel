<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RecipeRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'rating' => 'nullable|numeric|min:0|max:5',
            'cuisine' => 'nullable|string|max:255',
            'cook_time_minutes' => 'nullable|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'is_trending' => 'nullable|boolean',
            'is_latest' => 'nullable|boolean',
            'ingredients' => 'required|array',
            // 'ingredients.*' => 'exists:ingredients,id'
        ];
    }
}
