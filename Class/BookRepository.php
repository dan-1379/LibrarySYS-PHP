<?php 
    /** 
     * Handles database interaction for Books
    */
    class BookRepository {
        private PDO $pdo;

        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
        }

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
                return [];
            } 
        }

        function getTotalCount() : int {
            $sql = 'SELECT COUNT(*) FROM Books';
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute();
            return $stmt->fetchColumn();
        }

        function insertBook(Book $book) : void {
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
        }
    }
?>