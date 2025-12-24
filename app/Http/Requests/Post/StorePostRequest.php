<?php

namespace App\Http\Requests\Post;

use App\Helpers\ApiResponse;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StorePostRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'author_id' => ['required', 'exists:authors,id'],
            'published_at' => ['nullable', 'datetime'],
        ];
    }


    /**
     * @return string[]
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The category title field is required.',
            'content.required' => 'The category content field is required.',
            'category_id.required' => 'The category id field is required.',
            'author_id.required' => 'The author id field is required.',
        ];
    }

    /**
     * @param Validator $validator
     * @return mixed
     */
    protected function failedValidation(Validator $validator): mixed
    {
        throw new HttpResponseException(ApiResponse::validationError($validator));
    }
}
