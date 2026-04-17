<?php

include_once __DIR__ . '/../config/dbcon.php';
date_default_timezone_set('Asia/Karachi');
function getAllActive($table) {
    global $con;
    $query = "SELECT * FROM $table";
    $result = mysqli_query($con, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($con));
    }

    return $result;
}

function getCategoryActive($table, $name)
{
    global $con;
    $query = "SELECT * FROM $table WHERE name='$name' ";
    return $query_run = mysqli_query($con, $query);
}

function getProdByCategory($category_id)
{
    global $con;

    $query = "SELECT products.*, categories.name AS category_name
        FROM products
        INNER JOIN categories 
            ON products.category_id = categories.id
        WHERE products.category_id = '$category_id' 
        AND products.status = 1
    ";

    return mysqli_query($con, $query);
}

function getAllProducts() {
    global $con;
    $query = "SELECT * FROM products WHERE status = 1";
    return mysqli_query($con, $query);
}

function getCartItems()
{
    global $con;
    $user_id = $_SESSION['auth_user']['id'];
    $query ="SELECT c.id as cid, c.prod_id, c.prod_qty, c.total_price, p.id as pid, p.name, p.remaining_quantity as itemQty, p.image, p.d_price 
      FROM carts c, products p WHERE c.prod_id=p.id AND c.user_id='$user_id' ORDER BY c.id DESC ";
    return $query_run = mysqli_query($con, $query);

}

function getOdersItem()
{
    global $con;
    $user_id = $_SESSION['auth_user']['id'];

    $query = "SELECT * FROM orders WHERE user_id='$user_id' ";
    return $query_run = mysqli_query($con, $query);
}

function checkTrackingNoValid($tracking_no)
{
    global $con;
    $user_id = $_SESSION['auth_user']['id'];

    $query = "SELECT * FROM orders WHERE tracking_no='$tracking_no' AND user_id='$user_id' ";
    $result = mysqli_query($con, $query);

    if (!$result) {
        echo "Error: " . mysqli_error($con);
        return false;
    }

    return $result;
}

function getAllTrending() {
    global $con;
    $query = "SELECT * FROM products WHERE status = 1 AND trending = 1";
    return mysqli_query($con, $query);
}

function formatDate($datetime) {
    if (empty($datetime)) return 'N/A';

    $timestamp = strtotime($datetime);
    if (!$timestamp) return 'Invalid Date';

    return date('d F Y \a\t g:i A', $timestamp);
}
?>