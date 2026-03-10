<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
        include_once("inc/navMenu.php"); 
        include_once("functions.php"); 
    ?>

    <main class="main">
        <h1>Killorglin Library Management System</h1>
        <p>Process books, members and loans.</p>

        <div class="totalBooks">
            <h2>Total Books</h2>
            <p><?php echo getTotalBooks() . " books"; ?></p>
        </div>
    </main>
</body>
</html>