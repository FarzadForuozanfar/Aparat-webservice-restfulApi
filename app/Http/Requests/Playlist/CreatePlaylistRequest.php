<?php

namespace App\Http\Requests\Playlist;

use App\Rules\Unique4User;
use Illuminate\Foundation\Http\FormRequest;

class CreatePlaylistRequest extends FormRequest
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
            'title' => ['required','string','min:3','max:200', new Unique4User('playlist')]
        ];
    }
}
