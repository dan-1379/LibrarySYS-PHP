<?php 
    /**
     * Represents a loan in the library system.
     * 
     * Stores all details related to a loan including the loan date, due date and associated member ID.
     * 
     * @author Dan
     * @version 1.0
     */
    class Loan {
        /**
         * The unique identifier for the loan. Defaults to 0 for new loans as it will be auto incremented in mariadb
         * @var int
         */
        private int $loanID;

        /**
         * The loan date of the loan.
         * @var DateTime
         */
        private DateTime $loanDate;

        /**
         * The due date of the loan.
         * @var DateTime
         */
        private DateTime $dueDate;

        /**
         * The unique identifier of the associated member.
         * @var int
         */
        private int $memberID;

        /**
        * Creates a new Loan instance.
        * 
        * @param DateTime $loanDate The loan date of the loan.
        * @param DateTime $dueDate The due date of the loan.
        * @param int $memberID The unique identifier of the associated member.
        * @param int $loanID The unique identifier for the loan. Defaults to 0 for new loans as it will be auto incremented in mariadb
        */
        public function __construct(DateTime $loanDate, DateTime $dueDate, int $memberID, int $loanID = 0) {
            $this->loanDate = $loanDate;
            $this->dueDate = $dueDate;
            $this->loanID = $loanID;
            $this->memberID = $memberID;
        }

        /**
         * Returns the loan date of the loan.
         * @return DateTime The loan date.
         */
        public function getLoanDate() : DateTime {
            return $this->loanDate;
        }

        /**
         * Returns the due date of the loan.
         * @return DateTime The due date.
         */
        public function getDueDate() : DateTime {
            return $this->dueDate;
        }

        /**
         * The unique identifier for the loan. Defaults to 0 for new loans as it will be auto incremented in mariadb
         * @return int The loan ID.
         */
        public function getLoanID() : int {
            return $this->loanID;
        }

        /**
         * The unique identifier for the associated member.
         * @return int The member ID.
         */
        public function getMemberID() : int {
            return $this->memberID;
        }
    }
?>