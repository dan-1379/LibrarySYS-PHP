<?php 
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
</head>
<body>
    <?php include_once("inc/navMenu.php"); ?>

    <main class="main">
        <h1>Analytics Overview</h1>
        <p>Your products key performance metrics at a glance.</p>

        <div class="dataContainer">
            <div class="chartContainer">
                
            </div>

            <div class="totalsContainer">
                <div class="totalInfo">
                    <h2>Books</h2>
                    <span><?php echo $totalBooks; ?></span>
                    <p>in catalogue</p>
                </div>

                <div class="totalInfo">
                    <h2>Members</h2>
                    <span><?php echo $totalActiveMembers; ?></span>
                    <p>active</p>

                    <span><?php echo $totalInactiveMembers; ?></span>
                    <p>inactive</p>
                </div>

                <div class="totalInfo">
                    <h2>Loans</h2>
                    <span><?php echo $totalLoans; ?></span>
                    <p>current loans</p>
                </div>

                <div class="totalInfo">
                    <h2>Fines</h2>
                    <span><?php echo "€" . $totalFines; ?></span>
                    <p>total</p>
                </div>
            </div>

            <div class="recentsContainer">
                <table>
                    <tr>
                        <th>Book</th>
                        <th>Member</th>
                        <th>Borrowed Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>

                    <?php foreach($recentLoans as $recent) : ?>
                        <td><?php  ?></td>
                    <?php endforeach; ?>

                </table>
            </div>
        </div>
    </main>
</body>
</html>