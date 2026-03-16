<?php 
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once("config/config.php");

    $searchMember = $_POST['cSearchMember'] ?? $_SESSION['searchMember'] ?? '';
    $_SESSION['searchMember'] = $searchMember;

    if (isset($_POST['cSearchMember'])) {
        $_SESSION['loanBooks'] = [];
    }

    if (!isset($_SESSION['loanBooks'])) {
        $_SESSION['loanBooks'] = [];
    }

    if (isset($_POST['cISBN']) && !empty($_POST['cISBN'])) {
        if (count($_SESSION['loanBooks']) < 5) {
            $_SESSION['loanBooks'][] = $_POST['cISBN'];
        }
    }

    $members = $libraryService->searchMembers($searchMember);
    $allMembers = $libraryService->getAllMembers();
    $booksInLoan = $_SESSION['loanBooks'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Loans</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include_once("inc/navMenu.php"); ?>

    <div class="formContainer">
        <h2>Search Members</h2>

        <form action="processLoan.php" method="post">
            <input type="text" name="cSearchMember" id="cSearchMember" class="searchMember" placeholder="Search member...">
        </form>

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

    <div class="formContainer">
        <h2>Process Loan</h2>

        <?php if($searchMember && !empty($members)) :?>
            <?php $foundMember = $members[0]; ?>

            <div class="memberContainer">
                <div class="memberCardLeft">
                    <div class="memberIcon">
                        <i class="fa fa-user"></i>
                    </div>

                    <div class="memberCardInfo">
                        <h3 class="memberCardName"><?php echo $foundMember->getFirstName() . ' ' . $foundMember->getLastName() ?></h3>
                        <span class="memberCardId"><?php echo "ID:" . $foundMember->getId(); ?></span>
                    </div>
                </div>

                <div class="memberCardRight">
                    <span class="<?php echo $foundMember->getStatus() === 'A' ? "memberCardActiveStatus" : "memberCardInactiveStatus"?>"><?php echo $foundMember->getStatus() === 'A' ? "Active" : "Inactive"; ?></span>
                </div>
            </div>

            <hr>

            <h4>Books in Loan</h4>
            <?php foreach($booksInLoan as $book) : ?>
                <p><?php echo $book; ?></p>
            <?php endforeach; ?>

            <hr>

            <h4>New Loan</h4>

            <form action="processLoan.php" method="post">
                <input type="text" name="cISBN" id="cISBN" class="searchISBN" placeholder="Enter an ISBN...">
                <input type="submit" value="Confirm loan">
            </form>

            <hr>
        <?php endif; ?>
    </div>
</body>
</html>