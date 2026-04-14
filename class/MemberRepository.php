<?php 
    /** 
     * Handles database operations for the members table in the library system.
     * 
     * Provides methods to retrieve, add, update and delete members from the database.
     * 
     * @author Dan
     * @version 1.0
    */
    class MemberRepository {
        /**
         * The database connection.
         * 
         * @var pdo
         */
        private PDO $pdo;

        /**
         * Creates a new MemberRepository instance.
         * 
         * @param PDO $pdo The database connection
         */
        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
        }

        /**
         * Retrieves all members from the database.
         * 
         * @return array An array of member objects, or an empty array if none found.
         */
        function getAllMembers() : array {
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
        }

        /**
         * Updates a specified Member in the database.
         * 
         * @param Member $member The member object to be updated.
         * @throws PDOException If the member cannot be updated in the database.
         * @return void
         */
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
                throw new Exception("Error updating member. Please try again.");
            }
        }

        /**
         * Inserts a new Member into the database.
         * 
         * @param Member $member The member object to be inserted.
         * @throws PDOException If the member cannot be inserted into the database.
         * @return void
         */
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
                throw new Exception("Error adding member. Please try again.");
            }
        }

        /**
         * Searches for a member in the database by its ISBN.
         * 
         * @param string $searchKey The ID of the member to locate.
         * @return Member|null The member object if found. Otherwise, null.
         */
        public function searchMember(string $searchKey) {
            try {
                if (empty($searchKey)) {
                    return null;
                }

                $sql = "SELECT * FROM Members WHERE MemberID = :search";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":search", $searchKey);
                $stmt->execute();

                $row = $stmt->fetch();

                if (!$row) {
                    return null;
                }

                return new Member (
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
            } catch (PDOException $e) {  
                throw new Exception("An error occurred while searching. Please try again.");
            }
        }

        /**
         * Updates the status of the member in the database.
         * 
         * @param Member $member The member object to be updated.
         * @throws PDOException If the member cannot be altered in the database.
         * @return void
         */
        public function alterMemberStatus(int $memberID) : void {
            try {
                $sql = "UPDATE Members SET Status = 'I' WHERE MemberID = :cMemberID";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':cMemberID', $memberID);

                $stmt->execute();
            } catch (PDOException $e) {
                throw new Exception("Error updating member: " . $e->getMessage());
            }
        }

        /**
         * Retrieves the total count of members in the database.
         * 
         * @param $status The count of members the status applies to i.e. 'A' for active
         *  or 'I' for inactive.
         * @return int An integer count of the members.
         */
        public function getTotalMembers(string $status) : int {
                $sql = "SELECT COUNT(*) FROM Members WHERE Status = :cstatus";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":cstatus", $status);
                $stmt->execute();

                return (int) $stmt->fetchColumn();
        }
    }
?>