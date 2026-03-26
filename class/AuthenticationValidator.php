<?php 
    class AuthenticationValidator {
        public static function isValidUsername($username) : string {
            if (empty($username)) {
                return "Username must be entered.";
            }

            $pattern = '/^[a-zA-Z]+$/';

            if (!preg_match($pattern, $username)) {
                return "Username must only be uppercase or lowercase letters.";
            }

            return "valid";
        }

        public static function isValidPassword($password) : string {
            if (empty($password)) {
                return "Password must be entered.";
            }

            $pattern = "/^[a-zA-Z0-9!@$]+$/";

            if (!preg_match($pattern, $password)) {
                return "Password must only be uppercase or lowercase letters.";
            }

            return "valid";
        }
    }
?>