<?php
    /**
     * Provides validation logic for authentication of user credentials prior to processing
     * by the authentication repository.
     * 
     * @author Dan
     * @version 1.0
     */
    class AuthenticationValidator {
        /**
         * Determines if the provided username is valid.
         * 
         * @param string $username The username to be validated.
         * @return string "valid" if the username passes all checks; 
         * otherwise, a string with the validation error.
         */
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

         /**
         * Determines if the provided password is valid.
         * 
         * @param string $password The password to be validated.
         * @return string "valid" if the password passes all checks; 
         * otherwise, a string with the validation error.
         */
        public static function isValidPassword($password) : string {
            if (empty($password)) {
                return "Password must be entered.";
            }

            $pattern = "/^[a-zA-Z0-9!@$]+$/";

            if (!preg_match($pattern, $password)) {
                return "Password must only contain letters, numbers, or the symbols !@$.";
            }

            return "valid";
        }
    }
?>