<?php 
    require_once("config/config.php");
    $totalBooks = $libraryService->getTotalBooks();
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
        <h1>Killorglin Library Management System</h1>
        <p>Process books, members and loans.</p>

        <div class="totalBooks">
            <h2>Total Books</h2>
            <p><?php echo $totalBooks . " books"; ?></p>
        </div>
    </main>
</body>
</html>