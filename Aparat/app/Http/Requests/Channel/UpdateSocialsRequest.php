<?php

namespace App\Http\Requests\Channel;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialsRequest extends FormRequest
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

            'cloob' => 'nullable|url',
            'lenzo'=>'nullable|url',
            'facebook'=>'nullable|url',
            'twitter'=> 'nullable|url',
            'telegram' => 'nullable|url'


        ];
    }
}
