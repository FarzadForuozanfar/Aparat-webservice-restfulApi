<?php

namespace App\Http\Requests\Category;

use App\Rules\UploadedCategoryBannerId;
use Illuminate\Foundation\Http\FormRequest;

class CreateCategoryRequest extends FormRequest
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
            'title'=>'required|string|min:3|max:100,unique:categories,title',
            'icon'=>'required|string',
            'banner'=>['nullable', new UploadedCategoryBannerId()]
        ];
    }
}
