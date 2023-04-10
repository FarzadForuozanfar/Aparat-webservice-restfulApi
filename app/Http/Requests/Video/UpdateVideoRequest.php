<?php

namespace App\Http\Requests\Video;

use App\Rules\CategoryIdRule;
use App\Rules\OwnPlaylistRule;
use App\Rules\UploadedVideoBannerId;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->video);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'title'=> 'required|string|max:255',
            'category'=> ['required', new CategoryIdRule(CategoryIdRule::PUBLIC_CATEGORIES)],
            'info'=> 'nullable|string',
            'tags'=> 'nullable|array',
            'tags.*'=>'exists:tags,id',
            'channel_category'=> ['nullable', new CategoryIdRule(CategoryIdRule::PRIVATE_CATEGORIES)],
            'banner'=> ['nullable', new UploadedVideoBannerId() ],
            'publish_at'=> 'nullable|date_format:Y-m-d H:i:s|after:now',
            'enable_comments'=>'required|boolean'
        ];
    }
}
