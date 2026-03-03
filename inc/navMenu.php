<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LibrarySYS</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <aside>
        <div class="logo">
            <h1>Killorglin Library</h1>
            <p>Management System</p>
        </div>

        <nav>
            <a href="index.php">Dashboard</a>

            <div class="menuItem">
                <button onclick="toggleMenu(this)">
                    <span>Books</span>
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
                    <span>Members</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>

                <div class="submenu">
                    <a href="#">Add Member</a>
                    <a href="#">Delete Member</a>
                    <a href="#">Update Book</a>
                    <a href="#">View Book</a>
                </div>
            </div>

            <div class="menuItem">
                <button onclick="toggleMenu(this)">
                    <span>Loans</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>

                <div class="submenu">
                    <a href="#">Process Loan</a>
                    <a href="#">Process Return</a>
                </div>
            </div>

            <div class="menuItem">
                <button onclick="toggleMenu(this)">
                    <span>Admin</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>

                <div class="submenu">
                    <a href="#">Produce Fine Report</a>
                    <a href="#">Produce Genre Report</a>
                </div>
            </div>

            <div class="divider"></div>

            <a href="#" class="logout">Logout</a>
        </nav>  
    </aside>

    <script>
        function toggleMenu(button) {
            const menuItem = button.parentElement;
            menuItem.classList.toggle('open');
        }
    </script>
</body>
</html>