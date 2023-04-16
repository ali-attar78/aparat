<?php

namespace App\Http\Requests\Playlist;

use App\Rules\SortablePlaylistVideos;
use App\Rules\UniqueForUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class SortVideoInPlaylistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('sortVideos',$this->playlist);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [

            'videos'=>['required',new SortablePlaylistVideos($this->playlist)]

        ];
    }
}
