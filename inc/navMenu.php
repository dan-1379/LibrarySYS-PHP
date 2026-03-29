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
    if (!isset($_SESSION['username'])) {
        header("Location: login.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibrarySYS</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <aside>
        <div class="logo">
            <h1>Killorglin Library</h1>
            <p>Management System</p>
            <i class="fa fa-angle-left" onclick="toggleAside()"></i>

            <div class="systemUserContainer">
                <div class="userCardLeft">
                    <div class="userIcon">
                        <i class="fa fa-user"></i>
                    </div>

                    <div class="userCardInfo">
                        <h3 class="userCardName"><?php echo ucfirst($_SESSION['username']) ?></h3>
                    </div>
                </div>
            </div>
        </div>

        <nav>
            <div class="menuItem">
                <button onclick="window.location.href='index.php'">
                    <div class="menuItem-name">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </div>
                </button>
            </div>

            <?php if(isset($_SESSION['username']) && ($_SESSION['username'] === 'librarian' || $_SESSION['username'] === 'reception' || $_SESSION['username'] === 'manager')): ?>
                <div class="menuItem">
                    <button onclick="toggleMenu(this)">
                        <div class="menuItem-name">
                            <i class="fa fa-book"></i>
                            <span>Books</span>
                        </div>
                        <i class="fa fa-angle-down"></i>
                    </button>

                    <div class="submenu">
                        <a href="addBook.php">Add Book</a>
                        <!-- <a href="#">Delete Book</a> -->
                        <!-- <a href="#">Update Book</a> -->
                        <a href="viewBook.php">View Book</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['username']) && ($_SESSION['username'] === 'reception' || $_SESSION['username'] === 'manager')): ?>
                <div class="menuItem">
                    <button onclick="toggleMenu(this)">
                        <div class="menuItem-name">
                            <i class="fa fa-user"></i>
                            <span>Members</span>
                        </div>
                        <i class="fa fa-angle-down"></i>
                    </button>

                    <div class="submenu">
                        <a href="memberCrud.php">Update Members</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['username']) && ($_SESSION['username'] === 'reception' || $_SESSION['username'] === 'manager')): ?>
                <div class="menuItem">
                    <button onclick="toggleMenu(this)">
                        <div class="menuItem-name">
                            <i class="fa fa-cart-plus"></i>
                            <span>Loans</span>
                        </div>
                        <i class="fa fa-angle-down"></i>
                    </button>

                    <div class="submenu">
                        <a href="processLoan.php">Process Loan</a>
                        <a href="processReturn.php">Process Return</a>
                        <a href="processFine.php">Process Fine</a>
                    </div>
                </div>
            <?php endif; ?>

            <?php if(isset($_SESSION['username']) && ($_SESSION['username'] === 'manager')): ?>
                <div class="menuItem">
                    <button onclick="toggleMenu(this)">
                        <div class="menuItem-name">
                            <i class="fa fa-area-chart"></i>
                            <span>Admin</span>
                        </div>
                        <i class="fa fa-angle-down"></i>
                    </button>

                    <div class="submenu">
                        <!-- <a href="#">Produce Fine Report</a>
                        <a href="#">Produce Genre Report</a> -->
                    </div>
                </div>
            <?php endif; ?>

            <div class="divider"></div>

            <div class="menuItem">
                <form action="logout.php" method="post">
                    <button type="submit" id="logoutButton">
                        <div class="menuItem-name">
                            <i class="fa fa-sign-out"></i>
                            <span>Logout</span>
                        </div>
                    </button>
                </form>
            </div>

            <div class="lightMode">
                <button onclick="toggleLightMode()" class="lightModeButton">
                    <i class="fa fa-sun-o"></i>
                    <span class="lightModeText">Light Mode</span>
                </button>
            </div>
        </nav>  
    </aside>

    <script src="public/js/script.js" defer></script>

    <script>
        const logout = document.getElementById("logoutButton");

        logout.addEventListener("click", function() {
            window.location.href = "logout.php";
        })
    </script>
</body>
</html>