<?php

session_start();

if (isset($_SESSION['auth'])) {
    
    unset($_SESSION['auth']);
    unset($_SESSION['auth_user']);

    $_SESSION['message1'] = "log out Successfully";
    header('Location: /jenny/');
    exit();
}
?>

