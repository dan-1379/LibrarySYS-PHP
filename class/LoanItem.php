<?php 
    /**
     * Represents a loan item in the library system.
     * 
     * Stores all details related to a loan item including the loan ID, associated book ID and return date.
     * 
     * @author Dan
     * @version 1.0
     */
    class LoanItem {
        /**
         * The associated loan ID for the loan item.
         * @var int
         */
        private int $loanID;

        /**
         * The associated book ID for the loan item.
         * @var int
         */
        private int $bookID;

        /**
         * The return date for the loan item. Inserted into loan items table as null.
         * @var DateTime
         */
        private ?DateTime $returnDate;

        /**
        * Creates a new LoanItem instance.
        * 
        * @param int $loanID The associated loan ID for the loan item.
        * @param int $bookID The associated book ID for the loan item.
        * @param DateTime $returnDate The return date for the loan item.
        */
        public function __construct(int $loanID, int $bookID, ?DateTime $returnDate = null) {
            $this->bookID = $bookID;
            $this->returnDate = $returnDate;
            $this->loanID = $loanID;
        }

        /**
         * Returns the book ID of the loan item.
         * @return int The associated book ID of the loan item.
         */
        public function getBookID() : int {
            return $this->bookID;
        }

        /**
         * Returns the return date of the loan item.
         * @return int The return date of the loan item.
         */
        public function getReturnDate() : ?DateTime {
            return $this->returnDate;
        }

        /**
         * Sets the return date of the loan item. Defaults to null.
         * @param DateTime $returnDate The return date of the item.
         * @return void
         */
        public function setReturnDate(DateTime $returnDate) : void {
            $this->returnDate = $returnDate;
        }

        /**
         * Returns the associated loan ID of the loan item.
         * @return int The loan ID of the loan item.
         */
        public function getLoanID() : int {
            return $this->loanID;
        }

        /**
         * Checks if a loan item has been returned.
         * @return bool True if the loan item is not null. Otherwise false.
         */
        public function isReturned() : bool {
            return $this->returnDate !== null;
        }

        /**
         * Checks if a loan item is overdue.
         * @return ?DateTime True if the loan item is overdue. Otherwise false.
         */
        public function isOverdue(DateTime $dueDate) : bool {
            return $this->returnDate === null && new DateTime() > $dueDate;
        }
    }
?>