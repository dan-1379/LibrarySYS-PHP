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
    require_once("class/AuthenticationRepository.php");

    require_once("class/LibraryService.php");

    require_once("class/BookValidator.php");
    require_once("class/MemberValidator.php");
    require_once("class/AuthenticationValidator.php");

    session_start();

    $publicPage = ['login.php'];
    $currentPage = basename($_SERVER['PHP_SELF']);  // full path of file being run stripped by basename down to file name

    if (!in_array($currentPage, $publicPage) && !isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    } 

    function validateRoleForPage(array $validRole) {
        if (!in_array($_SESSION['username'], $validRole)) {
            header("Location: index.php");
            exit();
        }
    }

    define("LOAN_LENDING_PERIOD", 5);
    define("MAX_BOOKS_PER_LOAN", 5);
    define("FINE_RATE_PER_DAY", 0.20);

    $bookRepository = new BookRepository($pdo);
    $memberRepository = new MemberRepository($pdo);
    $loanRepository = new LoanRepository($pdo);
    $fineRepository = new FineRepository($pdo);
    $authRepository = new AuthenticationRepository($pdo);

    $libraryService = new LibraryService($bookRepository,$memberRepository, $loanRepository, $fineRepository, $authRepository);
?>