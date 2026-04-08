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
                throw new Exception("Error inserting fine.");
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
                throw new Exception("Error retrieving unpaid member fine.");
            }
        }

        /**
         * Retrieves all fines from the database.
         * 
         * @return array An array of fine objects, or an empty array if none found.
         */
        public function getAllFines() : array {
            try {
                $sql = 'SELECT f.*, m.FirstName, m.LastName, b.Title 
                        FROM Fines f
                        JOIN Loans l ON f.LoanID = l.LoanID
                        JOIN Members m on l.MemberID = m.MemberID
                        JOIN Books b ON f.BookID = b.BookID';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();

                $rows = $stmt->fetchAll();
                $fines = [];

                foreach($rows as $row) {
                    $fines[] = [
                        "fine" => new Fine (
                            $row['FineAmount'],
                            $row['LoanID'],
                            $row['BookID'],
                            $row['FineID'],
                            $row['Status'],
                        ),
                        "member" => $row["FirstName"] . " " . $row["LastName"],
                        "book" => $row["Title"]
                    ];
                }

                return $fines;
            } catch (PDOException $e) {  
                throw new Exception("Error retrieving all fines.");
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
                throw new Exception("Error deleting fine record.");
            }
        }

        /**
         * Changes the status of the fine to signify paid.
         * 
         * @param int $fineID The unique identifier of the fine to be deleted.
         * @return void
         */
        public function alterFineStatus(int $fineID) : void {
            try {
                $sql = "UPDATE FINES SET Status = 'P' WHERE FineID = :cfineID";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':cfineID', $fineID);
                $stmt->execute();
            } catch(PDOException $e) {
                throw new Exception("Error changing fine status.");
            }
        }

        /**
         * Compares the due date of the loan to todays date to check days overdue.
         * 
         * @param int $loanID The unique identifier of the loan.
         * @return int The number of days the book is overdue.
         */
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
                throw new Exception("Error calculating overdue days.");
            }
        }

        /**
         * Calculates the total fine.
         * Uses a defined constant in the configuration file to calculate.
         * 
         * @param int $daysOverdue The number of days overdue.
         * @return float The total fine for the loanitem.
         */
        public function calculateFineAmount($daysOverdue) : float {
            return $daysOverdue * FINE_RATE_PER_DAY;
        }

        /**
         * Retrieves the total number of fines in the database.
         * 
         * @return float The total value of fines that are unpaid.
         */
        public function getTotalFines() : float {
            try {
                $sql = "SELECT SUM(FineAmount) FROM Fines WHERE Status = 'U'";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();

                $result = $stmt->fetchColumn();

                return $result === 0.00 ? 0.00 : (float) $result;
            } catch(PDOException $e) {
                throw new Exception("Error retrieving total fines.");
            }
        }

        /**
         * Retrieves the details of the member with the most amount of fines.
         * 
         * @return array An associative array containing member details and total fine sum.
         */
        public function getTopFineOffender() : ?array {
            try {
                $sql = "SELECT m.*, sum(f.FineAmount) as Total_Fine
                        FROM Fines f
                        JOIN Loans l ON f.LoanID = l.LoanID
                        JOIN Members m ON l.MemberID = m.MemberID
                        GROUP BY m.MemberID
                        ORDER BY Total_Fine DESC
                        LIMIT 1";

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (!$row) {
                    return null;
                }
                
                return $row;
            } catch(PDOException $e) {
                throw new Exception("Error retrieving top fine offender.");
            }
        }
    }
?>