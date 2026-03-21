<?php 
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once("config/config.php");

    if (!isset($_SESSION['Member'])) {
        header("Location: processLoan.php");
        exit();
    }

    if (isset($_POST["processAnotherLoan"])) {
        unset($_SESSION['BooksInCart']);
        unset($_SESSION['Member']);
        unset($_SESSION['LoanDate']);
        unset($_SESSION['DueDate']);
        header("Location: processLoan.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Confirmation</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include_once("inc/navMenu.php"); ?>
    
    <div class="content">
        <div class="successMessage">
            <div class="successMessageIcon">
                <i class="fa fa-check"></i>
            </div>

            <div class="successMessageText">
                <h3>Loan processed successfully</h3>
            </div>
        </div>

        <div class="datesContainer">
            <div class="loanDate">
                <h4>Loan Date</h4>
                <p><?php echo $_SESSION["LoanDate"]; ?></p>
            </div>

            <div class="dueDate">
                <h4>Due Date</h4>
                <p><?php echo $_SESSION["DueDate"]; ?></p>
            </div>
        </div>

        <div class="memberInfo">
            <h4>Member</h4>
            <div class="memberContainer">
                <div class="memberCardLeft">
                    <div class="memberIcon">
                        <i class="fa fa-user"></i>
                    </div>

                    <div class="memberCardInfo">
                        <h3 class="memberCardName"><?php echo $_SESSION["Member"]->getFirstName(); ?></h3>
                        <span class="memberCardId"><?php echo $_SESSION["Member"]->getId() ?></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="booksContainer">
            <h4>Books Loaned (<?php echo count($_SESSION["BooksInCart"]) ?>)</h4>

            <?php foreach($_SESSION["BooksInCart"] as $bookInCart) : ?>
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
                </div>
            <?php endforeach; ?>
        </div>

        <div class="returnWarning">
            <i class="fa fa-warning"></i>
            <p>Please return all books by <span><?php echo $_SESSION["DueDate"] ?></span> to avoid late fines.</p>
        </div>

        <div class="actionButtons">
            <div class="processLoan">
                <form action="loanConfirmation.php" method="post">
                    <input type="submit" value="Process another loan" name="processAnotherLoan">
                </form>
            </div>

            <div class="printReceipt">
                <form action="receipt.php" method="post">
                    <input type="submit" value="Print Receipt">
                </form>
            </div>
        </div>
    </div>
</body>
</html>