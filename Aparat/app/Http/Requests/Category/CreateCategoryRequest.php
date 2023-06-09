<?php

namespace App\Http\Requests\Category;

use App\Rules\UniqueForUser;
use App\Rules\UploadedCategoryBannerId;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required','string','min:2','max:100',new UniqueForUser('categories')],
            'icon' => 'nullable|string',
            'banner_id' => ['nullable',new UploadedCategoryBannerId()],
        ];
    }
}
