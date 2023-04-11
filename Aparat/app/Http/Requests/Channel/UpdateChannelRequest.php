<?php

namespace App\Http\Requests\Channel;

use App\Models\User;
use App\Rules\ChannelName;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChannelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->route()->hasParameter('id') && auth()->user()->type !=User::TYPE_ADMIN)
        {
            return false;
        }

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
            'name' => ['required', new ChannelName],
            'website' => 'nullable|url|max:255',
            'info' => 'nullable|string'
        ];
    }
}
