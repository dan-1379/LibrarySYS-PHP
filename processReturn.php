<!--
 * Title: Font Awesome Icons
 * Author: W3 Schools
 * Site: w3schools.com
 * Date: 09/03/26
 * Code Version: N/A
 * Availability: https://www.w3schools.com/icons/fontawesome_icons_intro.asp
 * Accessed: 09/03/26
 * Modified: No modifications made. Using icons.
-->

<?php 
    require_once("config/config.php");

    validateRoleForPage(['reception', 'manager']);

    $searchMember = $_POST['cSearchMember'] ?? '';
    $selectedBooks = $_POST['selectBook'] ?? [];

    $member = null;
    $memberError = null;
    
    $booksLoaned = [];

    $success = "";
    $returnError = "";

    if (!empty($searchMember)) {
        unset($_SESSION['SelectedBooks']);
        unset($_SESSION['FineErrors']);

        try {
            $member = $libraryService->searchMembers(trim($searchMember));

            if ($member == null) {
                $memberError = "This is not a valid member";
            } else if (!$member->isActive()) {
                $memberError = "This member is inactive.";
            } else {
                $_SESSION['Member'] = $member;

                $booksLoaned = $libraryService->getLoanedBooks($member->getId());
                $_SESSION['LoanedBooks'] = $booksLoaned;
            }

        } catch (InvalidArgumentException $ex) {
            $memberError = $ex->getMessage();
        }
    }

    if (isset($_POST["clearCart"])) {
        unset($_SESSION['Member']);
        unset($_SESSION['SelectedBooks']);
    }

    if (!empty($selectedBooks)) {
        $_SESSION['SelectedBooks'] = $selectedBooks;
    }

    if (isset($_POST["processReturn"])) {
        $selectedToReturn =  $_SESSION['SelectedBooks'] ?? [];

        if (empty($selectedToReturn)) {
            $returnError = "Please select at least one book to return";
        } else {
            $errors = $libraryService->processReturn($selectedToReturn);
            $_SESSION['FineErrors'] = $errors;

            $updatedLoans = $libraryService->getLoanedBooks($_SESSION['Member']->getId());
            $_SESSION['LoanedBooks'] = $updatedLoans;
            $success = "Books returned successfully";
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - Process Return</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <?php include_once("inc/navMenu.php") ?>

    <main class="processLoanMain">
        <div class="formContainer">
            <h2>Process Return - Search Member</h2>

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
                            <h3 class="memberCardName">
                                <?php echo htmlspecialchars($_SESSION['Member']->getFirstName()) . ' ' . htmlspecialchars($_SESSION['Member']->getLastName()); ?>
                            </h3>
                            <span class="memberCardId"><?php echo "ID: " . htmlspecialchars($_SESSION['Member']->getId()); ?></span>
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
            <form action="processReturn.php" method="post">
                <div class="formContainer">
                    <h2>Active Book Loans</h2>

                    <?php if (!empty($success)) : ?>
                        <div class="successOutput">
                            <i class="fa fa-check-circle"></i>
                            <span class="successText"><?php echo $success; ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($returnError)) : ?>
                        <div class="errorOutput">
                            <i class="fa fa-exclamation-triangle"></i>
                            <span class="errorMessage"><?php echo $returnError; ?></span>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($_SESSION['FineErrors'])) : ?>
                        <h2>Fines to be paid</h2>
                        <?php foreach($_SESSION['FineErrors'] as $fineError) : ?>
                            <div class="errorOutput">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="errorMessage"><?php echo $fineError; ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>

                    <?php if(count($_SESSION['LoanedBooks']) != 0) : ?>
                        <p>Select all books to be returned</p>
                    <?php else : ?>
                        <p><?php echo htmlspecialchars($_SESSION['Member']->getFirstName()) . " " . htmlspecialchars($_SESSION['Member']->getLastName()); ?> has no active loans</p>
                    <?php endif; ?>

                    <?php foreach($_SESSION['LoanedBooks'] as $book) : ?>
                        <?php $checkOverdue = new DateTime($book["due"]) < new DateTime(); ?>

                        <div class="memberContainer">
                            <div class="memberCardLeft">
                                <div class="memberIcon">
                                    <i class="fa fa-book"></i>
                                </div>
                                <div class="memberCardInfo">
                                    <h3 class="memberCardName"><?php echo htmlspecialchars($book["book"]->getTitle()); ?>
                                        (<span class="<?php echo $checkOverdue ? 'overdueLabel' : 'notoverdueLabel';?>">
                                            <?php echo $checkOverdue ? "OVERDUE" : "NOT OVERDUE"; ?>
                                        </span>)
                                    </h3>
                                    <span class="memberCardId"><?php echo "ID: " . htmlspecialchars($book["book"]->getISBN()); ?></span>
                                </div>
                            </div>

                            <div class="memberCardRight">
                                <input type="checkbox" name="selectBook[]" value="<?php echo htmlspecialchars($book["book"]->getId()); ?>" 
                                    <?php echo in_array($book["book"]->getId(), $_SESSION["SelectedBooks"] ?? []) ? "checked" : ""; ?>
                                >
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if(!empty($_SESSION['LoanedBooks'])) : ?>
                    <input type="submit" value="Return Books" name="processReturn">
                <?php endif; ?>
            </form>

            <?php if(count($_SESSION['LoanedBooks']) != 0) : ?>
                <form action="processReturn.php" method="post">
                    <input type="submit" value="Cancel Return" name="clearCart">
                </form>
            <?php else : ?>
                <form action="processReturn.php" method="post">
                    <input type="submit" value="OK" name="clearCart">
                </form>
            <?php endif; ?>
        <?php endif; ?>
    </main>
</body>
</html>