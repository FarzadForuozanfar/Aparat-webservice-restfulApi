<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class channelNameRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(! preg_match('/^[a-zA-Z0-9_-]{3,255}$/', $value))
        {
            $fail('Invalid ChannelName', 'name must be contains a-z, A-Z, 0-9, _ or - and 255 char size');
        }
    }
}
