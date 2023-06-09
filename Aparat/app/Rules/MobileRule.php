<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use function PHPUnit\Framework\matches;

class MobileRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {

        $mobileRegex = '~^(0098|\+?98|0)9\d{9}$~';
        preg_match($mobileRegex,$value,$matche);
        if(empty($matche)){
            $fail('ورودی شماره تلفن شما درست نیست');
        }


    }

    public function message(){
        return "شماره موبایل وارد شده اشتباه میباشد";
    }


}
