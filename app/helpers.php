
<?php
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
