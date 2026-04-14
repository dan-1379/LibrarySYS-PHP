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
    $error = "";

    try {
        $totalBooks = $libraryService->getTotalBooks();
        $totalActiveMembers = $libraryService->getTotalMembers("A");
        $totalInactiveMembers = $libraryService->getTotalMembers("I");
        $totalLoans = $libraryService->getTotalLoans();
        $totalFines = number_format($libraryService->getTotalFines(), 2);

        $recentLoans = $libraryService->getRecentLoans();

        $topBorrowers = $libraryService->getTopBorrowers();

        $fineOffender = $libraryService->getTopFineOffender();
    } catch (Exception $ex) {
        $error = "Failed to load dashboard data. Please try again later.";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Dashboard</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include_once("inc/navMenu.php"); ?>

    <main class="main">
        <?php if(!empty($error)) : ?>
            <div class="infoMessage">
                <i class="fa fa-warning"></i>
                <p><?php echo $error; ?></p>
            </div>
        <?php else : ?>
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
                            <span class="totalInfoValue">€<?php echo $totalFines; ?></span>
                            <p class="totalInfoSubHeading">outstanding</p>
                        </div>
                    </div>
                </div>

                <div class="recentsContainer">
                    <h2>Recent Activity</h2>
                    <p>Most recent loans in order</p>
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
                                <td>
                                    <div class="recentBookContainer">
                                        <i class="fa fa-book"></i>
                                        <?php echo htmlspecialchars($recent['book']->getTitle()); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="recentMemberContainer">
                                        <div class="recentMemberInitials">
                                            <?php echo htmlspecialchars($recent['member']->getFirstName()[0]) . htmlspecialchars($recent['member']->getLastName()[0]); ?>
                                        </div>
                                        <?php echo htmlspecialchars($recent['member']->getFirstName()) . " " . htmlspecialchars($recent['member']->getLastName()); ?>
                                    </div>
                                </td>
                                <td><?php echo htmlspecialchars($recent['loanDate']) ?></td>
                                <td><?php echo htmlspecialchars($recent['dueDate']) ?></td>
                                <td>
                                    <?php echo empty($recent['returnDate'])
                                        ? "<span class='loanedStatus'>Loaned</span>" 
                                        : "<span class='returnedStatus'>Returned</span>";  ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <div class="leaderboardContainer">
                    <h2>Top Borrowers</h2>
                    <p>Top 3 members by loan count</p>

                    <?php foreach($topBorrowers as $topBorrower) : ?>
                        <div class="memberContainer">
                            <div class="memberCardLeft">
                                <div class="memberIcon">
                                    <i class="fa fa-user"></i>
                                </div>
                                <div class="memberCardInfo">
                                    <h3 class="memberCardName"><?php echo htmlspecialchars($topBorrower["member"]->getFirstName()) . " " . htmlspecialchars($topBorrower["member"]->getLastName()); ?></h3>
                                    <span class="memberCardId"><?php echo htmlspecialchars($topBorrower["member"]->getAddressLine1()) . ", " . htmlspecialchars($topBorrower["member"]->getAddressLine2()) . ", " . htmlspecialchars($topBorrower["member"]->getCity()); ?></span>
                                </div>
                            </div>
                            <div class="memberCardCount">
                                <?php echo htmlspecialchars($topBorrower["loanCount"]); ?> Loans
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="leaderboardContainer">
                    <h2>Top Fine Offender</h2>
                    <p>DO NOT LOAN TO THIS PERSON</p>

                    <div class="memberContainer">
                        <div class="memberCardLeft">
                            <div class="memberIcon">
                                <i class="fa fa-user"></i>
                            </div>
                            <div class="memberCardInfo">
                                <h3 class="memberCardName"><?php echo htmlspecialchars($fineOffender["FirstName"]) . " " . htmlspecialchars($fineOffender["LastName"]); ?></h3>
                                <span class="memberCardId"><?php echo htmlspecialchars($fineOffender["AddressLine1"]) . ", " . htmlspecialchars($fineOffender["AddressLine2"]) . ", " . htmlspecialchars($fineOffender["City"]); ?></span>
                            </div>
                        </div>
                        <div class="memberCardCount">
                            €<?php echo htmlspecialchars($fineOffender["Total_Fine"]); ?> Fines
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>