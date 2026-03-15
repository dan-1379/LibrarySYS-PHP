<?php 
    class Member {
        private int $id;
        private string $firstName;
        private string $lastName;
        private string $dob;
        private string $phone;
        private string $email;
        private string $addressLine1;
        private string $addressLine2;
        private string $city;
        private string $county;
        private string $eircode;
        private string $registrationDate;
        private string $status;

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

        public function getId() : int {
            return $this->id;
        }

        public function getFirstName() : string {
            return $this->firstName;
        }

        public function getLastName() : string {
            return $this->lastName;
        }

        public function getDob() : string {
            return $this->dob;
        }

        public function getPhone() : string {
            return $this->phone;
        }

        public function getEmail() : string {
            return $this->email;
        }

        public function getAddressLine1() : string {
            return $this->addressLine1;
        }

        public function getAddressLine2() : string {
            return $this->addressLine2;
        }

        public function getCity() : string {
            return $this->city;
        }

        public function getCounty() : string {
            return $this->county;
        }
        
        public function getEircode() : string {
            return $this->eircode;
        }

        public function getRegistrationDate() : string {
            return $this->registrationDate;
        }

        public function getStatus() : string {
            return $this->status;
        }

        public function isActive() : bool {
            return $this->status === 'A';
        }
    }
?>