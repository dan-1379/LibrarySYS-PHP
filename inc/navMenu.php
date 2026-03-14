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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibrarySYS</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<body>
    <aside>
        <div class="logo">
            <h1>Killorglin Library</h1>
            <p>Management System</p>
            <i class="fa fa-angle-left" onclick="toggleAside()"></i>
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

            <div class="menuItem">
                <button onclick="toggleMenu(this)">
                    <div class="menuItem-name">
                        <i class="fa fa-book"></i>
                        <span>Books</span>
                    </div>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>

                <div class="submenu">
                    <a href="addBook.php">Add Book</a>
                    <a href="#">Delete Book</a>
                    <a href="#">Update Book</a>
                    <a href="viewBook.php">View Book</a>
                </div>
            </div>

            <div class="menuItem">
                <button onclick="toggleMenu(this)">
                    <div class="menuItem-name">
                        <i class="fa fa-user"></i>
                        <span>Members</span>
                    </div>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>

                <div class="submenu">
                    <a href="memberCrud.php">Update Members</a>
                </div>
            </div>

            <div class="menuItem">
                <button onclick="toggleMenu(this)">
                    <div class="menuItem-name">
                        <i class="fa fa-cart-plus"></i>
                        <span>Loans</span>
                    </div>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>

                <div class="submenu">
                    <a href="#">Process Loan</a>
                    <a href="#">Process Return</a>
                </div>
            </div>

            <div class="menuItem">
                <button onclick="toggleMenu(this)">
                    <div class="menuItem-name">
                        <i class="fa fa-area-chart"></i>
                        <span>Admin</span>
                    </div>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>

                <div class="submenu">
                    <a href="#">Produce Fine Report</a>
                    <a href="#">Produce Genre Report</a>
                </div>
            </div>

            <div class="divider"></div>

            <div class="menuItem">
                <button>
                    <div class="menuItem-name">
                        <i class="fa fa-sign-out"></i>
                        <span>Logout</span>
                    </div>
                </button>
            </div>

            <div class="lightMode">
                <button onclick="toggleLightMode()" class="lightModeButton">
                    <i class="fa fa-sun-o"></i>
                    <span class="lightModeText">Light Mode</span>
                </button>
            </div>
        </nav>  
    </aside>

    <script>
        // https://www.w3schools.com/jsref/prop_element_classlist.asp
        function toggleMenu(button) {
            const menuItem = button.parentElement;
            menuItem.classList.toggle('open');
        }

        function toggleAside() {
            const aside = document.querySelector("aside");
            aside.classList.toggle("collapsed");
            
            const arrowIcon = document.querySelector(".logo i");
            if (aside.classList.contains("collapsed")) {
                arrowIcon.classList.remove("fa-angle-left");
                arrowIcon.classList.add("fa-angle-right");
            } else {
                arrowIcon.classList.add("fa-angle-left");
                arrowIcon.classList.remove("fa-angle-right");
            }
        }

        function toggleLightMode() {
            const lightIcon = document.querySelector(".colorMode i");
            const root = document.documentElement;

            root.classList.toggle("light");

            if (root.classList.contains("light")) {
                lightIcon.classList.remove("fa-sun-o");
                lightIcon.classList.add("fa-moon-o");
            } else {
                ightIcon.classList.remove("fa-moon-o");
                ightIcon.classList.add("fa-sun-o");
            }

            localStorage.setItem('colorMode', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
        }

        document.addEventListener("DOMContentLoaded", () => {
            const savedColorMode = localStorage.getItem("colorMode");

            if (savedColorMode === "dark") {
                toggleLightMode();
            }
        })
    </script>
</body>
</html>