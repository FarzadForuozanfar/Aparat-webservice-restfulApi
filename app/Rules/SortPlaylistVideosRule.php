<?php

namespace App\Rules;

use App\Models\PlayList;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class SortPlaylistVideosRule implements ValidationRule
{
    private PlayList $playList;

    /**
     * @param PlayList $playList
     */
    public function __construct(PlayList $playList)
    {
        $this->playList = $playList;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (is_array($value))
        {
            $videos = $this->playList->videos()->pluck('videos.id')->toArray();
            $value  = array_map('intval', $value);
            sort($value);
            sort($videos);
            if ($videos != $value)
                $fail('playlist', 'video doesnt exist in this playlist');
        }
        else
            $fail('playlist format', 'playlist must be array and not null');
    }
}
