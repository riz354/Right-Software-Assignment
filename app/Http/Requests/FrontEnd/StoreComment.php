<?php

namespace App\Http\Requests\FrontEnd;

use Illuminate\Foundation\Http\FormRequest;

class StoreComment extends FormRequest
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
            'comment'=>"required|max:255|min:3"
        ];
    }
    public function messages(): array
    {
        return [
            'comment.required'=>"Please Enter Comment",
            'comment.max'=>"Comment Should be maximum 255 character",
            'comment.min'=>"Comment Should be minimum 3 character"
        ];
    }
}
