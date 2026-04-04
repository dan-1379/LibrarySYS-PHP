<?php 
    /** 
     * Handles database operations for the books table in the library system.
     * 
     * Provides methods to retrieve, add, update and delete books from the database.
     * 
     * @author Dan
     * @version 1.0
    */
    class BookRepository {
        /**
         * The database connection.
         * 
         * @var pdo
         */
        private PDO $pdo;

        /**
         * Creates a new BookRepository instance.
         * 
         * @param PDO $pdo The database connection
         */
        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
        }

        /**
         * Retrieves all books from the database.
         * 
         * @return array An array of book objects, or an empty array if none found.
         */
        public function getAllBooks() : array {
            try {
                $sql = 'SELECT * FROM Books';
                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();

                $rows = $stmt->fetchAll();
                $books = [];

                foreach($rows as $row) {
                    $books[] = new Book (
                        $row['Title'],
                        $row['Author'],
                        $row['Description'],
                        $row['ISBN'],
                        $row['Genre'],
                        $row['Publisher'],
                        $row['PublicationDate'],
                        $row['Status'],
                        $row['BookID']
                    );
                }

                return $books;
            } catch (PDOException $e) {  
                throw new Exception("Could not retrieve books from the database.");
            } 
        }

        /**
         * Retrieves the total number of books in the database.
         * 
         * @return int The total number of books, or 0 if an error occurs.
         */
        public function getTotalCount() : int {
            try {
                $sql = 'SELECT COUNT(*) FROM Books';

                $stmt = $this->pdo->prepare($sql);
                $stmt->execute();

                return $stmt->fetchColumn();
            } catch (PDOException $e) {  
                throw new Exception("Could not retrieve book count.");
            } 
        }

        /**
         * Inserts a new Book into the database.
         * 
         * @param Book $book The book object to be inserted.
         * @throws PDOException If the book cannot be inserted into the database.
         * @return void
         */
        public function insertBook(Book $book) : void {
            try {
                $sql = "INSERT INTO Books (Title, Author, Description, ISBN, Genre, Publisher, PublicationDate, Status) 
                                VALUES (:ctitle, :cauthor, :cdescription, :cisbn, :cgenre, :cpublisher, :cpublication, :cstatus)";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':ctitle', $book->getTitle());
                $stmt->bindValue(':cauthor', $book->getAuthor());
                $stmt->bindValue(':cdescription', $book->getDescription());
                $stmt->bindValue(':cisbn', $book->getIsbn());
                $stmt->bindValue(':cgenre', ucfirst($book->getGenre()));
                $stmt->bindValue(':cpublisher', $book->getPublisher());
                $stmt->bindValue(':cpublication', $book->getPublicationDate());
                $stmt->bindValue(':cstatus', $book->getStatus());

                $stmt->execute();
            } catch (PDOException $e) {  
                throw new Exception("This book could not be saved to the database.");
            } 
        }

        /**
         * Searches for a book in the database by its ISBN.
         * 
         * @param string $searchKey The ISBN of the book to locate.
         * @return Book|null The book object if found. Otherwise, null.
         */
        public function searchBooks(string $searchKey) : ?Book {
            try {    
                $sql = "SELECT * FROM Books WHERE ISBN = :search";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":search", $searchKey);
                $stmt->execute();

                $row = $stmt->fetch();

                if (!$row) {
                    return null;
                }

                return new Book (
                        $row['Title'],
                        $row['Author'],
                        $row['Description'],
                        $row['ISBN'],
                        $row['Genre'],
                        $row['Publisher'],
                        $row['PublicationDate'],
                        $row['Status'],
                        $row['BookID']
                );
            } catch (PDOException $e) {  
                throw new Exception("An error occurred while searching. Please try again.");
            } 
        }

        /**
         * Searches for a book in the database by its ID number.
         * 
         * @param int $bookID The ID of the book to locate.
         * @return Book|null The book object if found. Otherwise, null.
         */
        public function getBookById(int $bookID) : ?Book {
            try {    
                $sql = "SELECT * FROM Books WHERE BookID = :cbookID";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(":cbookID", $bookID);
                $stmt->execute();

                $row = $stmt->fetch();

                if (!$row) {
                    return null;
                }
                
                return new Book (
                        $row['Title'],
                        $row['Author'],
                        $row['Description'],
                        $row['ISBN'],
                        $row['Genre'],
                        $row['Publisher'],
                        $row['PublicationDate'],
                        $row['Status'],
                        $row['BookID']
                );
            } catch (PDOException $e) {  
                throw new Exception("An error occurred while searching. Please try again.");
            } 
        }

        /**
         * Updates the status of the book in the database.
         * 
         * @param Book $book The book object to be updated.
         * @throws PDOException If the book cannot be altered in the database.
         * @return void
         */
        public function alterBookStatus(Book $book) : void {
            try {
                $sql = "UPDATE Books SET Status = :cstatus WHERE BookID = :cbookID";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':cbookID', $book->getId());
                $stmt->bindValue(':cstatus', $book->getStatus() === 'A' ? 'U' : 'A');

                $stmt->execute();
            } catch (PDOException $e) {
                throw new Exception("An error occured. Please try again");
            }
        }
    }
?>