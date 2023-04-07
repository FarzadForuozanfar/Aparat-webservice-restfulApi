<?php

namespace App\Http\Requests\Channel;

use App\Models\User;
use App\Rules\channelNameRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateChannelRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        if ($this->route()->hasParameter('id') && auth()->user()->type != User::ADMIN_TYPE)
        {
            return false;
        }
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
            'name'=>['required','string:255|','unique:channels,name', new channelNameRule()],
            'website'=>'nullable|url|string',
            'info'=>'nullable|string'
        ];
    }
}
