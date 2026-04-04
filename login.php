<?php 
    require_once("config/config.php");
    $result = [];

    if (isset($_POST['submitLogin'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
        $result = $libraryService->processLogin($username, $password);

        if (empty($result['usernameError']) && empty($result['passwordError']) && empty($result['login'])) {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>
<body>
    <div class="login">
        <h2>Sign in</h2>
        <p>Enter your staff credentials to continue.</p>

        <form action="login.php" method="post">
            <?php if (!empty($result['login'])): ?>
                <div class="errorOutput">
                    <i class="fa fa-exclamation-triangle"></i>
                    <span class="errorMessage"><?php echo $result['login']; ?></span>
                </div>
            <?php endif; ?>
            <div class="formGroup">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" placeholder="Enter your username">

                <?php if (!empty($result['usernameError'])): ?>
                    <div class="errorOutput">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="errorMessage"><?php echo $result['usernameError']; ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <div class="formGroup">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" placeholder="Enter your password">

                <?php if (!empty($result['passwordError'])): ?>
                    <div class="errorOutput">
                        <i class="fa fa-exclamation-triangle"></i>
                        <span class="errorMessage"><?php echo $result['passwordError']; ?></span>
                    </div>
                <?php endif; ?>
            </div>

            <input type="submit" value="Sign in" name="submitLogin">
        </form>
    </div>
</body>
</html>