<?php

namespace App\Http\Requests\Playlist;

use App\Rules\SortPlaylistVideosRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class SortVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('sortVideos', $this->playlist);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'videos' => ['required', new SortPlaylistVideosRule($this->playlist)]
        ];
    }
}
