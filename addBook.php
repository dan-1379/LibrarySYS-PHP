<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php include_once("inc/navMenu.php"); ?>

    <form action="addBook.php" method="post">
    <!--
        TITLE
        AUTHOR
        DESCRIPTION
        ISBN
        GENRE
        PUBLISHER
        PUBLICATIONDATE
        STATUS
    -->
        <input type="text" name="ctitle" id="" placeholder="Enter title">
        <input type="text" name="cauthor" id="" placeholder="Enter author">
        <input type="text" name="cdescription" id="" placeholder="Enter description">
        <input type="text" name="cisbn" id="" placeholder="Enter ISBN">
        <select name="cgenre" id="">
            <option value="A">A</option>
            <option value="B">B</option>
        </select>
        <input type="text" name="cpublisher" id="" placeholder="Enter publisher">
        <input type="date" name="cpublication" id="">
        <select name="cstatus" id="">
            <option value="A">A</option>
            <option value="I">I</option>
        </select>
        <input type="submit" value="Add Book">
    </form>
</body>
</html>