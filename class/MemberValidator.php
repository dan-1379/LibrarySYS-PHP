<?php 
    /*******************************************************************************
     * Title: Input Validation Methods
     * Author: Daniel Courtney
     * Site: N/A
     * Date: 09/03/26
     * Code Version: N/A
     * Availability: https://github.com/dan-1379/LibrarySYS/blob/master/LibrarySYS/MemberValidator.cs
     * Accessed: 31/03/26
     * Modified: Code taken from MemberValidator class in SWE project for the purposes of validating
     *           member entry fields. Code taken and modified for PHP usage.
     *******************************************************************************/


    /*
    * 
    * Provides validation logic for member-related data within the Library System.
    * This class is responsible for validating individual member fields such as 
    *  first name, last name, date of birth, phone number, email, and address,
    *  as well as enforcing business rules such as age restrictions and email format.
    */
    class MemberValidator {
        /** 
        * Determines if the provided member ID is valid.
        * @param string $id - The ID of the member
        * @return bool - True if the ID is numberic and greater than 0
        */
        public static function isValidID(string $id) : bool {
            if (empty(trim($id))) {
                return false;
            }

            if (!is_numeric($id)) {
                return false;
            }

            return (int) $id > 0;
        }

        /**
        * Determines if the provided name section is valid.
        * @param string $name - The name of the member to be validated. This method will be used for both first and last name.
        * @return bool - True if the name is non-empty and does not exceed 30 characters; otherwise, false.
        */
        public static function isValidName(string $name) : bool {
            return !empty(trim($name)) && strlen(trim($name)) <= 30 && preg_match('/^[a-zA-Z0-9\s\']+$/', $name);
        }

        /**
        * Determines if the provided DOB is valid.
        * @param string $dob - The DOB of the member to be validated.
        * @return bool - True if the DOB is not a future date and the member is not older than 120 years; otherwise, false.
        */
        public static function isValidDOB(string $dob) : bool {
            $date = DateTime::createFromFormat('Y-m-d', $dob);

            if (!$date) {
                return false;
            }

            $today = new DateTime();
            $minimumDOB = (new DateTime())->modify('-120 years');

            return $date <= $today && $date > $minimumDOB;
        }

        /**
        * Determines if the provided phone number is valid.
        * @param string $phone - The phone number of the member to be validated.
        * @return bool - True if the phone number is either 10 or 13 digits long, starts with "08" or "353", 
        * and contains only numeric characters; otherwise, false.
        */
        public static function isValidPhone(string $phone) : string {
            $nonDigitCount = 0;

            if (empty(trim($phone))) {
                return "A phone number must be entered";
            }

            if (strlen($phone) != 10 && strlen($phone) != 12) {
                return "The phone number must have 10 or 13 characters";
            }

            foreach(str_split($phone) as $digit) {
                if (!is_numeric($digit)) {
                    $nonDigitCount++;
                }
            }

            if ($nonDigitCount > 0){
                return "The phone number must contain only numeric characters";
            }

            if (substr($phone, 0, 2) !== '08' && substr($phone, 0, 3) !== '353') {
                return "The phone number must begin with '08' or '353'";
            } 

            return "valid";
        }

        /**
        * Determines if the provided email is valid.
        * @param string $email - The email of the member to be validated.
        * @return bool - True if the email is between 10 and 50 characters long, contains an "@" symbol, ends with a valid domain, 
        * and has valid recipient and domain sections; otherwise, false
        */
        public static function IsValidEmail(string $email) : string {
            if (strlen($email) < 10 || strlen($email) > 40) {
                return "Email length must be between 10 and 40.";
            }

            if (!str_contains($email, "@")) {
                return "Email must contain an @ symbol.";
            }

            if (!str_ends_with($email, ".com") &&
                !str_ends_with($email, ".org") &&
                !str_ends_with($email, ".ie") &&
                !str_ends_with($email, ".net")) {
                return "Email must end with a valid domain (.com, .org, .ie, .net).";
            }

            $emailSections = explode("@", $email);
            $recipient = $emailSections[0];

            $domainSections = explode(".", $emailSections[1]);
            $domain = $domainSections[0];

            if (strlen($recipient) < 1 || strlen($recipient) > 30)
            {
                return "Recipient section of the email must be between 1 and 30 characters.";
            }

            if (!self::isValidEmailSection($recipient))
            {
                return "Recipient section must only contain uppercase and lowercase letters, numbers, decimal points, dashes and underscores.";
            }


            if (strlen($domain) < 2 || strlen($domain) > 15)
            {
                return "Domain section of the email must be between 2 and 15 characters.";
            }

            if (!self::isValidEmailSection($domain))
            {
                return "Domain section must only contain uppercase and lowercase letters, numbers, decimal points, dashes and underscores.";
            }

            return "valid";
        }

        /**
        * Helper method for validating the recipient and domain sections of an email address.
        * @param string $section - The section of the email to be validated. This is either the recipient or domain.
        * @return bool - True if the section contains only uppercase and lowercase letters, numbers, decimal points, dashes, 
        * and underscores;
        */
        private static function isValidEmailSection(string $section) : bool {
            $validCharsCount = 0;

            foreach(str_split($section) as $char) {
                if (preg_match('/[a-zA-Z0-9.\-_]/', $char)) {
                    $validCharsCount++;
                }
            }

            return strlen($section) === $validCharsCount;
        }

        /**
        * Determines if the provided address line is valid.
        * @param string $addressLine - The line of the address to be validated. This can be either the first line or the optional
        * second line of the address.
        * @return bool - True if the address line is non-empty and between 5 and 30 characters long; otherwise, false.
        */
        public static function isValidAddressLine(string $addressLine) : bool {
            return !empty(trim($addressLine)) && strlen(trim($addressLine)) <= 30 && preg_match('/^[a-zA-Z0-9\s\']+$/', $addressLine); 
        }

        /**
        * Determines if the provided city is valid.
        * @param string $city - The city of the member to be validated.
        * @return bool - True if the city is non-empty and between 1 and 30 characters long; otherwise, false.
        */
        public static function isValidCity(string $city) : bool {
            return !empty(trim($city)) && strlen(trim($city)) >= 1 && strlen(trim($city)) <= 30 && preg_match('/^[a-zA-Z0-9\s\']+$/', $city);  
        }
        
        /**
        * Determines if the provided county is valid.
        * @param string $county - The county of the member to be validated.
        * @return bool - True if the county is non-empty and between 4 and 30 characters long; otherwise, false.
        */
        public static function isValidCounty(string $county) : bool {
            return !empty(trim($county)) && strlen(trim($county)) >= 4 && strlen(trim($county)) <= 10; 
        }

        /**
        * Determines if the provided eircode is valid.
        * @param string $eircode - The eircode of the member to be validated.
        * @return bool - True if the eircode is 7 characters long, starts with a letter followed by two digits in the 
        * routing key, and contains only letters and numbers in the unique identifier;
        */
        public static function isValidEircode(string $eircode) : string {
            if (empty($eircode)) {
                return "An eircode must be entered";
            }

            if (strlen($eircode) != 7) {
                return "Eircode must be 7 characters long";
            }

            $routingKey = substr($eircode, 0, 3);
            $uniqueIdentifier = substr($eircode, 3);
            $nonAlphanumericCount = 0;

            if (!preg_match('/[a-zA-Z]/', $routingKey[0])) {
                return "The first character of the routing key must be a letter";
            }

            if (!is_numeric($routingKey[1]) || !is_numeric($routingKey[2])) {
                return "The second and third characters of the routing key must be numbers";
            }

            foreach(str_split($uniqueIdentifier) as $char) {
                if (!preg_match('/[a-zA-Z0-9]/', $char)) {
                    $nonAlphanumericCount++;
                }
            }

            if ($nonAlphanumericCount > 0) {
                return "The unique identifier must contain only letters and numbers";
            }

            return "valid";
        }

        /**
        * Determines if the provided registration date is valid.
        * @param string $registrationDate - The registration date of the member to be validated.
        * @return bool - True if the registration date is the current date or a date before the current date.
        */
        public static function isValidRegistrationDate(string $registrationDate) : bool {
            $date = DateTime::createFromFormat('Y-m-d', $registrationDate);

            if (!$date) {
                return false;
            }

            $today = new DateTime();

            return $date <= $today;
        }

        /**
         * Determines if the provided status is valid.
         * @param string $status - The status of the book.
         * @return bool - True if the status is:
         *      A (Active)
         *      I (Inactive)
         * otherwise false.
         */
        public static function isValidStatus(string $status) : bool {
            $validStatus = ['A', 'I'];
            return in_array($status, $validStatus);
        }
    } 
?>