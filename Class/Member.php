<?php 
    /**
     * Represents a member in the library system.
     * 
     * Stores all details related to a loan member including name, address, dob, phone number and status.
     * 
     * @author Dan
     * @version 1.0
     */
    class Member {
        /**
         * The member ID of the member.
         * @var int
         */
        private int $id;

        /**
         * The first name of the member.
         * @var string
         */
        private string $firstName;

        /**
         * The last name of the member.
         * @var string
         */
        private string $lastName;

        /**
         * The DOB of the member.
         * @var string
         */
        private string $dob;

        /**
         * The phone number of the member.
         * @var string
         */
        private string $phone;

        /**
         * The email of the member.
         * @var string
         */
        private string $email;

        /**
         * The address line 1 of the member.
         * @var string
         */
        private string $addressLine1;

        /**
         * The address line 2 of the member.
         * @var string
         */
        private string $addressLine2;

        /**
         * The town/city of the member.
         * @var string
         */
        private string $city;

        /**
         * The county of the member.
         * @var string
         */
        private string $county;

        /**
         * The eircode of the member.
         * @var string
         */
        private string $eircode;

        /**
         * The registration date of the member.
         * @var string
         */
        private string $registrationDate;

        /**
         * The status of the member.
         * @var string
         */
        private string $status;

        /**
        * Creates a new Member instance.
        * @param string $firstName The first name of the member.
        * @param string $lastName The last name of the member.
        * @param string $dob The date of birth of the member.
        * @param string $phone The phone number of the member
        * @param string $email The email of the member.
        * @param string $addressLine1 The address line 1 of the member.
        * @param string $addressLine2 The address line 2 of the member.
        * @param string $city The town/city of the member.
        * @param string $county The county of the member.
        * @param string $eircode The eircode of the member.
        * @param string $registrationDate The registration date of the member.
        * @param string $status The status of the member.
        * @param int $id The identifier of the member.
        */
        public function __construct(string $firstName, string $lastName, string $dob, string $phone, string $email, string $addressLine1,
                                    string $addressLine2, string $city, string $county, string $eircode, string $registrationDate,
                                    string $status, int $id = 0) {
            $this->firstName = $firstName;
            $this->lastName = $lastName;
            $this->dob = $dob;
            $this->phone = $phone;
            $this->email = $email;
            $this->addressLine1 = $addressLine1;
            $this->addressLine2 = $addressLine2;
            $this->city = $city;
            $this->county = $county;
            $this->eircode = $eircode;
            $this->registrationDate = $registrationDate;
            $this->status = $status;
            $this->id = $id;
        }

        /**
         * Returns the member ID of the member.
         * @return int The member ID.
         */
        public function getId() : int {
            return $this->id;
        }

        /**
         * Returns the first name of the member.
         * @return string The members first name.
         */
        public function getFirstName() : string {
            return $this->firstName;
        }

        /**
         * Returns the last name of the member.
         * @return string The members last name.
         */
        public function getLastName() : string {
            return $this->lastName;
        }

        /**
         * Returns the date of birth of the member.
         * @return string The members date of birth.
         */
        public function getDob() : string {
            return $this->dob;
        }

        /**
         * Returns the phone number of the member.
         * @return string The members phone number.
         */
        public function getPhone() : string {
            return $this->phone;
        }

        /**
         * Returns the email of the member.
         * @return string The members email address.
         */
        public function getEmail() : string {
            return $this->email;
        }

        /**
         * Returns the address line 1 of the member.
         * @return string The members first line of the address.
         */
        public function getAddressLine1() : string {
            return $this->addressLine1;
        }

        /**
         * Returns the address line 2 of the member.
         * @return string The members second line of the address.
         */
        public function getAddressLine2() : string {
            return $this->addressLine2;
        }

        /**
         * Returns the city of the member.
         * @return string The members town/city.
         */
        public function getCity() : string {
            return $this->city;
        }

        /**
         * Returns the county of the member.
         * @return string The members county.
         */
        public function getCounty() : string {
            return $this->county;
        }
        
        /**
         * Returns the eircode of the member.
         * @return string The members eircode.
         */
        public function getEircode() : string {
            return $this->eircode;
        }

        /**
         * Returns the registration date of the member.
         * @return string The members registration date.
         */
        public function getRegistrationDate() : string {
            return $this->registrationDate;
        }

        /**
         * Returns the status of the member.
         * @return string The members status.
         */
        public function getStatus() : string {
            return $this->status;
        }

        /**
         * Checks if a member is active.
         * @return bool True if the member is active. Otherwise false.
         */
        public function isActive() : bool {
            return $this->status === 'A';
        }
    }
?>