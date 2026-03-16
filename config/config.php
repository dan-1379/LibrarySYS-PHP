<?php 
    // Configuration files required
    require_once("db.php");

    require_once("class/Book.php");
    require_once("class/Member.php");

    require_once("class/BookRepository.php");
    require_once("class/MemberRepository.php");

    require_once("class/LibraryService.php");

    require_once("BookValidator.php");
    require_once("MemberValidator.php");

    session_start();
    $_SESSION['username'] = "manager";

    $bookRepository = new BookRepository($pdo);
    $memberRepository = new MemberRepository($pdo);

    $libraryService = new LibraryService($bookRepository,$memberRepository);
?>