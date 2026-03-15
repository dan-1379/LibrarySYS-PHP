<?php 
    /** 
     * Handles database interaction for Members
    */
    class MemberRepository {
        private PDO $pdo;

        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
        }

        function getAllMembers() : array {
            try {
                $sql = 'SELECT * FROM Members';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();

                $rows = $stmt->fetchAll();
                $members = [];

                foreach($rows as $row) {
                    $members[] = new Member (
                        $row['FirstName'],
                        $row['LastName'],
                        $row['DOB'],
                        $row['Phone'],
                        $row['Email'],
                        $row['AddressLine1'],
                        $row['AddressLine2'],
                        $row['City'],
                        $row['County'],
                        $row['Eircode'],
                        $row['RegistrationDate'],
                        $row['Status'],
                        $row['MemberID']
                    );
                }

                return $members;
            } catch (PDOException $e) {  
                return [];
            } 
        }

        function updateMember(Member $member) : void {
            try {
                $sql = "UPDATE Members SET FirstName = :cfirstName, LastName = :clastName, DOB = :cDOB, Phone = :cPhone,
                                    Email = :cEmail, AddressLine1 = :cAddressLine1, AddressLine2 = :cAddressLine2, City = :cCity,
                                    County = :cCounty, Eircode = :cEircode, RegistrationDate = :cRegistrationDate,Status = :cStatus
                                    WHERE MemberID = :cMemberID";

                $stmt = $this->pdo->prepare($sql);

                $stmt->bindValue(":cMemberID", $member->getId());
                $stmt->bindValue(':cfirstName', $member->getFirstName());
                $stmt->bindValue(':clastName', $member->getLastName());
                $stmt->bindValue(':cDOB', $member->getDob());
                $stmt->bindValue(':cPhone', $member->getPhone());
                $stmt->bindValue(':cEmail', $member->getEmail());
                $stmt->bindValue(':cAddressLine1', $member->getAddressLine1());
                $stmt->bindValue(':cAddressLine2', $member->getAddressLine2());
                $stmt->bindValue(':cCity', $member->getCity());
                $stmt->bindValue(':cCounty', ucfirst($member->getCounty()));
                $stmt->bindValue(':cEircode', $member->getEircode());
                $stmt->bindValue(':cRegistrationDate', $member->getRegistrationDate());
                $stmt->bindValue(':cStatus', $member->getStatus());

                $stmt->execute();
            } catch (PDOException $e) {
                throw new Exception("Error updating member: " . $e->getMessage());
            }
        }

        function addMember(Member $member) : void {
            try {
                $sql = "INSERT INTO Members(FirstName, LastName, DOB, Phone, Email, AddressLine1, AddressLine2, City, County, 
                                            Eircode, RegistrationDate, Status)
                                    VALUES(:cfirstName, :clastName, :cDOB, :cPhone, :cEmail, :cAddressLine1, :cAddressLine2,
                                        :cCity, :cCounty, :cEircode, :cRegistrationDate, :cStatus)";

                $stmt = $this->pdo->prepare($sql);

                $stmt->bindValue(':cfirstName', $member->getFirstName());
                $stmt->bindValue(':clastName', $member->getLastName());
                $stmt->bindValue(':cDOB', $member->getDob());
                $stmt->bindValue(':cPhone', $member->getPhone());
                $stmt->bindValue(':cEmail', $member->getEmail());
                $stmt->bindValue(':cAddressLine1', $member->getAddressLine1());
                $stmt->bindValue(':cAddressLine2', $member->getAddressLine2());
                $stmt->bindValue(':cCity', $member->getCity());
                $stmt->bindValue(':cCounty', ucfirst($member->getCounty()));
                $stmt->bindValue(':cEircode', $member->getEircode());
                $stmt->bindValue(':cRegistrationDate', $member->getRegistrationDate());
                $stmt->bindValue(':cStatus', $member->getStatus());

                $stmt->execute();
            } catch (PDOException $e) {
                throw new Exception("Error updating member: " . $e->getMessage());
            }
        }

        public function searchMember(string $searchKey) {
            if (empty($searchKey)) {
                return $this->getAllMembers();
            }

            $sql = "SELECT * FROM Members WHERE FirstName LIKE :search OR LastName LIKE :search OR DOB LIKE :search OR
                    Phone LIKE :search OR Email LIKE :search OR AddressLine1 LIKE :search OR AddressLine2 LIKE :search OR
                    City LIKE :search OR County LIKE :search OR Eircode LIKE :search OR RegistrationDate LIKE :search OR
                    Status LIKE :search";

            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(":search", $searchKey);
            $stmt->execute();

            $rows = $stmt->fetchAll();

            $members = [];

            foreach($rows as $row) {
                $members[] = new Member (
                        $row['FirstName'],
                        $row['LastName'],
                        $row['DOB'],
                        $row['Phone'],
                        $row['Email'],
                        $row['AddressLine1'],
                        $row['AddressLine2'],
                        $row['City'],
                        $row['County'],
                        $row['Eircode'],
                        $row['RegistrationDate'],
                        $row['Status'],
                        $row['MemberID']
                );
            }

            return $members;
        }
    }
?>