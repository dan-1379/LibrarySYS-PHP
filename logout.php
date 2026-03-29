<?php
    require_once("config/config.php");

    session_unset();
    session_destroy();
    $_SESSION = [];
    
    header("Location: login.php");
    exit();
?>