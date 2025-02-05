<?php

namespace App\Http\Requests\Product;

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
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric',
            'description' => 'required|string',
            'images' => 'array',
            'images.*' => 'image|mimes:jpeg,png|max:2048', // Corrected
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a valid text.',
            'name.max' => 'The name must not exceed 255 characters.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists' => 'The selected category is invalid.',
            'price.required' => 'The price field is required.',
            'price.numeric' => 'The price must be a valid number.',
            'description.required' => 'The description field is required.',
            'description.string' => 'The description must be a valid text.',
            'images.required' => 'Please upload at least one image.',
            'images.array' => 'Images must be uploaded as an array.',
            'images.*.image' => 'Each file must be an image.',
            'images.*.mimes' => 'Only JPG, JPEG, and PNG formats are allowed.',
            'images.*.max' => 'Each image must not exceed 2MB in size.',
        ];
    }
   
}
