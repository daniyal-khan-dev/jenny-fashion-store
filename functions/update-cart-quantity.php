<?php
session_start();
include('../config/dbcon.php');

if (isset($_POST['user_id']) && isset($_POST['prod_id']) && isset($_POST['qty'])) {
    $user_id = $_POST['user_id'];
    $prod_id = $_POST['prod_id'];
    $quantity = $_POST['qty'];

    $check_cart_query = "SELECT * FROM carts WHERE user_id='$user_id' AND prod_id='$prod_id'";
    $check_cart_result = mysqli_query($con, $check_cart_query);

    if ($check_cart_result) {
        if (mysqli_num_rows($check_cart_result) > 0) {

            $update_cart_query = "UPDATE carts SET prod_qty='$quantity' WHERE user_id='$user_id' AND prod_id='$prod_id'";
            $update_cart_query_run = mysqli_query($con, $update_cart_query);

            if ($update_cart_query_run) {

                $productPriceQuery = "SELECT d_price FROM products WHERE id='$prod_id'";
                $productPriceResult = mysqli_query($con, $productPriceQuery);

                if ($productPriceResult && mysqli_num_rows($productPriceResult) > 0) {
                    $productPriceRow = mysqli_fetch_assoc($productPriceResult);
                    $productPrice = $productPriceRow['d_price'];

                    $totalPrice = $productPrice * $quantity;
                    $update_total_price_query = "UPDATE carts SET total_price='$totalPrice' WHERE user_id='$user_id' AND prod_id='$prod_id'";
                    $update_total_price_query_run = mysqli_query($con, $update_total_price_query);

                    if ($update_total_price_query_run) {
                        echo 'success';
                    } else {
                        echo 'error updating total price: ' . mysqli_error($con);
                    }
                } else {
                    echo 'error fetching product price: ' . mysqli_error($con);
                }
            } else {
                echo 'error updating quantity: ' . mysqli_error($con);
            }
        } else {
            echo 'Product not found in the cart';
        }
    } else {
        echo 'error checking cart: ' . mysqli_error($con);
    }
} else {
    echo 'Invalid data received';
}

mysqli_close($con);
?>
