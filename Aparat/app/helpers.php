<?php

use Hashids\Hashids;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

if (!function_exists('uniqueId')) {
    function uniqueId(int $value)
    {
       $hash = new Hashids(env('APP_KEY'),10);
       return $hash->encode($value);
    }
}

if (!function_exists('clear_storage')) {
    function clear_storage(string $storageName)
    {
        try {

            Storage::disk($storageName)->delete(Storage::disk($storageName)->allFiles());
            foreach (Storage::disk($storageName)->allDirectories() as $dir) {
                Storage::disk($storageName)->deleteDirectory($dir);
            }

            return true;
        }
        catch (Exception $exception){
            Log::error($exception);
            return false;
        }

    }
}

if (!function_exists('client_ip')) {
    function client_ip($withDate = false)
    {
        $ip = $_SERVER['REMOTE_ADDR'] . '-' . md5($_SERVER['HTTP_USER_AGENT']);

        if ($withDate){
            $ip .='-' . now()->toDateString();
        }

        return $ip;

    }
}
