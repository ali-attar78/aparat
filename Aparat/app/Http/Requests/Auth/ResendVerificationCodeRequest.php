<?php

namespace App\Http\Requests\Auth;

use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class ResendVerificationCodeRequest extends FormRequest
{
    use GetRegisterFieldAndValueTrait;

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
            'mobile'=>['required_without:email',new MobileRule],
            'email'=>'required_without:mobile|email',
        ];
    }



}
