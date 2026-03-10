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

    <table>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Description</th>
                <th>ISBN</th>
                <th>Genre</th>
                <th>Publisher</th>
                <th>Publication</th>
                <th>Status</th>
            </tr>

            <?php 
                include_once("functions.php"); 
                fetchAllBooks();
            ?>
    </table>
</body>
</html>