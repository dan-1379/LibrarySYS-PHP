<?php 
    require_once("config/config.php");
    validateRoleForPage(['reception', 'manager']);

    if (isset($_POST["deleteFine"])) {
        $fineID = (int) $_POST["cfineID"];
        $libraryService->deleteFine($fineID);
    }

    if (isset($_POST["payFine"])) {
        $fineID = (int) $_POST["cfineID"];
        $libraryService->alterFineStatus($fineID);
    }

    $fines = $libraryService->getAllFines();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library - Process Fine</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <?php include_once("inc/navMenu.php"); ?>

    <table class="viewBookTable">
        <tr>
            <th>ID</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Loan ID</th>
            <th>Book ID</th>
            <th>Pay</th>
            <th>Forgive</th>
        </tr>

        <?php foreach($fines as $fine) :  ?>
            <tr>
                <td><?php echo $fine->getFineID(); ?></td>
                <td><?php echo htmlspecialchars(number_format($fine->getFineAmount(), 2)); ?></td>
                <td><?php echo $fine->getStatus() === "U" ? "Unpaid" : "Paid"; ?></td>
                <td><?php echo $fine->getLoanID(); ?></td>
                <td><?php echo $fine->getBookID(); ?></td>

                <?php if ($fine->getStatus() === "U") : ?>
                    <td>
                        <form action="processFine.php" method="post">
                            <div class='payFine'>
                                <input type="hidden" name="cfineID" value="<?php echo $fine->getFineID(); ?>">
                                <button class='payFineButton' name="payFine"><i class="fa fa-credit-card"></i>PAY FINE</button>
                            </div>
                        </form>
                    </td>
                <?php else : ?>
                    <td>
                        Fine Paid
                    </td>
                <?php endif; ?>

                <td>
                    <form action="processFine.php" method="post">
                        <div class='deleteFine'>
                            <input type="hidden" name="cfineID" value="<?php echo $fine->getFineID(); ?>">
                            <button class='deleteFineButton' name="deleteFine"><i class='fa fa-trash-o'></i>FORGIVE FINE</button>
                        </div>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>