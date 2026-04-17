<?php
session_start();
include('../config/dbcon.php');

if (isset($_SESSION['auth_user']['id'])) {
    $user_id = $_SESSION['auth_user']['id'];

    $clear_cart_query = "DELETE FROM carts WHERE user_id='$user_id'";
    $clear_cart_query_run = mysqli_query($con, $clear_cart_query);

    if ($clear_cart_query_run) {
        echo 'success';
    } else {
        echo 'error: ' . mysqli_error($con);
    }
} else {
    echo 'invalid';
}

mysqli_close($con);
?>
