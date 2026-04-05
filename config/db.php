<?php 
    try {
        // College
        // $pdo = new PDO('mysql:host=localhost;dbname=LibrarySYS;charset=utf8', 'root', '');

        // Home
        $pdo = new PDO('mysql:host=127.0.0.1;dbname=LibrarySYS;charset=utf8', 'root', '');
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Unable to connect to the database. Please try again later.");
    }
?>