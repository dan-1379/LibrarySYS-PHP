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

        insertBookRecord();
    ?>

    <div class="formContainer">
        <h2>Add Book</h2>
        <p>Killorglin Library System</p>
        <form action="addBook.php" method="post">
            <div class="formGroup">
                <label for="ctitle">Title</label>
                <input type="text" name="ctitle" id="" placeholder="Enter title">
            </div>

            <div class="formGroup">
                <label for="cauthor">Author</label>
                <input type="text" name="cauthor" id="" placeholder="Enter author">

            </div>

            <div class="formGroup">
                <label for="cdescription">Description</label>
                <input type="text" name="cdescription" id="" placeholder="Enter description">
            </div>

            <div class="formGroup">
                <label for="cisbn">ISBN</label>
                <input type="text" name="cisbn" id="" placeholder="Enter ISBN">
            </div>
            
            <div class="formGroup">
                <label for="cgenre">Genre</label>
                <select name="cgenre" id="">
                    <option value="A">A</option>
                    <option value="B">B</option>
                </select>
            </div>

            <div class="formGroup">
                <label for="cpublisher">Publisher</label>
                <input type="text" name="cpublisher" id="" placeholder="Enter publisher">
            </div>

            <div class="formGroup">
                <label for="cpublication">Title</label>
                <input type="date" name="cpublication" id="">
            </div>

           <div class="formGroup">
                <label for="cpublication">Title</label>
                <select name="cstatus" id="">
                    <option value="A">A</option>
                    <option value="I">I</option>
                </select>
            </div>
    
        
    
            <input type="submit" value="Add Book" name="submitBookDetails">
        </form>
    </div>
</body>
</html>