<?php 
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once("config/config.php");

    $searchMember = $_POST['cSearchMember'] ?? '';
    $searchBook = $_POST['cSearchISBN'] ?? '';

    $member = null;
    $error= null;
    $book = null;

    if (!empty($searchMember)) {
        try {
            $member = $libraryService->searchMembers($searchMember);
            $_SESSION['Member'] = $member;
        } catch (InvalidArgumentException $ex) {
            $error = $ex->getMessage();
        }
    }

    if (!empty($searchBook)) {
        try {
            $book = $libraryService->searchBooks($searchBook);
            $_SESSION['BooksInCart'][] = $book;
        } catch (InvalidArgumentException $ex) {
            $error = $ex->getMessage();
        }
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

            <?php if($error) : ?>
                <div class="errorOutput">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span class="errorMessage"><?php echo $error; ?></span>
                </div>
            <?php endif; ?>

            <?php if($member) : ?>
            <div class="memberContainer">
                    <div class="memberCardLeft">
                        <div class="memberIcon">
                            <i class="fa fa-user"></i>
                        </div>

                        <div class="memberCardInfo">
                            <h3 class="memberCardName"><?php echo $member->getFirstName() . ' ' . $member->getLastName() ?></h3>
                            <span class="memberCardId"><?php echo "ID:" . $member->getId(); ?></span>
                        </div>
                    </div>

                    <div class="memberCardRight">
                        <span class="<?php echo $member->getStatus() === 'A' ? "memberCardActiveStatus" : "memberCardInactiveStatus"?>"><?php echo $member->getStatus() === 'A' ? "Active" : "Inactive"; ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="formContainer">
            <h2>Search Books</h2>

            <form action="processLoan.php" method="post">
                <input type="text" name="cSearchISBN" id="cSearchISBN" class="searchISBN" placeholder="Search ISBN...">
            </form>

            <?php if($book) : ?>
                <div class="memberContainer">
                    <div class="memberCardLeft">
                        <div class="memberIcon">
                            <i class="fa fa-book"></i>
                        </div>

                        <div class="memberCardInfo">
                            <h3 class="memberCardName"><?php echo $book->getTitle() ?></h3>
                            <span class="memberCardId"><?php echo "ID:" . $book->getISBN(); ?></span>
                        </div>
                    </div>

                    <div class="memberCardRight">
                        <span class="<?php echo $book->getStatus() === 'A' ? "memberCardActiveStatus" : "memberCardInactiveStatus"?>"><?php echo $book->getStatus() === 'A' ? "Active" : "Inactive"; ?></span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <!-- <main class="processLoanMain">
        <div class="formContainerInfo">
            <div class="formContainerMember">
                <div class="formContainerMemberHeader">
                    <h2>Search Members</h2>

                    <form action="processLoan.php" method="post">
                        <input type="text" name="cSearchMember" id="cSearchMember" class="searchMember" placeholder="Search member...">
                    </form>
                </div>

                <?php foreach($allMembers as $member) : ?>
                    <div class="memberContainer">
                        <div class="memberCardLeft">
                            <div class="memberIcon">
                                <i class="fa fa-user"></i>
                            </div>

                            <div class="memberCardInfo">
                                <h3 class="memberCardName"><?php echo $member->getFirstName() . ' ' . $member->getLastName() ?></h3>
                                <span class="memberCardId"><?php echo "ID:" . $member->getId(); ?></span>
                            </div>
                        </div>

                        <div class="memberCardRight">
                            <span class="<?php echo $member->getStatus() === 'A' ? "memberCardActiveStatus" : "memberCardInactiveStatus"?>"><?php echo $member->getStatus() === 'A' ? "Active" : "Inactive"; ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="formContainerBook">
                <h2>Search Books</h2>

                <form action="processLoan.php" method="post">
                    <input type="text" name="cSearchISBN" id="cSearchISBN" class="searchISBN" placeholder="Search ISBN...">
                </form>

                <?php foreach($allBooks as $book) : ?>
                    <div class="memberContainer">
                        <div class="memberCardLeft">
                            <div class="memberIcon">
                                <i class="fa fa-book"></i>
                            </div>

                            <div class="memberCardInfo">
                                <h3 class="memberCardName"><?php echo $book->getTitle() ?></h3>
                                <span class="memberCardId"><?php echo "ID:" . $book->getISBN(); ?></span>
                            </div>
                        </div>

                        <div class="memberCardRight">
                            <span class="<?php echo $book->getStatus() === 'A' ? "memberCardActiveStatus" : "memberCardInactiveStatus"?>"><?php echo $book->getStatus() === 'A' ? "Active" : "Inactive"; ?></span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="loanSummaryContainer">
            <h2>Loan Summary</h2>
            

            <h2>Member</h2>
            <hr>
            <?php if($members) : ?>
                <?php $member = $members[0]; ?>
                <div class="memberContainer">
                        <div class="memberCardLeft">
                            <div class="memberIcon">
                                <i class="fa fa-user"></i>
                            </div>

                            <div class="memberCardInfo">
                                <h3 class="memberCardName"><?php echo $member->getFirstName() . ' ' . $member->getLastName() ?></h3>
                                <span class="memberCardId"><?php echo "ID:" . $member->getId(); ?></span>
                            </div>
                        </div>

                        <div class="memberCardRight">
                            <span class="<?php echo $member->getStatus() === 'A' ? "memberCardActiveStatus" : "memberCardInactiveStatus"?>"><?php echo $member->getStatus() === 'A' ? "Active" : "Inactive"; ?></span>
                        </div>
                    </div>
            <?php endif; ?>

            <h2>Books</h2>
            <hr>
            <?php if ($books) : ?>
                <div class="memberContainer">
                        <div class="memberCardLeft">
                            <div class="memberIcon">
                                <i class="fa fa-book"></i>
                            </div>

                            <div class="memberCardInfo">
                                <h3 class="memberCardName"><?php echo $book->getTitle() ?></h3>
                                <span class="memberCardId"><?php echo "ID:" . $book->getISBN(); ?></span>
                            </div>
                        </div>

                        <div class="memberCardRight">
                            <span class="<?php echo $book->getStatus() === 'A' ? "memberCardActiveStatus" : "memberCardInactiveStatus"?>"><?php echo $book->getStatus() === 'A' ? "Active" : "Inactive"; ?></span>
                        </div>
                    </div>
            <?php endif; ?>
        </div>
    </main> -->
</body>
</html>