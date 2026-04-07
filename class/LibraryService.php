<?php 
    /** 
     * Handles business logic for Library
     * 
     * Acts as an intermediary between the UI and the repository (databse logic),
     * providing vaidation and business logic.
     * @see https://stackoverflow.com/questions/40911731/what-are-repositories-services-and-actions-controllers
     * 
     * @author Dan
     * @version 1.0
    */
    class LibraryService {
        /**
         * The repository for the book database operations.
         * @var BookRepository
         */
        private BookRepository $bookRepo;

        /**
         * The repository for the member database operations.
         * @var MemberRepository
         */
        private MemberRepository $memberRepo;

        /**
         * The repository for the loan database operations.
         * @var LoanRepository
         */
        private LoanRepository $loanRepo;

        /**
         * The repository for the fine database operations.
         * @var FineRepository
         */
        private FineRepository $fineRepo;

        /**
         * The repository for the user database operations.
         * @var AuthenticationRepository
         */
        private AuthenticationRepository $authRepo;

        /**
         * Creates a new LibraryService instance.
         * @param BookRepository $bookRepo The repository for the book database operations.
         * @param MemberRepository $memberRepo The repository for the member database operations.
         * @param LoanRepository $loanRepo The repository for the loan database operations.
         * @param FineRepository $fineRepo The repository for the fine database operations.
         * @param AuthenticationRepository $authRepo The repository for the user database operations.
         */
        public function __construct(BookRepository $bookRepo, MemberRepository $memberRepo, LoanRepository $loanRepo, FineRepository $fineRepo, AuthenticationRepository $authRepo) {
            $this->bookRepo = $bookRepo;
            $this->memberRepo = $memberRepo;
            $this->loanRepo = $loanRepo;
            $this->fineRepo = $fineRepo;
            $this->authRepo = $authRepo;
        }

        /**
         * Validates a users login credentials.
         * 
         * Validates the username and password fields and checks the credentials against
         * the users table.
         * 
         * @param string $username The username of the user.
         * @param string $password The password of the user prior to hashing.
         * 
         * @return null|array An array of errors if errors occur or null if login is successful.
         * 
         * @see AuthenticationValidator::isValidUsername()
         * @see AuthenticationValidator::isValidPassword()
         * @see AuthenticationRepository::processLogin()
         */
        public function processLogin($username, $password) : ?array {
            $credentialErrors = [];

            $checkUsername = AuthenticationValidator::isValidUsername($username);
            $checkPassword = AuthenticationValidator::isValidPassword($password);

            if ($checkUsername != "valid") {
                $credentialErrors['usernameError'] = $checkUsername;
            }

            if ($checkPassword != "valid") {
                $credentialErrors['passwordError'] = $checkPassword;
            }

            if (!empty($credentialErrors)) {
                return $credentialErrors;
            }

            $user =  $this->authRepo->processLogin($username, $password);

            if (!$user) {
                $credentialErrors['login'] = "Invalid username or password.";
                return $credentialErrors;
            }

            return $user;
        }

        /**
         * Validates the details of a Book object before adding or updating it in the system.
         * 
         * Checks all entry fields provided using the BookValidator class. Also checks that
         * the ISBN is not already in use by an existing book.
         * 
         * @param Book $book The book object whose details are to be validated.
         * @return array An associative array of validation errors.
         * 
         * @see BookValidator
         */
        private function validateBookDetails(Book $book) : array {
            $inputErrors = [];

            if (!BookValidator::isValidTitle($book->getTitle())) {
                $inputErrors['ctitle'] = "Invalid title. Please enter a valid title.";
            }

            if (!BookValidator::isValidAuthor($book->getAuthor())) {
                $inputErrors['cauthor'] = "Invalid author. Please enter a valid author.";
            }

            if (!BookValidator::isValidDescription($book->getDescription())) {
                $inputErrors['cdescription'] = "Invalid description. Please enter a valid description.";
            }

            $checkISBN = BookValidator::isValidISBN($book->getIsbn());

            if ($checkISBN != "Valid ISBN") {
                $inputErrors['cisbn'] = $checkISBN;
            }

            if (!BookValidator::isValidGenre($book->getGenre())) {
                $inputErrors['cgenre'] = "Invalid genre. Please enter a valid genre.";
            }

            if (!BookValidator::isValidPublisher($book->getPublisher())) {
                $inputErrors['cpublisher'] = "Invalid publisher. Please enter a valid publisher.";
            }

            if (!BookValidator::isValidPublicationDate($book->getPublicationDate())) {
                $inputErrors['cpublication'] = "Invalid publication. Please enter a valid publication.";
            }

            if (!BookValidator::isValidStatus($book->getStatus())) {
                $inputErrors['cstatus'] = "Invalid status. Please enter a valid status.";
            }

            if ($this->searchBooks($book->getIsbn()) !== null) {
                $inputErrors['cisbn'] = "ISBN already in use. Please enter a valid ISBN.";
            }

            return $inputErrors;
        }

        /**
         * Validates and adds a new Book to the database.
         * 
         * Validates all book fields before inserting into the database.
         * Returns an array of errors if validation fails, or an empty array if book was added successfully.
         * 
         * @param Book $book The Book object to be validated and added.
         * @return array An array of the validation errors, or empty array if successful.
         * 
         * @see BookValidator
         * @see BookRepository::insertBook()
         */
        public function addBook(Book $book) : array {
            $inputErrors = $this->validateBookDetails($book);

            if (empty($inputErrors)) {
                try {
                    $this->bookRepo->insertBook($book);
                } catch (Exception $e) {
                    $inputErrors["db_con"] = $e->getMessage();
                }
            }

            return $inputErrors;
        }

        /**
         * Retrieves all books from the database using the BookRepository operation.
         * 
         * @return array An array of Book objects, or an empty array if none found.
         * 
         * @see BookRepository::getAllBooks()
         */
        public function getAllBooks() : array {
            try {
                return $this->bookRepo->getAllBooks();
            } catch (Exception $e) {
                return [];
            }
        }

        /**
         * Retrieves the total number of books in the database using the BookRepository operation.
         * 
         * @return int The total number of books.
         * 
         * @see BookRepository::getTotalCount()
         */
        public function getTotalBooks() : int {
            try {
                return $this->bookRepo->getTotalCount();
            } catch (Exception $e) {
                return 0;
            }
        }

        /**
         * Searches for a book in the database by its ISBN.
         * 
         * Validates the ISBN before searching the database.
         * 
         * @param string $searchKey The ISBN of the book to search for.
         * @return Book|null The Book object if found, null otherwise.
         * 
         * @see BookValidator::isValidISBN()
         * @see BookRepository::searchBooks()
         */
        public function searchBooks(string $searchKey) : ?Book {
            if (BookValidator::isValidISBN($searchKey) !== "Valid ISBN") {
                return null;
            }

            try {
                return $this->bookRepo->searchBooks($searchKey);
            } catch (Exception $e) {
                return null;
            }
        }

         /**
         * Validates the details of a Member object before adding or updating it in the system.
         * 
         * Checks all entry fields provided using the MemberValidator class. 
         * 
         * @param Member $member The member object whose details are to be validated.
         * @return array An associative array of validation errors.
         */
        private function validateMemberDetails(Member $member) : array {
            $inputErrors = [];

            if (!MemberValidator::isValidName($member->getFirstName())) {
                $inputErrors['cFirstName'] = "Invalid first name";
            }

            if (!MemberValidator::isValidName($member->getLastName())) {
                $inputErrors['cLastName'] = "Invalid last name";
            }

            if (!MemberValidator::isValidDOB($member->getDob())) {
                $inputErrors['cDOB'] = "Invalid DOB";
            }

            $checkPhone = MemberValidator::isValidPhone($member->getPhone());

            if ($checkPhone != "valid") {
                $inputErrors['cPhone'] = $checkPhone;
            }

            $checkEmail = MemberValidator::IsValidEmail($member->getEmail());

            if ($checkEmail != "valid") {
                $inputErrors['cEmail'] = $checkEmail;
            }

            if (!MemberValidator::isValidAddressLine($member->getAddressLine1())) {
                $inputErrors['cAddressLine1'] = "Invalid address line 1";
            }

            if (!MemberValidator::isValidAddressLine($member->getAddressLine2())) {
                $inputErrors['cAddressLine2'] = "Invalid address line 2";
            }

            if (!MemberValidator::isValidCity($member->getCity())) {
                $inputErrors['cCity'] = "Invalid city";
            }

            if (!MemberValidator::isValidCounty($member->getCounty())) {
                $inputErrors['cCounty'] = "Invalid county";
            }

            $checkEircode = MemberValidator::isValidEircode($member->getEircode());

            if ($checkEircode != "valid") {
                $inputErrors['cEircode'] = $checkEircode;
            }

            if (!MemberValidator::isValidRegistrationDate($member->getRegistrationDate())) {
                $inputErrors['cRegistrationDate'] = "Invalid registration date";
            }

            if (!MemberValidator::isValidStatus($member->getStatus())) {
                $inputErrors['cStatus'] = "Invalid status";
            }

            return $inputErrors;
        }

        /**
         * Validates and adds a new member to the database.
         * 
         * Validates all member fields before inserting into the database.
         * Returns an array of errors if validation fails, or an empty
         * array if the member was successfully added.
         * 
         * @param Member $member The Member object to be validated and added.
         * @return array An array of validation errors, or empty if successful.
         * 
         * @see MemberValidator
         * @see MemberRepository::addMember()
         */
        public function addMember(Member $member) : array {
            $inputErrors = $this->validateMemberDetails($member);

            if (empty($inputErrors)) {
                try {
                    $this->memberRepo->addMember($member);
                } catch (Exception $e) {
                    $inputErrors["db_con"] = $e->getMessage();
                }
            }

            return $inputErrors;
        }

        /**
         * Validates and updates an existing member in the database.
         * 
         * Validates all member fields before updating the database.
         * Returns an array of errors if validation fails, or an empty
         * array if the member was successfully updated.
         * 
         * @param Member $member The Member object with updated details.
         * @return array An array of validation errors, or empty if successful.
         * 
         * @see MemberValidator
         * @see MemberRepository::updateMember()
         */
        public function updateMember(Member $member) : array {
            $inputErrors = $this->validateMemberDetails($member);

            if (empty($inputErrors)) {
                try {
                    $this->memberRepo->updateMember($member);
                } catch (Exception $e) {
                    $inputErrors['db_con'] = $e->getMessage();
                }
            }

            return $inputErrors;
        }
        
        /**
         * Retrieves all members from the database.
         * 
         * @return array An array of Member objects, or an empty array if none found.
         * 
         * @see MemberRepository::getAllMembers()
         */
        public function getAllMembers() : array {
            try {
                return $this->memberRepo->getAllMembers();
            } catch (Exception $e) {
                return [];
            }
        }

        /**
         * Retrieves the total count of members in the database.
         * 
         * @param string $status The count of members the status applies to i.e. 'A' or 'I'
         * @return int An integer count of the members.
         * 
         * @see MemberRepository::getTotalMembers()
         */
        public function getTotalMembers(string $status) : int {
            try {
                return $this->memberRepo->getTotalMembers($status);
            } catch (Exception $e) {
                return 0;
            }
        }

        /**
         * Searches for a member in the database by their ID.
         * 
         * Validates the member ID before searching the database.
         * 
         * @param string $searchKey The ID of the member to search for.
         * @throws InvalidArgumentException If the member ID is not valid.
         * @return Member|null The Member object if found, null otherwise.
         * 
         * @see MemberValidator::isValidID()
         * @see MemberRepository::searchMember()
         */
        public function searchMembers(string $searchKey) : ?Member {
            if (!MemberValidator::isValidID($searchKey)) {
                return null;
            }

            try {
                return $this->memberRepo->searchMember($searchKey);
            } catch (Exception $e) {
                return null;
            }
        }

        /**
         * Toggles the status of a member between Active ('A') and Inactive ('I').
         * 
         * @param Member $member The Member object whose status is to be altered.
         * @return void
         * 
         * @see LoanRepository::hasOverDueBooks()
         * @see FineRepository::getUnpaidMemberFine()
         * @see LoanRepository::getCurrentLoanCount()
         * @see MemberRepository::alterMemberStatus()
         */
        public function alterMemberStatus(int $memberID) : ?string {
            if ($this->hasOverdueBooks($memberID) > 0) {
                return "This member has overdue books";
            }
            
            if ($this->getUnpaidMemberFine($memberID) > 0) {
                return "This member has outstanding fines.";
            } 
            
            if ($this->getCurrentLoanCount($memberID) > 0) {
                return "This member books loaned.";
            }

            try {
                $this->memberRepo->alterMemberStatus($memberID);
                return null;
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }

        /**
         * Retrieves the count of books currently on loan to a member that are overdue.
         * 
         * @param int $member The member id.
         * @return int The count of books.
         * 
         * @see LoanRepository::hasOverDueBooks()
         */
        public function hasOverdueBooks(int $member) : int {
            try {
                return $this->loanRepo->hasOverDueBooks($member);
            } catch (Exception $e) {
                return 0;
            }
        }

        /**
         * Retrieves the count of books currently on loan to a member.
         * 
         * @param int $member The member id.
         * @return int The count of books.
         * 
         * @see LoanRepository::getCurrentLoanCount()
         */
        public function getCurrentLoanCount(int $member) : int {
            try {
                return $this->loanRepo->getCurrentLoanCount($member);
            } catch (Exception $e) {
                return 0;
            }
        }

        /**
         * Retrieves the books currently on loan to a member.
         * 
         * @param int $member The member id.
         * @return array An array of Book objects.
         * 
         * @see LoanRepository::getLoanedBooks()
         */
        public function getLoanedBooks(int $memberID) : array {
            try {
                return $this->loanRepo->getLoanedBooks($memberID);
            } catch (Exception $e) {
                return [];
            }
        }

        /**
         * Retrieves all loans from the database.
         * 
         * @return array An array of Loan objects, or an empty array if none found.
         * 
         * @see LoanRepository::getAllLoans()
         */
        public function getAllLoans() : array {
            try {
                return $this->loanRepo->getAllLoans();
            } catch (Exception $e) {
                return [];
            }
        }

        /**
         * Retrieves all loan items from the database.
         * 
         * @return array An array of LoanItem objects, or an empty array if none found.
         * 
         * @see LoanRepository::getAllLoanItems()
         */
        public function getAllLoanItems() : array {
            try {
                return $this->loanRepo->getAllLoanItems();
            } catch (Exception $e) {
                return [];
            }
        }

        /**
         * Updates the LoanDate and DueDate of a loan.
         * 
         * @return void
         * 
         * @see LoanRepository::updateLoanDetails()
         */
        public function updateLoanDetails(Loan $loan) : void {
            $this->loanRepo->updateLoanDetails($loan);
        }

        /**
         * Retrieves the count of loans on the system.
         * 
         * @return int The total count of loans.
         * 
         * @see LoanRepository::getTotalLoans()
         */
        public function getTotalLoans() : int {
            try {
                return $this->loanRepo->getTotalLoans();
            } catch (Exception $e) {
                return 0;
            }
        }

        /**
         * Retrieves the five most recent loans from the system.
         * 
         * @return array An array of the five most recent loans containing 
         * member, book and loan information.
         * 
         *  @see LoanRepository::getRecentLoans()
         */
        public function getRecentLoans() : array {
            try {
                return $this->loanRepo->getRecentLoans();
            } catch (Exception $e) {
                return [];
            }
        }

        /**
         * Retrieves the top three members with the highest loan count.
         * 
         * @return array An array of the three members along with loan count.
         * 
         * @see LoanRepository::getTopBorrowers()
         */
        public function getTopBorrowers() : array {
            try {
                return $this->loanRepo->getTopBorrowers();
            } catch (Exception $e) {
                return [];
            }
        }
        
        /**
         * Retrieves the total unpaid fine amount for a member.
         * 
         * @param int $memberID The unique identifer of the member.
         * @return float The total unpaid fine amount, or 0.00 if no unpaid fines.
         * 
         * @see FineRepository::getUnpaidMemberFine()
         */
        public function getUnpaidMemberFine(int $memberID) : float {
            try {
                return $this->fineRepo->getUnpaidMemberFine($memberID);
            } catch (Exception $e) {
                return 0.00;
            }
        }

         /**
         * Executes the main transaction process.
         * 
         * Processes the loan by insering a loan record and inserts a loan item record for
         * each book in the cart.
         * 
         * @param LoanItem $loanItem The LoanItem object to be added.
         * 
         * @see LoanRepository::processLoan()
         * @see BookRepository::alterBookStatus()
         */
        public function processLoan(Loan $loan, array $booksInCart) : void {
            try {
                $this->loanRepo->processLoan($loan, $booksInCart);

                foreach($booksInCart as $bookInCart) {
                    $this->bookRepo->alterBookStatus($bookInCart);
                }
            } catch (Exception $e) {
                throw $e;
            }
        }

        /**
         * Executes the secondary transaction process.
         * 
         * Processes the return by checking the dates to ensure loan is not overdue.
         * If overdue books are returned, a fine is calculated and inserted into the database.
         * Updates return date for loan items and status of book.
         * 
         * @param array $selectedToReturn An array of book ID's the user has selected to be 
         * returned.
         * 
         * @return array An array of fine strings for each book that now contains a fine,
         * containing the value of the fine and amount of days overdue.
         * 
         * @see LoanRepository::getLoanByBookID()
         * @see FineRepository::calculateOverdueDays()
         * @see FineRepository::calculateFineAmount()
         * @see Fine
         * @see FineRepository::insertFine()
         * @see BookRepository::alterBookStatus()
         * @see LoanRepository::processReturn()
         */
        public function processReturn(array $selectedToReturn) : array {
            try {
                $finedBooks = [];

                foreach($selectedToReturn as $bookID) {
                    $loanID = $this->loanRepo->getLoanByBookID((int) $bookID);

                    if ($loanID) {
                        $daysOverdue = $this->fineRepo->calculateOverdueDays($loanID);

                        if ($daysOverdue > 0) {
                            $fineAmount = $this->fineRepo->calculateFineAmount($daysOverdue);
                            $newFine = new Fine($fineAmount, $loanID, (int) $bookID);
                            $this->fineRepo->insertFine($newFine);

                            $finedBooks[] = "A fine of €" . number_format($fineAmount, 2) . 
                                            " has been added for book " . $bookID . " for days overdue: " . $daysOverdue;
                        }
                    }

                    $book = $this->bookRepo->getBookById((int) $bookID);

                    if ($book) {
                        $this->bookRepo->alterBookStatus($book);
                    }
                }

                $this->loanRepo->processReturn($selectedToReturn);
                return $finedBooks;
            } catch(Exception $e) {
                return [];
            }
        }

        /**
         * Retrieves all fines from the database.
         * 
         * @return array An array of Fine objects, or an empty array if none found.
         * 
         * @see FineRepository::getAllFines()
         */
        public function getAllFines() : array {
            try {
                return $this->fineRepo->getAllFines();
            } catch (Exception $e) {
                return [];
            }
        }

        /**
         * Hard deletes a fine from the database.
         * 
         * @param int $fineID The unique identifier of the fine to be deleted.
         * @return void
         * 
         * @see FineRepository::deleteFine()
         */
        public function deleteFine(int $fineID) : void {
            try {
                $this->fineRepo->deleteFine($fineID);
            } catch (Exception $e) {
                throw $e;
            }
        }

        /**
         * Changes the status of the fine to signify paid.
         * 
         * @param int $fineID The unique identifier of the fine to be deleted.
         * @return void
         * 
         * @see FineRepository::alterFineStatus()
         */
        public function alterFineStatus(int $fineID) : void {
            try {
                $this->fineRepo->alterFineStatus($fineID);
            } catch (Exception $e) {
                throw $e;
            }
        }

        /**
         * Retrieves the total number of fines in the database.
         * 
         * @return float The total value of fines that are unpaid.
         * 
         * @see FineRepository::getTotalFines()
         */
        public function getTotalFines() : float {
            try {
                return $this->fineRepo->getTotalFines();
            } catch (Exception $e) {
                return 0.00;
            }
        }
    }
?>