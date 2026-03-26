<?php 
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once("config/config.php");
    $totalBooks = $libraryService->getTotalBooks();

    $totalActiveMembers = $libraryService->getTotalMembers("A");
    $totalInactiveMembers = $libraryService->getTotalMembers("I");

    $totalLoans = $libraryService->getTotalLoans();

    $totalFines = number_format($libraryService->getTotalFines(), 2);

    $recentLoans = $libraryService->getRecentLoans();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include_once("inc/navMenu.php"); ?>

    <main class="main">
        <h1>Analytics Overview</h1>
        <p>Your products key performance metrics at a glance.</p>

        <div class="dataContainer">
            <div class="totalsContainer">
                <div class="totalInfo">
                    <div class="totalInfoBookIcon">
                        <i class="fa fa-book"></i>
                    </div>

                    <div class="totalInfoBody">
                        <p class="totalInfoTitle">Books</p>
                        <span class="totalInfoValue"><?php echo $totalBooks; ?></span>
                        <p class="totalInfoSubHeading">in catalogue</p>
                    </div>
                </div>

                <div class="totalInfo">
                    <div class="totalInfoMemberIcon">
                        <i class="fa fa-user"></i>
                    </div>

                    <div class="totalInfoBody">
                        <p class="totalInfoTitle">Active Members</p>
                        <span class="totalInfoValue"><?php echo $totalActiveMembers; ?></span>
                        <p class="totalInfoSubHeading"><?php echo $totalInactiveMembers; ?> inactive</p>
                    </div>
                </div>

                <div class="totalInfo">
                    <div class="totalInfoLoanIcon">
                        <i class="fa fa-cart-plus"></i>
                    </div>

                    <div class="totalInfoBody">
                        <p class="totalInfoTitle">Current Loans</p>
                        <span class="totalInfoValue"><?php echo $totalLoans; ?></span>
                        <p class="totalInfoSubHeading">active loans</p>
                    </div>
                </div>

                <div class="totalInfo">
                    <div class="totalInfoFineIcon">
                        <i class="fa fa-exclamation-triangle"></i>
                    </div>

                    <div class="totalInfoBody">
                        <p class="totalInfoTitle">Total Fines</p>
                        <span class="totalInfoValue"><?php echo $totalFines; ?></span>
                        <p class="totalInfoSubHeading">outstanding</p>
                    </div>
                </div>
            </div>

            <div class="recentsContainer">
                <h2>Recent Activity</h2>
                <table>
                    <tr>
                        <th>Book</th>
                        <th>Member</th>
                        <th>Borrowed Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>

                    <?php foreach($recentLoans as $recent) : ?>
                        <tr>
                            <td><?php echo $recent['book'] ?></td>
                            <td><?php echo $recent['member'] ?></td>
                            <td><?php echo $recent['loanDate'] ?></td>
                            <td><?php echo $recent['dueDate'] ?></td>
                            <td><?php echo empty($recent['ReturnDate']) ? 'Loaned' : 'Returned' ?></td>
                        </tr>
                    <?php endforeach; ?>

                </table>
            </div>
        </div>
    </main>
</body>
</html>