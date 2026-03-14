<?php
    include("BookValidator.php");
    include("MemberValidator.php");
    $inputErrors = [];
    $success = "";

    function insertBookRecord() {
        global $inputErrors;
        global $success;

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
                    $success = "Book added successfully!";
                }
            } catch (PDOException $e) {
                $title = 'An error has occurred';
                $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
            }
        }
    }

    function fetchAllBooks(){
        try {
            // $pdo = new PDO('mysql:host=localhost;dbname=LibrarySYS;charset=utf8', 'root', '');
            $pdo = new PDO('mysql:host=127.0.0.1;dbname=LibrarySYS;charset=utf8', 'root', '');
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

    function getTotalBooks() {
        try {
            // $pdo = new PDO('mysql:host=localhost;dbname=LibrarySYS;charset=utf8', 'root', '');
            $pdo = new PDO('mysql:host=127.0.0.1;dbname=LibrarySYS;charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            $sql = 'SELECT COUNT(*) FROM Books';
            $result = $pdo->prepare($sql); 
            $result->execute(); 

            // https://stackoverflow.com/questions/58227521/how-to-get-count-of-rows-in-mysql-table-using-php
            return (int) $result->fetchColumn();
        } catch (PDOException $e) {  
            $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();  
            return 0;
        } 
    }

    function fetchAllMembers(){
        try {
            // $pdo = new PDO('mysql:host=localhost;dbname=LibrarySYS;charset=utf8', 'root', '');
            $pdo = new PDO('mysql:host=127.0.0.1;dbname=LibrarySYS;charset=utf8', 'root', '');
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 

            $sql = 'SELECT * FROM Members';
            $result = $pdo->prepare($sql); 
            $result->execute(); 

            while ($row = $result->fetch()) { 
                $statusText = ($row['Status'] === 'A') ? 'Active' : 'Inactive';

                echo "<tr>";
                echo "<td>{$row['MemberID']}</td>";
                echo "<td>{$row['FirstName']} {$row['LastName']}</td>";
                echo "<td>{$row['DOB']}</td>";
                echo "<td>{$row['Phone']}</td>";
                echo "<td>{$row['Email']}</td>";
                echo "<td>{$row['AddressLine1']}, {$row['AddressLine2']}, {$row['City']}</td>";
                echo "<td>{$row['County']}</td>";
                echo "<td>{$row['Eircode']}</td>";
                echo "<td>{$row['RegistrationDate']}</td>";
                echo "<td>$statusText</td>";
                echo "<td><div class='editMember'>";
                echo "<button onclick='showEditMenu(this)' class='editMemberButton'><i class='fa fa-edit'></i>EDIT</button>";
                echo "</div></td>";
                echo "<div class='deleteMember'>";
                echo "<td><button onclick = 'deleteMember(this)' class='deleteMemberButton'><i class='fa fa-trash-o'></i>DELETE</button></td>";
                echo "</div>";
                echo '</tr>'; 
            } 
        } catch (PDOException $e) {  
            $output = 'Unable to connect to the database server: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();  
        } 
    }

    function insertMemberRecord() {
        $inputErrors = [];
        $success = "";

        if (isset($_POST['updateMemberDetails'])) {
                $cfirstName = $_POST['cFirstName'] ?? "";
                $clastName = $_POST['cLastName'] ?? "";
                $cDOB = $_POST['cDOB'] ?? "";
                $cPhone = $_POST['cPhone'] ?? "";
                $cEmail = $_POST['cEmail'] ?? "";
                $cAddressLine1 = $_POST['cAddressLine1'] ?? "";
                $cAddressLine2 = $_POST['cAddressLine2'] ?? "";
                $cCity = $_POST['cCity'] ?? "";
                $cCounty = $_POST['cCounty'] ?? "";
                $cEircode = $_POST['cEircode'] ?? "";
                $cRegistrationDate = $_POST['cRegistrationDate'] ?? "";
                $cStatus = $_POST['cStatus'] ?? "";

                if (!MemberValidator::isValidName($cfirstName)) {
                    $inputErrors['cFirstName'] = "Invalid first name.";
                }

                if (!MemberValidator::isValidName($clastName)) {
                    $inputErrors['cLastName'] = "Invalid last name.";
                }

                if (!MemberValidator::isValidDOB($cDOB)) {
                    $inputErrors['cDOB'] = "Invalid DOB.";
                }

                $checkPhone = MemberValidator::isValidPhone($cPhone);

                if ($checkPhone != "valid") {
                    $inputErrors['cPhone'] = $checkPhone;
                }

                $checkEmail = MemberValidator::IsValidEmail($cEmail);

                if ($checkEmail != "valid") {
                    $inputErrors['cEmail'] = $checkEmail;
                }

                if (!MemberValidator::isValidAddressLine($cAddressLine1)) {
                    $inputErrors['cAddressLine1'] = "Invalid address line 1";
                }

                if (!MemberValidator::isValidAddressLine($cAddressLine2)) {
                    $inputErrors['cAddressLine2'] = "Invalid address line 2";
                }

                if (!MemberValidator::isValidCity($cCity)) {
                    $inputErrors['cCity'] = "Invalid city";
                }

                if (!MemberValidator::isValidCounty($cCounty)) {
                    $inputErrors['cCounty'] = "Invalid county";
                }

                $checkEircode = MemberValidator::isValidEircode($cEircode);

                if ($checkEircode != "valid") {
                    $inputErrors['cEircode'] = $checkEircode;
                }
                
                if (empty($inputErrors)) {
                    try {
                        // $pdo = new PDO('mysql:host=localhost;dbname=LibrarySYS;charset=utf8', 'root', '');
                        $pdo = new PDO('mysql:host=127.0.0.1;dbname=LibrarySYS;charset=utf8', 'root', '');
                        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                        $sql = "UPDATE Members SET FirstName = :cfirstName, LastName = :clastName, DOB = :cDOB, Phone = :cPhone,
                                Email = :cEmail, AddressLine1 = :cAddressLine1, AddressLine2 = :cAddressLine2, City = :cCity,
                                County = :cCounty, Eircode = :cEircode, RegistrationDate = :cRegistrationDate,Status = :cStatus
                                WHERE MemberID = :cMemberID";

                        $stmt = $pdo->prepare($sql);
                        $stmt->bindValue(":cMemberID", $_POST['cMemberID']);
                        $stmt->bindValue(':cfirstName', $cfirstName);
                        $stmt->bindValue(':clastName', $clastName);
                        $stmt->bindValue(':cDOB', $cDOB);
                        $stmt->bindValue(':cPhone', $cPhone);
                        $stmt->bindValue(':cEmail', $cEmail);
                        $stmt->bindValue(':cAddressLine1', $cAddressLine1);
                        $stmt->bindValue(':cAddressLine2', $cAddressLine2);
                        $stmt->bindValue(':cCity', $cCity);
                        $stmt->bindValue(':cCounty', $cCounty);
                        $stmt->bindValue(':cEircode', $cEircode);
                        $stmt->bindValue(':cRegistrationDate', $cRegistrationDate);
                        $stmt->bindValue(':cStatus', $cStatus);

                        $stmt->execute();
                        $success = "Member updated successfully!";
            } catch (PDOException $e) {
                $title = 'An error has occurred';
                $output = 'Database error: ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine();
            }
        }
    }
        return ['errors' => $inputErrors, 'success' => $success];
    }

    // function searchMemberRecords() {
    //     if (isset($_POST['updateMemberDetails'])) {}
    // }
?>