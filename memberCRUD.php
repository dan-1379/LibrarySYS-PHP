<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php 
        include_once("inc/navMenu.php"); 
        include_once("functions.php");
    ?>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>DOB</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Address</th>
            <th>County</th>
            <th>Eircode</th>
            <th>Registration</th>
            <th>Status</th>
        </tr>

        <?php 
            fetchAllMembers();
        ?>
    </table>
</body>
</html>