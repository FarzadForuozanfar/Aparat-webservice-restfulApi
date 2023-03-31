
<?php

use Hashids\Hashids;

if (!function_exists('toValidMobileNumber'))
{
    /**
     * تبدیل شماره تلفن به پیشوندی +98
     *
     * @param string $mobile
     * @return string
     */
    function toValidMobileNumber(string $mobile): string
    {
        return '+98' . substr($mobile, -10, 10);

    }
}

if (!function_exists('createVerifyCode'))
{
    /**
     * generate random code for verification
     *
     * @return int
     */
    function createVerifyCode(): int
    {
        return rand(100000,900000);
    }
}
if (!function_exists('uniqueId')) {
    function uniqueId(int $value): string
    {
        $hash = new Hashids(env('APP_KEY'), 10);
        return $hash->encode($value);
    }
}

