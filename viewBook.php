<?php 
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require_once("config/config.php");
    $books = $libraryService->getAllBooks();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Book</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include_once("inc/navMenu.php"); ?>

    <?php if(empty($books)) : ?>
        <div class="infoMessage">
            <i class="fa fa-warning"></i>
            <p>No books currently in the library</p>
        </div>
    <?php else : ?>
        <table class="viewBookTable">
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

                <?php foreach($books as $book) :  ?>
                    <tr>
                        <td><?php echo $book->getId(); ?></td>
                        <td><?php echo $book->getTitle(); ?></td>
                        <td><?php echo $book->getAuthor(); ?></td>
                        <td><?php echo $book->getDescription(); ?></td>
                        <td><?php echo $book->getIsbn(); ?></td>
                        <td><?php echo $book->getGenre(); ?></td>
                        <td><?php echo $book->getPublisher(); ?></td>
                        <td><?php echo $book->getPublicationDate(); ?></td>
                        <td><?php echo $book->isAvailable() ? "Available" : "Unavailable"; ?></td>
                    </tr>
                <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>