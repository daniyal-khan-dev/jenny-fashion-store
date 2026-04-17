<?php

session_start();
// include('../config/dbcon.php');
include('myfunction.php');
header('Content-Type: application/json');


function response($status, $message, $data = null) {

    echo json_encode([
        "status" => $status,
        "message" => $message,
        "data" => $data
    ]);

    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'signup') {

    $firstName = trim($_POST['firstName']);
    $lastName  = trim($_POST['lastName']);
    $username  = trim($_POST['username']);
    $email     = trim($_POST['email']);
    $password  = $_POST['password'];
    $cpassword = $_POST['cPassword'];

    $username = mysqli_real_escape_string($con, $username);
    $email    = mysqli_real_escape_string($con, $email);

    if (empty($firstName)) response(false, "First name is required.");
    if (empty($lastName)) response(false, "Last name is required.");

    if (!preg_match('/^[a-zA-Z0-9_.]+$/', $username)) {
        response(false, "Invalid username format.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        response(false, "Invalid email format.");
    }

    if (strlen($password) < 7) {
        response(false, "Password must be at least 7 characters.");
    }

    if ($password !== $cpassword) {
        response(false, "Passwords do not match.");
    }

    $check = mysqli_query($con, "SELECT username, email FROM users 
        WHERE username='$username' OR email='$email' LIMIT 1
    ");

    if ($check && mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);

        if ($row['username'] === $username) {
            response(false, "Username is already taken.");
        }

        if ($row['email'] === $email) {
            response(false, "Email is already registered.");
        }
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $insert = mysqli_query($con, "
        INSERT INTO users (firstname, lastname, username, email, password, user_role, added_by) 
        VALUES ('$firstName', '$lastName', '$username', '$email', '$hashed', 0, 'System')
    ");

    if ($insert) {
        response(true, "Account registered successfully.");
    } else {
        response(false, "Something went wrong. Please try again.");
    }
}
else if (isset($_GET['action']) && $_GET['action'] == 'signin') {
    $email = mysqli_real_escape_string($con, $_POST['login_email']);
    $password = $_POST['login_pass'];
    
    $login_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $login_query_run = mysqli_query($con, $login_query);

    if (mysqli_num_rows($login_query_run) > 0) {

        $userdata = mysqli_fetch_assoc($login_query_run);

        if (!password_verify($password, $userdata['password'])) {
            response(false, "Invalid credentials");
        }

        // SESSION
        $_SESSION['auth'] = true;

        $_SESSION['auth_user'] = [
            'id' => $userdata['id'],
            'username' => $userdata['username'],
            'email' => $userdata['email']
        ];

        $_SESSION['user_role'] = $userdata['user_role'];
        $message = ($userdata['user_role'] == 1) ? "Welcome to Dashboard" : "Logged in Successfully";
        $_SESSION['message1'] = $message;
        

        // RESPONSE DATA
        response(true, "Login successful", [
            "role" => $userdata['user_role'],
            "redirect" => ($userdata['user_role'] == 1)
                ? "/jenny/admin/dashboard"
                : "/jenny/"
        ]);


    } else {
        response(false, "Invalid credentials");
    }
}

?>
