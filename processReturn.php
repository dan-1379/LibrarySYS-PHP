<?php 
    require_once("config/config.php");
    
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    validateRoleForPage(['reception', 'manager']);

    $searchMember = $_POST['cSearchMember'] ?? '';

    $member = null;
    $memberError = null;
    
    $booksLoaned = [];

    if (!empty($searchMember)) {
        try {
            $member = $libraryService->searchMembers(htmlspecialchars($searchMember));

            if ($member == null) {
                $memberError = "This is not a valid member";
            } else if (!$member->isActive()) {
                $memberError = "This member is inactive.";
            } else {
                $_SESSION['Member'] = $member;
                $booksLoaned = $libraryService->getLoanedBooks($member->getId());
            }

        } catch (InvalidArgumentException $ex) {
            $memberError = $ex->getMessage();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include_once("inc/navMenu.php") ?>

    <main class="processLoanMain">
        <div class="formContainer">
            <h2>Search Members</h2>

            <form action="processReturn.php" method="post">
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
                <h2>Active Book Loans</h2>

                <!-- Add all books here -->
                <?php foreach($booksLoaned as $book) : ?>
                    <div class="memberContainer">
                            <div class="memberCardLeft">
                                <div class="memberIcon">
                                    <i class="fa fa-book"></i>
                                </div>
                                <div class="memberCardInfo">
                                    <h3 class="memberCardName"><?php echo $book->getLoanID(); ?></h3>
                                    <span class="memberCardId"><?php echo "ID:" . $book->getBookID(); ?></span>
                                </div>
                            </div>
                            <div class="memberCardRight">
                                <span class="<?php echo $book->getStatus() === 'A' ? "memberCardActiveStatus" : "memberCardInactiveStatus"?>"><?php echo $book->getStatus() === 'A' ? "Active" : "Inactive"; ?></span>
                            </div>
                        </div>
                <?php endforeach; ?>
            </div>

            <!-- <?php if(isset($_SESSION['BooksInCart']) && !empty($_SESSION['BooksInCart'])) : ?>
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
                    <input type="submit" value="Confirm Checkout (<?php echo count($_SESSION["BooksInCart"]) ?>)" name="confirmProcessLoan">
                </form>
            <?php endif; ?> -->

            <form action="processReturn.php" method="post">
                <input type="submit" value="Cancel Return" name="clearCart">
            </form>
        <?php endif; ?>
    </main>
</body>
</html>