<?php
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