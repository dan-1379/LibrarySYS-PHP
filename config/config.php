<?php 
// Configuration files required
require_once("db.php");

require_once("Class/Book.php");
require_once("Class/Member.php");

require_once("Class/BookRepository.php");
require_once("Class/MemberRepository.php");

require_once("Class/LibraryService.php");

require_once("BookValidator.php");
require_once("MemberValidator.php");

session_start();
$_SESSION['username'] = "manager";

$bookRepository = new BookRepository($pdo);
$memberRepository = new MemberRepository($pdo);

$libraryService = new LibraryService($bookRepository,$memberRepository);
?>