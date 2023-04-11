<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ChannelName implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!preg_match('~^[a-z-A-Z_][a-z-A-Z0-9\-_]{3,254}$~',$value)){

            $fail('Channel name must contain only this chars a-z,A-Z,0-9,_or -');
        }
    }
}
