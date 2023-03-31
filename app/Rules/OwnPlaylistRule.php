<?php

namespace App\Rules;

use App\Models\PlayList;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class OwnPlaylistRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!PlayList::where(['user_id' => auth()->id() , 'id' => $value])->count())
            $fail('Invalid Playlist Id');
    }
}
