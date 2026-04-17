<?php
session_start();
include('../config/dbcon.php');

if (!isset($_SESSION['auth']) || $_SESSION['auth'] !== true) {
    echo 'not_logged_in';
    exit();
}

if (isset($_POST['user_id']) && isset($_POST['prod_id']) && isset($_POST['qty'])) {
    $user_id  = (int) $_POST['user_id'];
    $prod_id  = (int) $_POST['prod_id'];
    $quantity = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
    
    if ($quantity < 1) {
        $quantity = 1;
    }
    
    // Get product price
    $prod_res = mysqli_query($con, "SELECT d_price FROM products WHERE id='$prod_id' LIMIT 1");
    if (!$prod_res || mysqli_num_rows($prod_res) === 0) {
        echo 'error: product not found';
        exit();
    }
    $prod_row    = mysqli_fetch_assoc($prod_res);
    $unit_price  = (float) $prod_row['d_price'];
    $total_price = $unit_price * $quantity;

    // Check if already in cart
    $check = mysqli_query($con, "SELECT id FROM carts WHERE user_id='$user_id' AND prod_id='$prod_id'");
    if (!$check) {
        echo 'error: ' . mysqli_error($con);
        exit();
    }

    if (mysqli_num_rows($check) > 0) {
        echo 'already_in_cart';
    } else {
        $insert = "INSERT INTO carts (user_id, prod_id, prod_qty, total_price)
                   VALUES ('$user_id', '$prod_id', '$quantity', '$total_price')";
        $run = mysqli_query($con, $insert);
        echo $run ? 'success' : 'error: ' . mysqli_error($con);
    }
} else {
    echo 'invalid';
}

mysqli_close($con);
?>
