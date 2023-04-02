<?php

namespace App\Http\Requests\Video;

use App\Models\Video;
use App\Rules\ChangeStateVideoRule;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class ChangeStateRequest extends FormRequest

{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('change-state', $this->video);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'state'=> ['required', new ChangeStateVideoRule($this->video)]
        ];
    }
}
