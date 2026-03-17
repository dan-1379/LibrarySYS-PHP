<?php 
    /** 
     * Handles business logic for Library
     * https://stackoverflow.com/questions/40911731/what-are-repositories-services-and-actions-controllers
    */
    class LibraryService {
        private BookRepository $bookRepo;
        private MemberRepository $memberRepo;

        public function __construct(BookRepository $bookRepo, MemberRepository $memberRepo) {
            $this->bookRepo = $bookRepo;
            $this->memberRepo = $memberRepo;
        }

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

        public function getAllBooks() : array {
            return $this->bookRepo->getAllBooks();
        }

        public function getTotalBooks() : int {
            return $this->bookRepo->getTotalCount();
        }

        public function searchBooks(string $searchKey) : array {
            return $this->bookRepo->searchBooks($searchKey);
        }

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
        
        public function getAllMembers() : array {
            return $this->memberRepo->getAllMembers();
        }

        public function searchMembers(string $searchKey) : array {
            return $this->memberRepo->searchMember($searchKey);
        }

        public function alterMemberStatus(Member $member) : void {
            $this->memberRepo->alterMemberStatus($member);
        }
    }
?>