<?php

namespace App\Http\Requests\Comment;

use App\Models\Video;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CreateCommentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $parent_id = $this->get('parent_id', null);
        return Gate::allows('createComment', [Video::find($this->video_id), $parent_id]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'video_id' => 'required|exists:videos,id',
            'parent_id' => 'nullable|exists:comments,id',
            'body' => 'required|string|max:1000'
        ];
    }
}
