<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <?php 
        include_once("inc/navMenu.php"); 
        include_once("functions.php");
    ?>
    
    <table class="memberCrudTable">
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
            <th>Edit</th>
            <th>Delete</th>
        </tr>

        <?php 
            fetchAllMembers();
        ?>
    </table>

    <div class="formContainer">
        <form action="memberCRUD.php" method="post">
            <?php if (!empty($success)) : ?>
                <div class="successOutput">
                    <i class="fa fa-check-circle"></i>
                    <span class="successMessage"><?php echo $success; ?></span>
                </div>
            <?php endif; ?>

            <label for="cFirstName">First Name</label>
            <input type="text" name="" id="" placeholder="Enter first name">

            <label for="cLastName">First Name</label>
            <input type="text" name="cLastName" id="" placeholder="Enter first name">

            <label for="cDOB">DOB</label>
            <input type="date" name="cDOB" id="">

            <label for="cPhone">Phone</label>
            <input type="tel" name="cPhone" id="">

            <label for="cEmail">Email</label>
            <input type="email" name="cEmail" id="">

            <label for="cAddressLine1">Address Line 1</label>
            <input type="text" name="cAddressLine1" id="">

            <label for="cAddressLine2">Address Line 2</label>
            <input type="text" name="cAddressLine2" id="">

            <label for="cCity">Town/City</label>
            <input type="text" name="cCity" id="">

            <label for="cCounty">County</label>
            <select name="cCounty" id="">
                <option disabled selected value>-- Select County --</option>
                <option value="carlow">Carlow</option>
                <option value="cavan">Cavan</option>
                <option value="clare">Clare</option>
                <option value="cork">Cork</option>
                <option value="donegal">Donegal</option>
                <option value="dublin">Dublin</option>
                <option value="galway">Galway</option>
                <option value="kerry">Kerry</option>
                <option value="kildare">Kildare</option>
                <option value="kilkenny">Kilkenny</option>
                <option value="laois">laois</option>
                <option value="leitrim">Leitrim</option>
                <option value="limerick">Limerick</option>
                <option value="longford">Longford</option>
                <option value="louth">Louth</option>
                <option value="mayo">Mayo</option>
                <option value="meath">Meath</option>
                <option value="monaghan">Monaghan</option>
                <option value="offaly">Offaly</option>
                <option value="roscommon">Roscommon</option>
                <option value="sligo">Sligo</option>
                <option value="tipperary">Tipperary</option>
                <option value="waterford">Waterford</option>
                <option value="westmeath">Westmeath</option>
                <option value="wexford">Wexford</option>
                <option value="wicklow">Wicklow</option>
            </select>

            <label for="cEircode">Eircode</label>
            <input type="text" name="cEircode" id="">

            <label for="cRegistrationDate">Registration Date</label>
            <input type="date" name="cRegistrationDate" id="">

            <label for="cStatus">County</label>
            <select name="cStatus" id="">
                <option disabled selected value>-- Select Status --</option>
                <option value="A">Active</option>
                <option value="I">Inactive</option>
            </select>
        </form>
    </div>
</body>
</html>