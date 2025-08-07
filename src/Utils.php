<?php
namespace App;

class Utils {
    public static function generatePassword(int $length = 16): string {
        $length = max(8, $length); // Mindestlänge 8
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_=+[]{}?';
        $password = '';
        $max = strlen($chars) - 1;
        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $max)];
        }
        return $password;
    }

    public static function getPasswordLength(): int {
        return (int) ($_ENV['PASSWORD_LENGTH'] ?? 16);
    }
}

