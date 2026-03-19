<?php 
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once("config/config.php");

    $searchMember = $_POST['cSearchMember'] ?? '';
    $searchBook = $_POST['cSearchISBN'] ?? '';

    $member = null;
    $memberError = null;

    $book = null;
    $bookError = null;

    if (!empty($searchMember)) {
        try {
            $member = $libraryService->searchMembers($searchMember);
            $_SESSION['Member'] = $member;
        } catch (InvalidArgumentException $ex) {
            $memberError = $ex->getMessage();
        }
    }

    if (!empty($searchBook)) {
        try {
            $book = $libraryService->searchBooks($searchBook);

            if ($book === null) {
                $bookError = "No book found with that ISBN";
            } else if (in_array($book, $_SESSION['BooksInCart'] ?? [])) {
                $bookError = "This book has already been added to the loan";
            } else {
                $_SESSION['BooksInCart'][] = $book;
            }
        } catch (InvalidArgumentException $ex) {
            $bookError = $ex->getMessage();
        }
    }

    if (isset($_POST["clearCart"])) {
        unset($_SESSION['BooksInCart']);
        unset($_SESSION['Member']);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <?php include_once("inc/navMenu.php"); ?>

    <main class="processLoanMain">
        <div class="formContainer">
            <h2>Search Members</h2>

            <form action="processLoan.php" method="post">
                <input type="text" name="cSearchMember" id="cSearchMember" class="searchMember" placeholder="Search member by ID...">
            </form>

            <?php if($memberError) : ?>
                <div class="errorOutput">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span class="errorMessage"><?php echo $memberError; ?></span>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['Member'])) : ?>
                <div class="memberContainer">
                    <div class="memberCardLeft">
                        <div class="memberIcon">
                            <i class="fa fa-user"></i>
                        </div>

                        <div class="memberCardInfo">
                            <h3 class="memberCardName"><?php echo $_SESSION['Member']->getFirstName() . ' ' . $_SESSION['Member']->getLastName() ?></h3>
                            <span class="memberCardId"><?php echo "ID:" . $_SESSION['Member']->getId(); ?></span>
                        </div>
                    </div>

                    <div class="memberCardRight">
                        <span class="<?php echo $_SESSION['Member']->getStatus() === 'A' ? "memberCardActiveStatus" : "memberCardInactiveStatus"?>"><?php echo $_SESSION['Member']->getStatus() === 'A' ? "Active" : "Inactive"; ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <?php if(isset($_SESSION['Member'])) : ?>
            <div class="formContainer">
                <h2>Search Books</h2>

                <form action="processLoan.php" method="post">
                    <input type="text" name="cSearchISBN" id="cSearchISBN" class="searchISBN" placeholder="Search ISBN...">
                </form>

                <?php if($bookError) : ?>
                    <div class="errorOutput">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="errorMessage"><?php echo $bookError; ?></span>
                    </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['BooksInCart']) && !empty($_SESSION['BooksInCart'])) : ?>
                    <?php foreach($_SESSION['BooksInCart'] as $bookInCart) : ?>
                        <div class="memberContainer">
                            <div class="memberCardLeft">
                                <div class="memberIcon">
                                    <i class="fa fa-book"></i>
                                </div>
                                <div class="memberCardInfo">
                                    <h3 class="memberCardName"><?php echo $bookInCart->getTitle() ?></h3>
                                    <span class="memberCardId"><?php echo "ID:" . $bookInCart->getISBN(); ?></span>
                                </div>
                            </div>
                            <div class="memberCardRight">
                                <span class="<?php echo $bookInCart->getStatus() === 'A' ? "memberCardActiveStatus" : "memberCardInactiveStatus"?>"><?php echo $bookInCart->getStatus() === 'A' ? "Active" : "Inactive"; ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <?php if(isset($_SESSION['BooksInCart']) && !empty($_SESSION['BooksInCart'])) : ?>
                <div class="loanSummaryContainer">
                    <h2>Loan Summary</h2>

                    <div class="loanSummaryDetails">
                        <div class="loanSummaryBooks">
                            <h5><i class="fa fa-book"></i>Books (<?php echo count($_SESSION["BooksInCart"]) ?>)</h5>

                            <?php foreach($_SESSION['BooksInCart'] as $bookInCart) : ?>
                                <div class="loanSummaryBook">
                                    <p><?php echo $bookInCart->getTitle(); ?></p>
                                    <p><?php echo $bookInCart->getAuthor(); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="loanSummaryIcon">
                            <i class="fa fa-arrow-right"></i>
                        </div>

                        <div class="loanSummaryMember">
                            <h5><i class="fa fa-user"></i>Member</h5>
                            <p><?php echo $_SESSION['Member']->getFirstName() . ' ' . $_SESSION['Member']->getLastName() ?></p>
                            <p><?php echo $_SESSION['Member']->getAddressLine1() . ', ' . $_SESSION['Member']->getAddressLine2() . ', ' . $_SESSION['Member']->getCity() ?></p>
                        </div>
                    </div>
                </div>

                <form action="processLoan.php" method="post">
                    <input type="submit" value="Confirm Checkout (<?php echo count($_SESSION["BooksInCart"]) ?>)">
                </form>
            <?php endif; ?>

            <form action="processLoan.php" method="post">
                <input type="submit" value="Cancel Loan" name="clearCart">
            </form>
        <?php endif; ?>
    </main>
</body>
</html>