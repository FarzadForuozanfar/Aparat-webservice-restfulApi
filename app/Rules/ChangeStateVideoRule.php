<?php

namespace App\Rules;

use App\Models\Video;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use JetBrains\PhpStorm\NoReturn;

class ChangeStateVideoRule implements ValidationRule
{
    private Video|null $video;

    /**
     * @param Video|null $video
     */
    public function __construct(Video $video = null)
    {
        $this->video = $video;
    }

    /**
     * Run the validation rule.
     *
     * @param string $attribute
     * @param mixed $value
     * @param Closure $fail
     */
    #[NoReturn] public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $permission = (!empty($this->video) and
            (
                ($this->video->state == Video::CONVERTED and in_array($value, [Video::ACCEPT, Video::BLOCKED]))
                or
                ($this->video->state == Video::ACCEPT and $value == Video::BLOCKED)
                or
                ($this->video->state == Video::BLOCKED and $value == Video::ACCEPT)
            )
        );
        if (!$permission)
        {
            $fail('Video state', 'The request is unacceptable');
        }
    }
}
