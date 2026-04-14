<?php 
    require_once("config/config.php");
    $error = "";

    try {
        $books = $libraryService->getAllBooks();
    } catch (Exception $ex) {
        $error = "Error in retrieving books. Please try again later.";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Book</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php include_once("inc/navMenu.php"); ?>

    <?php if(!empty($error)) : ?>
        <div class="infoMessage">
            <i class="fa fa-warning"></i>
            <p><?php echo $error; ?></p>
        </div>
    <?php elseif(empty($books)) : ?>
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
                        <td><?php echo htmlspecialchars($book->getId()); ?></td>
                        <td><?php echo htmlspecialchars(ucwords($book->getTitle())); ?></td>
                        <td><?php echo htmlspecialchars(ucwords($book->getAuthor())); ?></td>
                        <td><?php echo htmlspecialchars($book->getDescription()); ?></td>
                        <td><?php echo htmlspecialchars($book->getIsbn()); ?></td>
                        <td><?php echo htmlspecialchars(ucwords($book->getGenre())); ?></td>
                        <td><?php echo htmlspecialchars(ucwords($book->getPublisher())); ?></td>
                        <td><?php echo htmlspecialchars($book->getPublicationDate()); ?></td>
                        <td><?php echo $book->isAvailable() ? "Available" : "Unavailable"; ?></td>
                    </tr>
                <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>