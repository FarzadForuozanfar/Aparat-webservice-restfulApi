
<?php
if (!function_exists('toValidMobileNumber'))
{
    /**
     * تبدیل شماره تلفن به پیشوندی +98
     *
     * @param string $mobile
     * @return string
     */
    function toValidMobileNumber(string $mobile)
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
    function createVerifyCode()
    {
        return rand(100000,900000);
    }
}

