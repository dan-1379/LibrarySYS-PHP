<?php 
    // Configuration files required
    require_once("db.php");

    require_once("class/Book.php");
    require_once("class/Member.php");
    require_once("class/Fine.php");
    require_once("class/Loan.php");
    require_once("class/LoanItem.php");

    require_once("class/BookRepository.php");
    require_once("class/MemberRepository.php");
    require_once("class/FineRepository.php");
    require_once("class/LoanRepository.php");

    require_once("class/LibraryService.php");

    require_once("class/BookValidator.php");
    require_once("class/MemberValidator.php");

    session_start();
    $_SESSION['username'] = "manager";

    define("LOAN_LENDING_PERIOD", 5);
    define("MAX_BOOKS_PER_LOAN", 5);
    define("FINE_RATE_PER_DAY", 0.20);

    $bookRepository = new BookRepository($pdo);
    $memberRepository = new MemberRepository($pdo);
    $loanRepository = new LoanRepository($pdo);
    $fineRepository = new FineRepository($pdo);

    $libraryService = new LibraryService($bookRepository,$memberRepository, $loanRepository, $fineRepository);
?>