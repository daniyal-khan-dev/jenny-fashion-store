<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['auth'])) {
    $_SESSION['message2'] = "Please login as admin to continue";
    header('Location: /jenny/login');
    exit();
}

// Check if user role exists and is admin
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 1) {
    $_SESSION['message2'] = "Access denied. Admins only.";
    header('Location: /jenny/index');
    exit();
}
?>