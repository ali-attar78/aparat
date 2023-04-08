<?php

namespace App\Rules;

use App\Models\Video;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CanChangeVideoState implements ValidationRule
{

    private Video $video;

    public function __construct(Video $video){

        $this->video = $video;
    }


    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        if (empty($this->video) ||
            (
            ($this->video->state == Video::STATE_CONVERTED && !in_array($value,[Video::STATE_ACCEPTED,Video::STATE_BLOCKED])) ||
            ($this->video->state == Video::STATE_ACCEPTED && $value != Video::STATE_BLOCKED) ||
            ($this->video->state == Video::STATE_BLOCKED && $value != Video::STATE_ACCEPTED)

            )
          )
        {
            $fail("state invalid");
        }
    }
}
