<?php


namespace App\Support\Enum;

enum OTPTypes: string
{
    case LOGIN    = 'login';
    case USER_RESET_PASS    = 'user_reset_password';
    case COMPANY_RESET_PASS    = 'company_reset_password';
    case ADMIN_RESET_PASS    = 'admin_reset_password';
    case USER_VERIFY_PHONE  = 'user_verify_phone';
    case USER_VERIFY_EMAIL  = 'user_verify_email';
    case EDUCATOR_VERIFY_EMAIL  = 'educator_verify_email';
    case EDUCATOR_RESET_PASS    = 'educator_reset_password';
    case MERCHANT_VERIFY_EMAIL  = 'merchant_verify_email';
}
