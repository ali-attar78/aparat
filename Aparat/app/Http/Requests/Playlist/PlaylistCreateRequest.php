<?php

namespace App\Http\Requests\Playlist;

use App\Rules\UniqueForUser;
use Illuminate\Foundation\Http\FormRequest;

class PlaylistCreateRequest extends FormRequest
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
            'title' => ['required','string','min:2','max:200',new UniqueForUser('playlist')]
        ];
    }
}
