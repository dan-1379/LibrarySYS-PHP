<?php 
    require_once("config/config.php");

    if (isset($_POST['submitLogin'])) {
        $username = htmlspecialchars($_POST['username']);
        $password = htmlspecialchars($_POST['password']);

        $result = $libraryService->processLogin($username, $password);

        if (isset($result['usernameError']) || isset($result['passwordError']) || isset($result['login'])) {
            $errors = $result;
        } else {
            $_SESSION['username'] = $result['username'];
            header("Location: index.php");
            exit();
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Login</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <div class="login">
        <h2>Sign in</h2>
        <p>Enter your staff credentials to continue.</p>

        <form action="login.php" method="post">
            <?php if (!empty($errors['login'])): ?>
                <div class="errorOutput">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span class="errorMessage"><?php echo $errors['login']; ?></span>
                </div>
            <?php endif; ?>
            <div class="formGroup">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Enter your username">

                <?php if (!empty($errors['usernameError'])): ?>
                    <div class="errorOutput">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="errorMessage"><?php echo $errors['usernameError']; ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="formGroup">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password">

                <?php if (!empty($errors['passwordError'])): ?>
                    <div class="errorOutput">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="errorMessage"><?php echo $errors['passwordError']; ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <input type="submit" value="Sign in" name="submitLogin">
        </form>
    </div>
</body>
</html>