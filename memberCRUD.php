<?php
    ini_set('display_errors', 1);
    error_reporting(E_ALL);

    require_once("config/config.php");

    $inputErrors = [];
    $success = "";
    
    if (isset($_POST["updateMemberDetails"])) {
        $cfirstName = $_POST['cFirstName'] ?? "";
        $clastName = $_POST['cLastName'] ?? "";
        $cDOB = $_POST['cDOB'] ?? "";
        $cPhone = $_POST['cPhone'] ?? "";
        $cEmail = $_POST['cEmail'] ?? "";
        $cAddressLine1 = $_POST['cAddressLine1'] ?? "";
        $cAddressLine2 = $_POST['cAddressLine2'] ?? "";
        $cCity = $_POST['cCity'] ?? "";
        $cCounty = $_POST['cCounty'] ?? "";
        $cEircode = $_POST['cEircode'] ?? "";
        $cRegistrationDate = $_POST['cRegistrationDate'] ?? "";
        $cStatus = $_POST['cStatus'] ?? "";
        $cID = (int) $_POST['cMemberID'] ?? 0;

        $member = new Member($cfirstName, $clastName, $cDOB, $cPhone, $cEmail, $cAddressLine1, $cAddressLine2, $cCity,
                             $cCounty, $cEircode, $cRegistrationDate, $cStatus, $cID);
        $inputErrors = $libraryService->updateMember($member);

        if (empty($inputErrors)) {
            header("Location: memberCRUD.php");
        }
    } else if(isset($_POST["addMemberDetails"])) {
        $cfirstName = $_POST['cFirstName'] ?? "";
        $clastName = $_POST['cLastName'] ?? "";
        $cDOB = $_POST['cDOB'] ?? "";
        $cPhone = $_POST['cPhone'] ?? "";
        $cEmail = $_POST['cEmail'] ?? "";
        $cAddressLine1 = $_POST['cAddressLine1'] ?? "";
        $cAddressLine2 = $_POST['cAddressLine2'] ?? "";
        $cCity = $_POST['cCity'] ?? "";
        $cCounty = $_POST['cCounty'] ?? "";
        $cEircode = $_POST['cEircode'] ?? "";
        $cRegistrationDate = $_POST['cRegistrationDate'] ?? "";
        $cStatus = $_POST['cStatus'] ?? "";
        $cID = (int) $_POST['cMemberID'] ?? 0;

        $member = new Member($cfirstName, $clastName, $cDOB, $cPhone, $cEmail, $cAddressLine1, $cAddressLine2, $cCity,
                             $cCounty, $cEircode, $cRegistrationDate, $cStatus, $cID);
        $inputErrors = $libraryService->addMember($member);
    }

    if (isset($_POST["deleteMember"])) {
        $id = $_POST['cMemberID'];

        // echo "<script>alert(`You are now deleting member ${id}`)</script>";

        $member = new Member('', '', '', '', '', '', '', '', '', '', '', '', $id);
        $libraryService->alterMemberStatus($member);
    }

    $members = $libraryService->getAllMembers();
    $searchMember = $_POST['cSearchMember'] ?? '';

    if (!empty($searchMember)) {
        $members = $libraryService->searchMembers($searchMember);
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php include_once("inc/navMenu.php"); ?>

    <main class="memberMain">
        <div class="memberAddSearch">
            <div class="addMember">
                <button onclick="openAddMenu()"><i class="fa fa-plus"></i>Add Member</button>
            </div>

            <div class="searchMember">
                <form action="memberCRUD.php" method="post">
                    <input type="text" name="cSearchMember" id="cSearchMember" placeholder="Search library records...">
                </form>
            </div>
        </div>

        <div class="memberTable">
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

                <?php foreach($members as $member) : ?>
                    <?php $statusText = ($member->getStatus() === 'A') ? 'Active' : 'Inactive'; ?>
                    <tr>
                        <td><?php echo $member->getId(); ?></td>
                        <td><?php echo $member->getFirstName() . ' ' . $member->getLastName();; ?></td>
                        <td><?php echo $member->getDob(); ?></td>
                        <td><?php echo $member->getPhone(); ?></td>
                        <td><?php echo $member->getEmail(); ?></td>
                        <td><?php echo $member->getAddressLine1() . ', ' . $member->getAddressLine2() . ', ' . $member->getCity(); ?></td>
                        <td><?php echo $member->getCounty(); ?></td>
                        <td><?php echo $member->getEircode(); ?></td>
                        <td><?php echo $member->getRegistrationDate(); ?></td>
                        <td><?php echo $statusText ?></td>
                        <td>
                            <div class="editMember">
                                <button onclick='showEditMenu(this)' class='editMemberButton'><i class='fa fa-edit'></i>EDIT</button>
                            </div>
                        </td>
                        <td>
                            <form action="memberCRUD.php" method="post">
                                <div class='deleteMember'>
                                    <input type="hidden" name="cMemberID" value="<?php echo $member->getId(); ?>">
                                    <button onclick = 'deleteMember(this)' class='deleteMemberButton' name="deleteMember"><i class='fa fa-trash-o'></i>DELETE</button>
                                </div>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </main>

    <div class="contentOverlay" id="overlay" onclick="closeEditMenu()"></div>

    <div class="formContainerUpdate" id="updateMemberForm">
        <form action="memberCRUD.php" method="post" class="">
            <div class="formContainerUpdateSections">
                <div class="formContainerUpdateSplit">
                    <input type="hidden" name="cMemberID" id="cMemberID">

                    <div class="formGroup">
                        <label for="cFirstName">First Name</label>
                        <input type="text" name="cFirstName" id="cFirstName" placeholder="Enter first name" value="<?php echo htmlspecialchars($_POST['cFirstName'] ?? '') ?>">
                        
                        <?php if (!empty($inputErrors['cFirstName'])): ?>
                            <div class="errorOutput">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="errorMessage"><?php echo $inputErrors['cFirstName'] ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="formGroup">
                        <label for="cLastName">Last Name</label>
                        <input type="text" name="cLastName" id="cLastName" placeholder="Enter last name" value="<?php echo htmlspecialchars($_POST['cLastName'] ?? '') ?>">

                        <?php if (!empty($inputErrors['cLastName'])): ?>
                            <div class="errorOutput">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="errorMessage"><?php echo $inputErrors['cLastName'] ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="formGroup">
                        <label for="cDOB">DOB</label>
                        <input type="date" name="cDOB" id="cDOB" value="<?php echo htmlspecialchars($_POST['cDOB'] ?? '') ?>">

                        <?php if (!empty($inputErrors['cDOB'])): ?>
                            <div class="errorOutput">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="errorMessage"><?php echo $inputErrors['cDOB'] ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="formGroup">
                        <label for="cPhone">Phone</label>
                        <input type="tel" name="cPhone" id="cPhone" placeholder="Enter phone number" value="<?php echo htmlspecialchars($_POST['cPhone'] ?? '') ?>">

                        <?php if (!empty($inputErrors['cPhone'])): ?>
                            <div class="errorOutput">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="errorMessage"><?php echo $inputErrors['cPhone'] ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="formGroup">
                        <label for="cEmail">Email</label>
                        <input type="email" name="cEmail" id="cEmail" placeholder="Enter email" value="<?php echo htmlspecialchars($_POST['cEmail'] ?? '') ?>">

                        <?php if (!empty($inputErrors['cEmail'])): ?>
                            <div class="errorOutput">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="errorMessage"><?php echo $inputErrors['cEmail'] ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="formGroup">
                        <label for="cAddressLine1">Address Line 1</label>
                        <input type="text" name="cAddressLine1" id="cAddressLine1" placeholder="Enter address line 1" value="<?php echo htmlspecialchars($_POST['cAddressLine1'] ?? '') ?>">

                        <?php if (!empty($inputErrors['cAddressLine1'])): ?>
                            <div class="errorOutput">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="errorMessage"><?php echo $inputErrors['cAddressLine1'] ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="formContainerUpdateSplit">
                    <div class="formGroup">
                        <label for="cAddressLine2">Address Line 2</label>
                        <input type="text" name="cAddressLine2" id="cAddressLine2" placeholder="Enter address line 2" value="<?php echo htmlspecialchars($_POST['cAddressLine2'] ?? '') ?>">
                        
                        <?php if (!empty($inputErrors['cAddressLine2'])): ?>
                            <div class="errorOutput">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="errorMessage"><?php echo $inputErrors['cAddressLine2'] ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="formGroup">
                        <label for="cCity">Town/City</label>
                        <input type="text" name="cCity" id="cCity" placeholder="Enter town/city" value="<?php echo htmlspecialchars($_POST['cCity'] ?? '') ?>">

                        <?php if (!empty($inputErrors['cCity'])): ?>
                            <div class="errorOutput">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="errorMessage"><?php echo $inputErrors['cCity'] ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="formGroup">
                        <label for="cCounty">County</label>
                        <select name="cCounty" id="cCounty" value="<?php echo htmlspecialchars($_POST['cCounty'] ?? '') ?>">
                            <option disabled selected value>-- Select County --</option>
                            <option value="carlow" <?php echo (($_POST['cCounty'] ?? '') === 'carlow') ? 'selected' : ''?>>Carlow</option>
                            <option value="cavan" <?php echo (($_POST['cCounty'] ?? '') === 'cavan') ? 'selected' : ''?>>Cavan</option>
                            <option value="clare" <?php echo (($_POST['cCounty'] ?? '') === 'clare') ? 'selected' : ''?>>Clare</option>
                            <option value="cork" <?php echo (($_POST['cCounty'] ?? '') === 'cork') ? 'selected' : ''?>>Cork</option>
                            <option value="donegal" <?php echo (($_POST['cCounty'] ?? '') === 'donegal') ? 'selected' : ''?>>Donegal</option>
                            <option value="dublin" <?php echo (($_POST['cCounty'] ?? '') === 'dublin') ? 'selected' : ''?>>Dublin</option>
                            <option value="galway" <?php echo (($_POST['cCounty'] ?? '') === 'galway') ? 'selected' : ''?>>Galway</option>
                            <option value="kerry" <?php echo (($_POST['cCounty'] ?? '') === 'kerry') ? 'selected' : ''?>>Kerry</option>
                            <option value="kildare" <?php echo (($_POST['cCounty'] ?? '') === 'kildare') ? 'selected' : ''?>>Kildare</option>
                            <option value="kilkenny" <?php echo (($_POST['cCounty'] ?? '') === 'kilkenny') ? 'selected' : ''?>>Kilkenny</option>
                            <option value="laois" <?php echo (($_POST['cCounty'] ?? '') === 'laois') ? 'selected' : ''?>>laois</option>
                            <option value="leitrim" <?php echo (($_POST['cCounty'] ?? '') === 'leitrim') ? 'selected' : ''?>>Leitrim</option>
                            <option value="limerick" <?php echo (($_POST['cCounty'] ?? '') === 'limerick') ? 'selected' : ''?>>Limerick</option>
                            <option value="longford" <?php echo (($_POST['cCounty'] ?? '') === 'longford') ? 'selected' : ''?>>Longford</option>
                            <option value="louth" <?php echo (($_POST['cCounty'] ?? '') === 'louth') ? 'selected' : ''?>>Louth</option>
                            <option value="mayo" <?php echo (($_POST['cCounty'] ?? '') === 'mayo') ? 'selected' : ''?>>Mayo</option>
                            <option value="meath" <?php echo (($_POST['cCounty'] ?? '') === 'meath') ? 'selected' : ''?>>Meath</option>
                            <option value="monaghan" <?php echo (($_POST['cCounty'] ?? '') === 'monaghan') ? 'selected' : ''?>>Monaghan</option>
                            <option value="offaly" <?php echo (($_POST['cCounty'] ?? '') === 'offaly') ? 'selected' : ''?>>Offaly</option>
                            <option value="roscommon" <?php echo (($_POST['cCounty'] ?? '') === 'roscommon') ? 'selected' : ''?>>Roscommon</option>
                            <option value="sligo" <?php echo (($_POST['cCounty'] ?? '') === 'sligo') ? 'selected' : ''?>>Sligo</option>
                            <option value="tipperary" <?php echo (($_POST['cCounty'] ?? '') === 'tipperary') ? 'selected' : ''?>>Tipperary</option>
                            <option value="waterford" <?php echo (($_POST['cCounty'] ?? '') === 'waterford') ? 'selected' : ''?>>Waterford</option>
                            <option value="westmeath" <?php echo (($_POST['cCounty'] ?? '') === 'westmeath') ? 'selected' : ''?>>Westmeath</option>
                            <option value="wexford" <?php echo (($_POST['cCounty'] ?? '') === 'wexford') ? 'selected' : ''?>>Wexford</option>
                            <option value="wicklow" <?php echo (($_POST['cCounty'] ?? '') === 'wicklow') ? 'selected' : ''?>>Wicklow</option>
                        </select>

                        <?php if (!empty($inputErrors['cCounty'])): ?>
                            <div class="errorOutput">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="errorMessage"><?php echo $inputErrors['cCounty'] ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="formGroup">
                        <label for="cEircode">Eircode</label>
                        <input type="text" name="cEircode" id="cEircode" placeholder="Enter eircode" value="<?php echo htmlspecialchars($_POST['cEircode'] ?? '') ?>">

                        <?php if (!empty($inputErrors['cEircode'])): ?>
                            <div class="errorOutput">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="errorMessage"><?php echo $inputErrors['cEircode'] ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="formGroup">
                        <label for="cRegistrationDate">Registration Date</label>
                        <input type="date" name="cRegistrationDate" id="cRegistrationDate" value="<?php echo htmlspecialchars($_POST['cRegistrationDate'] ?? '') ?>">

                        <?php if (!empty($inputErrors['cRegistrationDate'])): ?>
                            <div class="errorOutput">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="errorMessage"><?php echo $inputErrors['cRegistrationDate'] ?></span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="formGroup">
                        <label for="cStatus">Status</label>
                        <select name="cStatus" id="cStatus">
                            <option disabled selected value>-- Select Status --</option>
                            <option value="A" <?php echo (($_POST['cStatus'] ?? '') === 'A') ? 'selected' : ''?>>Active</option>
                            <option value="I" <?php echo (($_POST['cStatus'] ?? '') === 'I') ? 'selected' : ''?>>Inactive</option>
                        </select>

                        <?php if (!empty($inputErrors['cStatus'])): ?>
                            <div class="errorOutput">
                                <i class="fa fa-exclamation-triangle"></i>
                                <span class="errorMessage"><?php echo $inputErrors['cStatus'] ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

                <div class="formGroup">
                    <input type="button" value="Cancel" onclick="closeEditMenu()">
                    <input type="submit" value="Update Member" name="updateMemberDetails" id="submitMemberDetails">
                </div>
        </form>
    </div>
    
    <script src="public/js/script.js"></script>
    <script>
        <?php if (!empty($inputErrors)): ?>
            overlay.classList.add("open");
            editForm.classList.add("open");

            <?php if (isset($_POST['addMemberDetails'])): ?>
                document.getElementById("submitMemberDetails").value = "Add Member";
                document.getElementById("submitMemberDetails").name = "addMemberDetails";
            <?php endif; ?>
        <?php endif; ?>
    </script>
</body>
</html>