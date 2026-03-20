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
         * Creates a new LibraryService instance.
         * @param BookRepository $bookRepo The repository for the book database operations.
         * @param MemberRepository $memberRepo The repository for the member database operations.
         */
        public function __construct(BookRepository $bookRepo, MemberRepository $memberRepo, LoanRepository $loanRepo, FineRepository $fineRepo) {
            $this->bookRepo = $bookRepo;
            $this->memberRepo = $memberRepo;
            $this->loanRepo = $loanRepo;
            $this->fineRepo = $fineRepo;
        }

        /**
         * Validates and adds a new Book to the database.
         * 
         * Validates all book fields before inserting into the database.
         * Returns an array of errors if validation fails, or an empty array if book was added successfully.
         * 
         * @param Book $book The Book object to be validated and added.
         * @return array An array of the validation errors, or empty array if successful.
         */
        public function addBook(Book $book) : array {
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

            if (empty($inputErrors)) {
                $this->bookRepo->insertBook($book);
            }

            return $inputErrors;
        }

        /**
         * Retrieves all books from the database using the BookRepository operation.
         * 
         * @return array An array of Book objects, or an empty array if none found.
         */
        public function getAllBooks() : array {
            return $this->bookRepo->getAllBooks();
        }

        /**
         * Retrieves the total number of books in the database using the BookRepository operation.
         * 
         * @return int The total number of books.
         */
        public function getTotalBooks() : int {
            return $this->bookRepo->getTotalCount();
        }

        /**
         * Searches for a book in the database by its ISBN.
         * 
         * Validates the ISBN before searching the database.
         * 
         * @param string $searchKey The ISBN of the book to search for.
         * @throws InvalidArgumentException If the ISBN is not valid.
         * @return Book|null The Book object if found, null otherwise.
         */
        public function searchBooks(string $searchKey) : ?Book {
            if (!BookValidator::isValidISBN($searchKey)) {
                throw new InvalidArgumentException("Not a valid ISBN. Please try again.");
            }

            return $this->bookRepo->searchBooks($searchKey);
        }

        public function alterBookStatus(Book $book) : void {
            $this->bookRepo->alterBookStatus($book);
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
         */
        public function addMember(Member $member) : array {
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

            if (empty($inputErrors)) {
                $this->memberRepo->addMember($member);
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
         */
        public function updateMember(Member $member) : array {
            $inputErrors = [];

            if (!MemberValidator::isValidName($member->getFirstName())) {
                $inputErrors['cFirstName'] = "Invalid first name.";
            }

            if (!MemberValidator::isValidName($member->getLastName())) {
                $inputErrors['cLastName'] = "Invalid last name.";
            }

            if (!MemberValidator::isValidDOB($member->getDob())) {
                $inputErrors['cDOB'] = "Invalid DOB.";
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

            if (empty($inputErrors)) {
                $this->memberRepo->updateMember($member);
            }

            return $inputErrors;
        }
        
        /**
         * Retrieves all members from the database.
         * 
         * @return array An array of Member objects, or an empty array if none found.
         */
        public function getAllMembers() : array {
            return $this->memberRepo->getAllMembers();
        }

        /**
         * Searches for a member in the database by their ID.
         * 
         * Validates the member ID before searching the database.
         * 
         * @param string $searchKey The ID of the member to search for.
         * @throws InvalidArgumentException If the member ID is not valid.
         * @return Member|null The Member object if found, null otherwise.
         */
        public function searchMembers(string $searchKey) : ?Member {
            if (!MemberValidator::isValidID($searchKey)) {
                throw new InvalidArgumentException("Not a valid ID. Please try again.");
            }

            return $this->memberRepo->searchMember($searchKey);
        }

        /**
         * Toggles the status of a member between Active ('A') and Inactive ('I').
         * 
         * @param Member $member The Member object whose status is to be altered.
         * @return void
         */
        public function alterMemberStatus(Member $member) : void {
            $this->memberRepo->alterMemberStatus($member);
        }

        public function insertLoan(Loan $loan) : int {
            return $this->loanRepo->insertLoan($loan);
        } 

        public function insertLoanItem(LoanItem $loanItem) : void {
            $this->loanRepo->insertLoanItem($loanItem);
        }

        public function hasOverdueBooks(int $member) : int {
            return $this->loanRepo->hasOverDueBooks($member);
        }

        public function getCurrentLoanCount(int $member) : int {
            return $this->loanRepo->getCurrentLoanCount($member);
        }
        
        public function getUnpaidMemberFine(int $memberID) : float {
            return $this->fineRepo->getUnpaidMemberFine($memberID);
        }

        public function processLoan(Loan $loan, array $booksInCart) : void {
            $this->loanRepo->processLoan($loan, $booksInCart);

            foreach($booksInCart as $bookInCart) {
                $this->bookRepo->alterBookStatus($bookInCart);
            }
        }
    }
?>