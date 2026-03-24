<?php 
    require_once("config/config.php");
    
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    validateRoleForPage(['reception', 'manager']);

    $searchMember = $_POST['cSearchMember'] ?? '';
    $selectedBooks = $_POST['selectBook'] ?? [];

    $member = null;
    $memberError = null;
    
    $booksLoaned = [];

    if (!empty($searchMember)) {
        unset($_SESSION['SelectedBooks']);

        try {
            $member = $libraryService->searchMembers(htmlspecialchars($searchMember));

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
        foreach($_SESSION['SelectedBooks'] as $selectedBook) {
            $libraryService->processReturn();
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
            <form action="processReturn.php" method="post">
                <div class="formContainer">
                    <h2>Active Book Loans</h2>

                    <?php if(count($_SESSION['LoanedBooks']) != 0) : ?>
                        <p>Select all books to be returned</p>
                    <?php else : ?>
                        <p><?php echo $_SESSION['Member']->getFirstName() . " " . $_SESSION['Member']->getLastName(); ?> has no active loans</p>
                    <?php endif; ?>
                    <!-- Add all books here -->
                    <?php foreach($_SESSION['LoanedBooks'] as $book) : ?>
                        <div class="memberContainer">
                            <div class="memberCardLeft">
                                <div class="memberIcon">
                                    <i class="fa fa-book"></i>
                                </div>
                                <div class="memberCardInfo">
                                    <h3 class="memberCardName"><?php echo $book->getTitle(); ?></h3>
                                    <span class="memberCardId"><?php echo "ID:" . $book->getAuthor(); ?></span>
                                </div>
                            </div>

                            <div class="memberCardRight">
                                <input type="checkbox" name="selectBook[]" value="<?php echo $book->getId(); ?>" <?php echo in_array($book->getId(), $_SESSION["SelectedBooks"] ?? []) ? "checked" : ""; ?>>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <input type="submit" value="Return Books" name="processReturn">
            </form>

            <form action="processReturn.php" method="post">
                <input type="submit" value="Cancel Return" name="clearCart">
            </form>
        <?php endif; ?>
    </main>
</body>
</html>