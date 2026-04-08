<?php 
    require_once("config/config.php");
    validateRoleForPage(['reception', 'manager']);

    $searchMember = $_POST['cSearchMember'] ?? '';
    $searchBook = $_POST['cSearchISBN'] ?? '';

    $member = null;
    $memberError = null;

    $book = null;
    $bookError = null;

    if (!empty($searchMember)) {
        try {
            $member = $libraryService->searchMembers(trim($searchMember));

            if ($member == null) {
                $memberError = "This is not a valid member";
            } else if (!$member->isActive()) {
                $memberError = "This member is inactive.";
            } else if ($libraryService->hasOverdueBooks($member->getId()) > 0) {
                $memberError = "This member has overdue books";
            } else if ($libraryService->getUnpaidMemberFine($member->getId()) > 0) {
                $memberError = "This member has outstanding fines.";
            } else {
                $_SESSION['Member'] = $member;
            }

        } catch (InvalidArgumentException $ex) {
            $memberError = $ex->getMessage();
        }
    }

    if (!empty($searchBook)) {
        try {
            $book = $libraryService->searchBooks(trim($searchBook));

            if ($book === null) {
                $bookError = "No book found with that ISBN";
            } else if (in_array($book, $_SESSION['BooksInCart'] ?? [])) {
                $bookError = "This book has already been added to the loan";
            } else if (!$book->isAvailable()) {
                $bookError = "This book is not currently available.";
            } else if (count($_SESSION["BooksInCart"] ?? []) >= MAX_BOOKS_PER_LOAN) {
                $bookError = "You cannot add more than " . MAX_BOOKS_PER_LOAN . " books to a loan";
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

    if (isset($_POST["removeBook"])) {
        $bookToRemove = $_POST["removeBook"];

        foreach($_SESSION["BooksInCart"] as $index => $book) {
            if ($book->getISBN() == $bookToRemove) {
                array_splice($_SESSION["BooksInCart"], $index, 1);
                break;
            }
        }
    }

    if (isset($_POST["confirmProcessLoan"])) {
        $member = $_SESSION["Member"];

        if (!$member->isActive()) {
            $memberError = "Cannot proceed with loan. Member is inactive.";
        } else if ($libraryService->hasOverdueBooks($member->getId()) > 0) {
            $memberError = "Cannot proceed with loan. Member has overdue books.";
        } else if ($libraryService->getUnpaidMemberFine($member->getId()) > 0) {
            $memberError = "Cannot proceed with loan. Member has outstanding fines.";
        } else if ($libraryService->getCurrentLoanCount($member->getId()) + count($_SESSION["BooksInCart"]) > MAX_BOOKS_PER_LOAN) {
            $memberError = "Cannot proceed with loan. Member has reached the maximum loan limit of " . MAX_BOOKS_PER_LOAN . " books.";
        } else {
            $loanDate = new DateTime();
            $dueDate = (new DateTime())->modify("+" . LOAN_LENDING_PERIOD . " days");
            $loan = new Loan($loanDate, $dueDate, $member->getId());

            $_SESSION["LoanDate"] = $loanDate->format("Y-m-d");
            $_SESSION["DueDate"] = $dueDate->format("Y-m-d");

            $libraryService->processLoan($loan, $_SESSION["BooksInCart"]);
            header("Location: loanConfirmation.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - Process Loan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <?php include_once("inc/navMenu.php"); ?>

    <main class="processLoanMain">
        <div class="formContainer">
            <h2>Process Loan - Search Member</h2>

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
                            <h3 class="memberCardName">
                                <?php echo htmlspecialchars($_SESSION['Member']->getFirstName()) . ' ' . htmlspecialchars($_SESSION['Member']->getLastName()); ?>
                            </h3>
                            <span class="memberCardId">
                                <?php echo "ID:" . htmlspecialchars($_SESSION['Member']->getId()); ?>
                            </span>
                        </div>
                    </div>

                    <div class="memberCardRight">
                        <span class="<?php echo $_SESSION['Member']->getStatus() === 'A' ? "memberCardActiveStatus" : "memberCardInactiveStatus"?>">
                            <?php echo $_SESSION['Member']->getStatus() === 'A' ? "Active" : "Inactive"; ?>
                        </span>
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
                                    <h3 class="memberCardName"><?php echo htmlspecialchars($bookInCart->getTitle()) ?></h3>
                                    <span class="memberCardId"><?php echo "ISBN: " . htmlspecialchars($bookInCart->getISBN()); ?></span>
                                </div>
                            </div>
                            <div class="memberCardRight">
                                <!-- <span class="<?php echo $bookInCart->getStatus() === 'A' ? "memberCardActiveStatus" : "memberCardInactiveStatus"?>">
                                    <?php echo $bookInCart->getStatus() === 'A' ? "Active" : "Inactive"; ?>
                                </span> -->

                                <form action="processLoan.php" method="post">
                                    <input type="hidden" name="removeBook" value="<?php echo htmlspecialchars($bookInCart->getISBN()); ?>">
                                    <button type="submit" class="deleteMemberButton"><i class='fa fa-trash-o'></i>Remove</button>
                                </form>
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
                                    <p><?php echo htmlspecialchars($bookInCart->getTitle()); ?></p>
                                    <p><?php echo htmlspecialchars($bookInCart->getAuthor()); ?></p>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div class="loanSummaryIcon">
                            <i class="fa fa-arrow-right"></i>
                        </div>

                        <div class="loanSummaryMember">
                            <h5><i class="fa fa-user"></i>Member</h5>
                            <p>
                                <?php echo htmlspecialchars($_SESSION['Member']->getFirstName()) . ' ' . htmlspecialchars($_SESSION['Member']->getLastName()); ?>
                            </p>
                            <p>
                                <?php echo htmlspecialchars($_SESSION['Member']->getAddressLine1()) . ', ' . htmlspecialchars($_SESSION['Member']->getAddressLine2()) . ', ' . 
                                htmlspecialchars($_SESSION['Member']->getCity()); ?>
                            </p>
                        </div>
                    </div>
                </div>

                <form action="processLoan.php" method="post">
                    <input type="submit" value="Confirm Checkout (<?php echo count($_SESSION["BooksInCart"]) ?>)" name="confirmProcessLoan">
                </form>
            <?php endif; ?>

            <form action="processLoan.php" method="post">
                <input type="submit" value="Cancel Loan" name="clearCart">
            </form>
        <?php endif; ?>
    </main>
</body>
</html>