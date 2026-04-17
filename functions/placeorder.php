<?php
session_start();
include('../config/dbcon.php');
$routes = include_once __DIR__ . '/../routes.php';

if (!isset($_POST['placeOrderbtn'])) {
    header("Location: " . $routes['user']['checkout']);
    exit();
}

$name        = mysqli_real_escape_string($con, trim($_POST['name']));
$email       = mysqli_real_escape_string($con, trim($_POST['email']));
$workphoneno = mysqli_real_escape_string($con, trim($_POST['workphoneno']));
$cellno      = mysqli_real_escape_string($con, trim($_POST['cellno'] ?? ''));
$address     = mysqli_real_escape_string($con, trim($_POST['address']));
$remarks     = mysqli_real_escape_string($con, trim($_POST['remarks'] ?? ''));

if (empty($name) || empty($email) || empty($workphoneno) || empty($address)) {
    $_SESSION['message1'] = 'Name, email, phone and address are required.';
    header("Location: " . $routes['user']['checkout']);
    exit();
}

$user_id = (int) $_SESSION['auth_user']['id'];

// Fetch cart items with product price
$query     = "SELECT c.id as cid, c.prod_id, c.prod_qty, c.total_price,
                     p.name, p.image, p.d_price
              FROM carts c
              JOIN products p ON c.prod_id = p.id
              WHERE c.user_id = '$user_id'
              ORDER BY c.id DESC";
$query_run = mysqli_query($con, $query);

if (!$query_run || mysqli_num_rows($query_run) === 0) {
    $_SESSION['message1'] = 'Your cart is empty.';
    header("Location: " . $routes['user']['cart']);
    exit();
}

$items      = [];
$totalPrice = 0;
while ($row = mysqli_fetch_assoc($query_run)) {
    $items[]     = $row;
    $totalPrice += (float) $row['total_price'];
}

// Get next auto-increment to build sequential tracking number
$next_row    = mysqli_fetch_assoc(mysqli_query(
    $con,
    "SELECT AUTO_INCREMENT FROM information_schema.TABLES
     WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'orders'"
));
$next_id     = (int)($next_row['AUTO_INCREMENT'] ?? 1);
$tracking_no = 'TRK-' . date('Ymd') . '-' . str_pad($next_id, 5, '0', STR_PAD_LEFT);

$insert_order = "INSERT INTO orders
                    (tracking_no, user_id, name, email, work_phone_no, cell_no, address, remarks, total_price)
                 VALUES
                    ('$tracking_no', '$user_id', '$name', '$email',
                     '$workphoneno', '$cellno', '$address', '$remarks', '$totalPrice')";
$order_run = mysqli_query($con, $insert_order);

if (!$order_run) {
    $_SESSION['message1'] = 'Could not place order: ' . mysqli_error($con);
    header("Location: " . $routes['user']['checkout']);
    exit();
}

$order_id = mysqli_insert_id($con);

// Insert order items
foreach ($items as $citem) {
    $prod_id    = (int) $citem['prod_id'];
    $prod_qty   = (int) $citem['prod_qty'];
    $item_total = (float) $citem['total_price'];

    $ins_item = "INSERT INTO order_items (order_id, prod_id, qty, total_price) VALUES ('$order_id', '$prod_id', '$prod_qty', '$item_total')";
    mysqli_query($con, $ins_item);
}

// Clear cart
mysqli_query($con, "DELETE FROM carts WHERE user_id='$user_id'");

$_SESSION['message1'] = 'Order Placed Successfully';
header("Location: " . $routes['user']['myOrders']);
exit();
