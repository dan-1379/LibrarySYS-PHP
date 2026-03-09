<?php
    include("BookValidator.php");
    $inputErrors = [];

    function insertBookRecord() {
        global $inputErrors;

        if (isset($_POST['submitBookDetails'])) {
            try {
                $ctitle = $_POST['ctitle'] ?? "";
                $cauthor = $_POST['cauthor'] ?? "";
                $cdescription = $_POST['cdescription'] ?? "";
                $cisbn = $_POST['cisbn'] ?? "";
                $cgenre = $_POST['cgenre'] ?? "";
                $cpublisher = $_POST['cpublisher'] ?? "";
                $cpublication = $_POST['cpublication'] ?? "";
                $cstatus = $_POST['cstatus'] ?? "";

                if (!BookValidator::isValidTitle($ctitle)) {
                    $inputErrors['ctitle'] = "Invalid title. Please enter a valid title.";
                }

                if (!BookValidator::isValidAuthor($cauthor)) {
                    $inputErrors['cauthor'] = "Invalid author. Please enter a valid author.";
                }

                if (!BookValidator::isValidDescription($cdescription)) {
                    $inputErrors['cdescription'] = "Invalid description. Please enter a valid description.";
                }

                $checkISBN = BookValidator::isValidISBN($cisbn);

                if ($checkISBN != "Valid ISBN") {
                    $inputErrors['cisbn'] = $checkISBN;
                }

                if (!BookValidator::isValidGenre($cgenre)) {
                    $inputErrors['cgenre'] = "Invalid genre. Please enter a valid genre.";
                }

                if (!BookValidator::isValidPublisher($cpublisher)) {
                    $inputErrors['cpublisher'] = "Invalid publisher. Please enter a valid publisher.";
                }

                if (!BookValidator::isValidPublicationDate($cpublication)) {
                    $inputErrors['cpublication'] = "Invalid publication. Please enter a valid publication.";
                }

                if (!BookValidator::isValidStatus($cstatus)) {
                    $inputErrors['cstatus'] = "Invalid status. Please enter a valid status.";
                }

                if (empty($inputErrors)) {
                    $pdo = new PDO('mysql:host=localhost;dbname=LibrarySYS;charset=utf8', 'root', '');
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    $sql = "INSERT INTO Books (Title, Author, Description, ISBN, Genre, Publisher, PublicationDate, Status)
                            VALUES (:ctitle, :cauthor, :cdescription, :cisbn, :cgenre, :cpublisher, :cpublication, :cstatus)";

                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':ctitle', $ctitle);
                    $stmt->bindValue(':cauthor', $cauthor);
                    $stmt->bindValue(':cdescription', $cdescription);
                    $stmt->bindValue(':cisbn', $cisbn);
                    $stmt->bindValue(':cgenre', $cgenre);
                    $stmt->bindValue(':cpublisher', $cpublisher);
                    $stmt->bindValue(':cpublication', $cpublication);
                    $stmt->bindValue(':cstatus', $cstatus);

                    $stmt->execute();
                }
            } catch (PDOException $e) {
                $title = 'An error has occurred';
                $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
            }
        }
    }

    function fetchAllBooks(){
        try {
            $pdo = new PDO('mysql:host=localhost;dbname=LibrarySYS;charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            $sql = 'SELECT * FROM Books';
            $result = $pdo->prepare($sql); 
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
        } catch (PDOException $e) {  
            $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();  
            } 
    }
?>