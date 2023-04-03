<?php

namespace App\Http\Requests\Video;

use App\Rules\CategoryIdRule;
use App\Rules\OwnPlaylistRule;
use App\Rules\UploadedVideoBannerId;
use App\Rules\UploadedVideoId;
use Illuminate\Foundation\Http\FormRequest;

class RepublishVideoRequest extends FormRequest

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
     * @return array
     */
    public function rules(): array
    {
        return [

        ];
    }
}
