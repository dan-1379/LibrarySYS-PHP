<?php
    /** 
     * Handles database operations for the fines table in the library system.
     * 
     * Provides methods to retrieve, add, update and delete fines from the database.
     * 
     * @author Dan
     * @version 1.0
    */
    class FineRepository {
        /**
         * The database connection.
         * 
         * @var pdo
         */
        private PDO $pdo;

        /**
         * Creates a new FineRepository instance.
         * 
         * @param PDO $pdo The database connection
         */
        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
        }

        /**
         * Inserts a new Fine into the database.
         * 
         * @param Fine $fine The fine object to be inserted.
         * @return void
         */
        public function insertFine(Fine $fine) : void {
            try {
                $sql = "INSERT INTO FINES (FineAmount, Status, LoanID, BookID)
                        VALUES (:cfineAmount, :cstatus, :cloanID, :cbookID)";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':cfineAmount', $fine->getFineAmount());
                $stmt->bindValue(':cstatus', $fine->getStatus());
                $stmt->bindValue(':cloanID', $fine->getLoanID());
                $stmt->bindValue(':cbookID', $fine->getBookID());

                $stmt->execute();         
            } catch (PDOException $e) {  
                throw $e;
            } 
        }

        /**
         * Retrieves the total unpaid fine amount for a member.
         * 
         * @param int $memberID The unique identifer of the member.
         * @return float The total unpaid fine amount, or 0.00 if no unpaid fines.
         */
        public function getUnpaidMemberFine(int $memberID) : float {
            try {
                $sql = "SELECT SUM(f.FineAmount) 
                        FROM Fines f
                        JOIN Loans l ON f.LoanID = l.LoanID
                        WHERE l.MemberID = :cmemberID 
                        AND f.Status = 'U'";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':cmemberID', $memberID);

                $stmt->execute();
                $result = $stmt->fetchColumn();

                return $result > 0 ? (float) $result : 0.00;
            } catch (PDOException $e) {  
                throw $e;
            }
        }

        /**
         * Updates the status of a fine in the database
         * 
         * @param int $fineID The unique identifier of the fine to be updated.
         * @param string $status The new status of the fine, Unpaid ('U') or Paid ('P')
         */
        public function updateFineStatus(int $fineID, string $status) : void {}

        public function getAllFines() : array {
            try {
                $sql = 'SELECT * FROM Fines';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();

                $rows = $stmt->fetchAll();
                $fines = [];

                foreach($rows as $row) {
                    $fines[] = new Fine (
                        $row['FineAmount'],
                        $row['LoanID'],
                        $row['BookID'],
                        $row['FineID'],
                        $row['Status'],
                    );
                }

                return $fines;
            } catch (PDOException $e) {  
                return [];
            }
        }

        /**
         * Hard deletes a fine from the database.
         * 
         * @param int $fineID The unique identifier of the fine to be deleted.
         */
        public function deleteFine(int $fineID) : void {
            try {
                $sql = "DELETE FROM Fines WHERE FineID = :cfineID";
                $stmt = $this->pdo->prepare($sql);

                $stmt->bindValue("cfineID", $fineID);
                $stmt->execute();
            } catch(PDOException $e) {
                throw $e;
            }
        }

        public function alterFineStatus(int $fineID) {
            try {
                $sql = "UPDATE FINES SET Status = 'P' WHERE FineID = :cfineID";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':cfineID', $fineID);
                $stmt->execute();
            } catch(PDOException $e) {
                throw $e;
            }
        }

        public function calculateOverdueDays(int $loanID) : int {
            try {
                $sql = "SELECT DueDate FROM LOANS WHERE LoanID = :cloanID";
                $stmt = $this->pdo->prepare($sql);

                $stmt->bindValue(":cloanID", $loanID);
                $stmt->execute();

                $row = $stmt->fetch();

                if (!$row) {
                    return 0;
                }

                $dueDate = new DateTime($row['DueDate']);
                $today = new DateTime();

                if ($today <= $dueDate) {
                    return 0;
                }

                // https://www.php.net/manual/en/datetime.diff.php
                return (int) $today->diff($dueDate)->days;

            } catch(PDOException $e) {
                throw $e;
            }
        }

        public function calculateFineAmount($daysOverdue) : float {
            return $daysOverdue * FINE_RATE_PER_DAY;
        }

        public function getTotalFines() : float {
            try {
                $sql = "SELECT SUM(FineAmount) FROM Fines WHERE Status = 'U'";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();

                $result = $stmt->fetchColumn();

                return $result === 0.00 ? 0.00 : (float) $result;
            } catch(PDOException $e) {
                throw $e;
            }
        }
    }
?>