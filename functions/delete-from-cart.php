<?php
session_start();
include('../config/dbcon.php');

if (isset($_POST['cart_id'])) {
    $cart_id = $_POST['cart_id'];

    $delete_cart_query = "DELETE FROM carts WHERE id='$cart_id'";
    $delete_cart_query_run = mysqli_query($con, $delete_cart_query);

    if ($delete_cart_query_run) {
        echo 'success';
    } else {
        echo 'error: ' . mysqli_error($con);
    }
} else {
    echo 'invalid';
}

mysqli_close($con);
?>
