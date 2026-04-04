<?php 
    require_once("config/config.php");

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1); 
    error_reporting(E_ALL);

    $inputErrors = [];

    if (isset($_POST["submitLoanDetails"])) {
        $loanID = (int) $_POST["cLoanID"];
        $loanDate = new DateTime($_POST["cLoanDate"]);
        $dueDate = new DateTime($_POST["cDueDate"]);
        $memberID = (int) $_POST["cMemberID"];

        $loan = new Loan($loanDate, $dueDate, $memberID, $loanID);
        $inputErrors = $libraryService->updateLoanDetails($loan);
    }

    $loans = $libraryService->getAllLoans();
    $loanItems = $libraryService->getAllLoanItems();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Records</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <?php include_once("inc/navMenu.php"); ?>

    <main class="main">
        <h1>Library Records</h1>
        <p>All loan and loan item records.</p>
        
        <div class="dataContainer">
            <div class="recentsContainer">
                <h2>Loans</h2>
                <table class="viewLoansTable">
                    <tr>
                        <th>LoanID</th>
                        <th>LoanDate</th>
                        <th>DueDate</th>
                        <th>MemberID</th>
                        <th>EDIT</th>
                    </tr>

                    <?php foreach($loans as $loan) : ?>
                        <tr>
                            <td><?php echo $loan->getLoanID(); ?></td>
                            <td><?php echo $loan->getLoanDate()->format("Y-m-d"); ?></td>
                            <td><?php echo $loan->getDueDate()->format("Y-m-d"); ?></td>
                            <td><?php echo $loan->getMemberID(); ?></td>
                            <td>
                                <div class="editLoan">
                                    <button onclick='showEditLoan(this)' class='editMemberButton'><i class='fa fa-edit'></i>EDIT</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="recentsContainer">
                <h2>LoanItems</h2>

                <table class="viewLoansTable">
                    <tr>
                        <th>LoanID</th>
                        <th>BookID</th>
                        <th>ReturnDate</th>
                    </tr>

                    <?php foreach($loanItems as $loanItem) : ?>
                        <tr>
                            <td><?php echo $loanItem->getLoanID(); ?></td>
                            <td><?php echo $loanItem->getBookID(); ?></td>
                            <td><?php echo $loanItem->getReturnDate() ? $loanItem->getReturnDate()->format("Y-m-d") : "-"; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </main>

    <div class="contentOverlay" id="loanOverlay" onclick="closeEditLoan()"></div>

    <div class="formContainerUpdate" id="updateLoanForm">
        <form action="records.php" method="post">
            <div class="formGroup">
                <label for="cLoanID">LoanID</label>
                <input type="text" name="cLoanID" id="cLoanID" readonly>
            </div>

            <div class="formGroup">
                <label for="cLoanDate">Loan Date</label>
                <input type="date" name="cLoanDate" id="cLoanDate">
            </div>

            <div class="formGroup">
                <label for="cDueDate">Due Date</label>
                <input type="date" name="cDueDate" id="cDueDate">
            </div>

            <div class="formGroup">
                <label for="cMemberID">MemberID</label>
                <input type="text" name="cMemberID" id="cMemberID" readonly>
            </div>

            <div class="formGroup">
                <input type="button" value="Cancel" onclick="closeEditLoan()">
                <input type="submit" value="Update Loan" name="submitLoanDetails" id="submitLoanDetails">
            </div>
        </form>
    </div>

    <script src="public/js/script.js"></script>
</body>
</html>