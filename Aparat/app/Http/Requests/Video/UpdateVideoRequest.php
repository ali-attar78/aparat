<?php

namespace App\Http\Requests\Video;

use App\Models\Video;
use App\Rules\CategoryId;
use App\Rules\OwnPlaylistId;
use App\Rules\UploadedVideoBannerId;
use App\Rules\UploadedVideoId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update',$this->video);

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title'=>'required|string|max:255',
            'category'=>['required',new CategoryId(CategoryId::PUBLIC_CATEGORIES)],
            'info'=>'nullable|string',
            'tags'=>'nullable|array',
            'tags.*'=>'exists:tags,id',
            'channel_category'=>['nullable',new CategoryId(CategoryId::PRIVATE_CATEGORIES)],
            'banner'=>['nullable',new UploadedVideoBannerId()],
            'enable_comments' => 'required|boolean'
            ];
    }
}
