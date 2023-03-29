<?php

if (!function_exists('to_valid_mobile_number')){


    /**
     * افزودن +98 به ابتدای شماره تلفن
     * @param string $mobile شماره تلفن
     * @return string
     */
    function to_valid_mobile_number(string $mobile){
        return  $mobile= '+98' . substr($mobile,-10,10);
    }

}

if (!function_exists('random_verification_code')){
/**
 * ایجاد کد فعالسازی رندوم
 *
 * @return int
 * @throws Exception
 */
function random_verification_code()
{
    return random_int(100000,999999);
}
    }


