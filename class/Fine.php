<?php
    /**
     * Represents a fine in the library system.
     * 
     * Stores all details related to a fine including the amount, status, associated loan and associated book.
     * 
     * @author Dan
     * @version 1.0
     */
    class Fine {
        /**
         * The unique identifier for the fine. Defaults to 0 for new books as it will be auto incremented in mariadb
         * @var int
         */
        private int $id;

        /**
         * The amount of the fine.
         * @var float
         */
        private float $amount;

        /**
         * The status of the fine, Unpaid ('U') or Paid ('P'). Defaults to 'U'.
         * @var string
         */
        private string $status;

        /**
         * The associated loan identifer for the fine.
         * @var int
         */
        private int $loanID;

        /**
         * The associated book identifer for the fine.
         * @var int
         */
        private int $bookID;

        /**
        * Creates a new Fine instance.
        * 
        * @param float $amount The amount of the fine.
        * @param string $status The status of the fine, Unpaid ('U') or Paid ('P'). Defaults to 'U'.
        * @param int $loanID The associated loan identifer for the fine.
        * @param int $bookID The associated book identifer for the fine.
        * @param int $ID The unique identifier for the fine. Defaults to 0 for new books as it will be auto incremented in mariadb
        */
        public function __construct(float $amount, int $loanID, int $bookID, int $ID = 0, string $status = "U") {
            $this->id = $ID;
            $this->amount = $amount;
            $this->status = $status;
            $this->loanID = $loanID;
            $this->bookID = $bookID;
        }

        /**
         * Returns the unique identifier for the fine. Defaults to 0 for new books as it will be auto incremented in mariadb
         * @return int The fine ID.
         */
        public function getFineID() : int {
            return $this->id;
        }

        /**
         * Returns the amount of the fine.
         * @return float The fine amount.
         */
        public function getFineAmount() : float {
            return $this->amount;
        }

        /**
         * Returns the status for the fine.
         * @return string The fine status.
         */
        public function getStatus() : string {
            return $this->status;
        }

        /**
         * Sets the status of the fine, Unpaid ('U') or Paid ('P'). Defaults to 'U'.
         * @param string $status The new status of the fine.
         * @return void
         */
        public function setStatus(string $status) : void {
            $this->status = $status;
        }

        /**
         * Returns the associated loan identifier for the fine.
         * @return int The loan ID.
         */
        public function getLoanID() : int {
            return $this->loanID;
        }

        /**
         * Returns the associated book identifier for the fine.
         * @return int The book ID.
         */
        public function getBookID() : int {
            return $this->bookID;
        }

        /**
         * Checks if a fine has been paid.
         * @return bool True if the fine is paid ('P'). Otherwise false.
         */
        public function isPaid() : bool {
            return $this->status === 'P';
        }
    }
?>