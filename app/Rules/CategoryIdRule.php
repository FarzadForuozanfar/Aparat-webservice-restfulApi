<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CategoryIdRule implements ValidationRule
{
    const PUBLIC_CATEGORIES  = 'public';
    const PRIVATE_CATEGORIES = 'private';
    const ALL_CATEGORIES     = 'all';
    private $categoryType;

    public function __construct($categoryType = self::ALL_CATEGORIES)
    {
        $this->categoryType = $categoryType;
    }
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $message = 'invalid category id';
        if ($this->categoryType == self::PUBLIC_CATEGORIES)
        {
            if (!Category::where('id', $value)->whereNull('user_id')->count())
                $fail($message . '1');
        }

        elseif ($this->categoryType == self::PRIVATE_CATEGORIES)
        {
            if (!Category::where('id', $value)->where('user_id', auth()->id())->count())
                $fail($message . '2');
        }

        else
        {
            if (!Category::where('id', $value)->count())
                $fail($message . '3');
        }
    }
}
