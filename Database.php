<?php 
    class Database {
        private PDO $pdo;

        public function __construct() {
            try {
                $this->pdo = new PDO('mysql:host=localhost;dbname=LibrarySYS;charset=utf8', 'root', '');
                $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                $title = 'An error has occurred';
                $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
            }
        }

        public function insertBookRecord(Book $book) {
            try {
                $sql = "INSERT INTO Books (Title, Author, Description, ISBN, Genre, Publisher, PublicationDate, Status)
                            VALUES (:ctitle, :cauthor, :cdescription, :cisbn, :cgenre, :cpublisher, :cpublication, :cstatus)";

                $stmt = $this->pdo->prepare($sql);
                $stmt->bindValue(':ctitle', $book->title);
                $stmt->bindValue(':cauthor', $book->author);
                $stmt->bindValue(':cdescription', $book->description);
                $stmt->bindValue(':cisbn', $book->isbn);
                $stmt->bindValue(':cgenre', $book->genre);
                $stmt->bindValue(':cpublisher', $book->publisher);
                $stmt->bindValue(':cpublication', $book->publication);
                $stmt->bindValue(':cstatus', $book->status);

                $stmt->execute();
            } catch (PDOException $e) {
                $title = 'An error has occurred';
                $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
            }
        }

        public function fetchAllBooks() : void {
            $sql = 'SELECT * FROM Books';
            $result = $this->pdo->prepare($sql); 
            $result->execute(); 

            while ($row = $result->fetch()) { 
                $statusText = ($row['Status'] === 'A') ? 'Available' : 'Unavailable';

                echo "<tr>";
                echo "<td>{$row['BookID']}</td>";
                echo "<td>{$row['Title']}</td>";
                echo "<td>{$row['Author']}</td>";
                echo "<td>{$row['Description']}</td>";
                echo "<td>{$row['ISBN']}</td>";
                echo "<td>{$row['Genre']}</td>";
                echo "<td>{$row['Publisher']}</td>";
                echo "<td>{$row['PublicationDate']}</td>";
                echo "<td>$statusText</td>";
                echo '</tr>';
            } 
        }
    }
?>