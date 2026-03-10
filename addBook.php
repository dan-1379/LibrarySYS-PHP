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
        global $inputErrors;
        global $success;
    ?>

    <div class="formContainer">
        <h2>Add Book</h2>
        <p>Killorglin Library System</p>
        
        <form action="addBook.php" method="post">
            <?php if (!empty($success)) : ?>
                <div class="successOutput">
                    <i class="fa fa-check-circle"></i>
                    <span class="successMessage"><?php echo $success; ?></span>
                </div>
            <?php endif; ?>

            <div class="formGroup">
                <label for="ctitle">Title</label>
                <input type="text" name="ctitle" id="ctitle" placeholder="Enter title" value="<?php echo htmlspecialchars($_POST['ctitle'] ?? '') ?>">

                <?php if (!empty($inputErrors['ctitle'])): ?>
                    <div class="errorOutput">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="errorMessage"><?php echo $inputErrors['ctitle'] ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="formGroup">
                <label for="cauthor">Author</label>
                <input type="text" name="cauthor" id="" placeholder="Enter author" value="<?php echo htmlspecialchars($_POST['cauthor'] ?? '') ?>">
                
                <?php if (!empty($inputErrors['cauthor'])): ?>
                    <div class="errorOutput">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="errorMessage"><?php echo $inputErrors['cauthor'] ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="formGroup">
                <label for="cdescription">Description</label>
                <input type="text" name="cdescription" id="" placeholder="Enter description" value="<?php echo htmlspecialchars($_POST['cdescription'] ?? '') ?>">

                <?php if (!empty($inputErrors['cdescription'])): ?>
                    <div class="errorOutput">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="errorMessage"><?php echo $inputErrors['cdescription'] ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="formGroup">
                <label for="cisbn">ISBN</label>
                <input type="text" name="cisbn" id="" placeholder="Enter ISBN" value="<?php echo htmlspecialchars($_POST['cisbn'] ?? '') ?>">

                <?php if (!empty($inputErrors['cisbn'])): ?>
                    <div class="errorOutput">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="errorMessage"><?php echo $inputErrors['cisbn'] ?></span>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="formGroup">
                <label for="cgenre">Genre</label>
                <select name="cgenre" id="">
                    <option disabled selected value name="genre"> -- Select an option -- </option>
                    <!-- https://stackoverflow.com/questions/16458332/how-to-retain-selected-values-in-select-field-after-form-submission -->
                    
                    <option value="sci-fi">Science Fiction</option>
                    <option value="fantasy">Fantasy</option>
                    <option value="mystery">Mystery</option>
                    <option value="thriller">Thriller</option>
                    <option value="horror">Horror</option>
                </select>

                <?php if (!empty($inputErrors['cgenre'])): ?>
                    <div class="errorOutput">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="errorMessage"><?php echo $inputErrors['cgenre'] ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="formGroup">
                <label for="cpublisher">Publisher</label>
                <input type="text" name="cpublisher" id="" placeholder="Enter publisher" value="<?php echo htmlspecialchars($_POST['cpublisher'] ?? '') ?>">

                <?php if (!empty($inputErrors['cpublisher'])): ?>
                    <div class="errorOutput">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="errorMessage"><?php echo $inputErrors['cpublisher'] ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="formGroup">
                <label for="cpublication">Publication</label>
                <input type="date" name="cpublication" id="" value="<?php echo htmlspecialchars($_POST['ctitle'] ?? '') ?>">

                <?php if (!empty($inputErrors['cpublication'])): ?>
                    <div class="errorOutput">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="errorMessage"><?php echo $inputErrors['cpublication'] ?></span>
                    </div>
                <?php endif; ?>
            </div>

           <div class="formGroup">
                <label for="cstatus">Status</label>
                <select name="cstatus" id="">
                    <option disabled selected value> -- Select an option -- </option>
                    <option value="A">Available</option>
                    <option value="C">Checked Out</option>
                    <option value="U">Unavailable</option>
                </select>

                <?php if (!empty($inputErrors['cstatus'])): ?>
                    <div class="errorOutput">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="errorMessage"><?php echo $inputErrors['cstatus'] ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <input type="submit" value="Add Book" name="submitBookDetails">
        </form>
    </div>
</body>
</html>