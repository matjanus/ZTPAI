<?php

namespace App\Validator;

class PasswordValidator
{
    public static function isValid(string $password): bool
    {
        return preg_match('/^(?=.*[a-z])(?=.*[A-Z]).{8,}$/', $password) === 1;
    }

    public static function getPasswordInvalidMessage(string $password): string
    {
        return 'The password must contain min. 8 characters and uppercase and lowercase letter.';
    }
}