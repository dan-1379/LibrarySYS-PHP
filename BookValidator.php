<?php
    /**
     * 
     * Provides validation logic for book-related data within the Library System.
     * This class is responsible for validating individual book fields such as
     * title, author, ISBN, publication date, and publisher, as well as enforcing
     * business rules such as ISBN uniqueness.
     */
    class BookValidator {
        /**
         * Determines if the provided title is valid.
         * @param string $title - The book title to be validated.
         * @return bool - True if the title is non-empty and does not exceed 30 characters.
         */
        public static function isValidTitle(string $title) : bool {
            return !empty(trim($title)) && strlen(trim($title)) <= 30;
        }

        /**
         * Determines if the provided author is valid.
         * @param string $author - The book author to be validated.
         * @return bool - True if the author is non-empty and does not exceed 25 characters.
         */
        public static function isValidAuthor(string $author) : bool {
            return !empty(trim($author)) && strlen(trim($author)) <= 25;
        }

        /**
         * Determines if the provided description is valid.
         * @param string $description - The book description to be validated.
         * @return bool - True if the description is non-empty and does not exceed 25 characters.
         */
        public static function isValidDescription(string $description) : bool {
            return !empty(trim($description)) && strlen(trim($description)) <= 30;
        }

        /**
         * Determines if the provided ISBN is valid.
         * @param string $isbn - The book ISBN to be validated.
         * @return bool - True if the ISBN is valid; False if any of the validation checks fail.
         */
        public static function isValidISBN(string $isbn) : string {
            if (empty($isbn)) {
                return "ISBN must not be null or contain only whitespaces";
            }

            if (strlen($isbn) != 17) {
                return "ISBN must be of length 17";
            }

            // https://www.w3schools.com/php/php_regex.asp
            $regex = '/^[0-9-]+$/';

            // https://www.w3schools.com/php/php_regex_functions.asp
            if (!preg_match($regex, $isbn)) {
                return "ISBN must only contain numbers and dashes";
            }

            $sections = explode("-", $isbn);

            if (count($sections) != 5) {
                return "ISBN must contain 5 sections";
            }

            $prefix = $sections[0];
            $registrationGroup = $sections[1];
            $registrant = $sections[2];
            $publication = $sections[3];
            $checkDigit = $sections[4];

            if ($prefix != "978" && $prefix != "979") {
                return "ISBN must begin with '978' or '979'";
            }

            if (strlen($registrationGroup) < 1 || strlen($registrationGroup) > 5) {
                return "Registration Group should be of length 1-5";
            }

             if (strlen($registrant) < 1 || strlen($registrant) > 7) {
                return "Registrant should be of length 1-7";
            }

            if (strlen($publication) < 1 || strlen($publication) > 7) {
                return "Publication should be of length 1-7";
            }

            if (!self::isValidCheckDigit($isbn)) {
                return "Check digit of ISBN is invalid";
            }

            return "Valid ISBN";
        }

        /**
         * Helper method to validate the check digit of an ISBN.
         * This digit must be equal to the last digit in the ISBN.
         * @param string $isbn - The ISBN of the book.
         * @return bool - True if the check digit is valid; otherwise, false.
         */
        private static function isValidCheckDigit(string $isbn) : bool {
            $digitsOnly = str_replace("-", "", $isbn);
            $sum = 0;
            $remainder = 0;

            for ($i = 0; $i < strlen($digitsOnly) - 1; $i++) {
                $currentDigit = (int) $digitsOnly[$i];

                if ($i % 2 == 0) {
                    $sum += $currentDigit;
                }
                else {
                    $sum += $currentDigit * 3;
                }
            }

            $remainder = (10 - ($sum % 10)) % 10;

            if (!($remainder == (int) $digitsOnly[strlen($digitsOnly) - 1])) {
                return false;
            }

            return true;
        }
    }
?>