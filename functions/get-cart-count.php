<?php
session_start(); 
include('../config/dbcon.php');

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    $query = "SELECT SUM(prod_qty) as total_items FROM carts WHERE user_id = '$user_id'";
    $result = mysqli_query($con, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $totalItems = $row['total_items'];
        echo $totalItems;
    } else {
        echo '0';
        echo "Error: " . mysqli_error($con); 
    }
} else {
    echo 'error';
}
?>
