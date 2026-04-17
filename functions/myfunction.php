<?php
include __DIR__ . '/../config/dbcon.php';
date_default_timezone_set('Asia/Karachi');
function getAll($table)
{
    global $con;
    $query = "SELECT * FROM $table  ORDER BY $table.id ASC";
    return $query_run = mysqli_query($con, $query);
}

function getProducts()
{
    global $con;
    $query = "SELECT products.*, categories.name AS category_name
    FROM products
    LEFT JOIN categories  ON products.category_id = categories.id";
    return $query_run = mysqli_query($con, $query);
}

function getByID($table, $id)
{
    global $con;
    $query = "SELECT * FROM $table WHERE id='$id'";
    return $query_run = mysqli_query($con, $query);
}

function getAllOrders()
{
    global $con;
    $query = "SELECT * FROM orders WHERE status='0' ";
    return $query_run = mysqli_query($con, $query);
}
function getOrderHistroy()
{
    global $con;
    $query = "SELECT * FROM orders WHERE status !='0' ";
    return $query_run = mysqli_query($con, $query);
}

function checkTrackingNoValid($tracking_no)
{
    global $con;

    $query = "SELECT * FROM orders WHERE tracking_no='$tracking_no' ";
    $result = mysqli_query($con, $query);

    if (!$result) {
        echo "Error: " . mysqli_error($con);
        return false;
    }

    return $result;
}

function getTopSellingProducts($limit = 10) {
    global $con;

    $query = "SELECT p.*, SUM(oi.qty) as total_sold 
              FROM products p
              JOIN order_items oi ON p.id = oi.prod_id
              JOIN orders o ON oi.order_id = o.id
              WHERE o.status = 1
              GROUP BY p.id
              ORDER BY total_sold DESC
              LIMIT $limit";

    return mysqli_query($con, $query);
}

function getTopClients($limit = 10) {
    global $con;
    
    $query = "SELECT u.username, SUM(oi.qty) as total_items_purchased 
              FROM users u
              JOIN orders o ON u.id = o.user_id
              JOIN order_items oi ON o.id = oi.order_id
               WHERE o.status = 1
              GROUP BY u.id
              ORDER BY total_items_purchased DESC
              LIMIT $limit";
    
    return mysqli_query($con, $query);
}

function getTotalUserCount() {
    global $con;
    $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM users WHERE user_role != 1"));
    return $row['c'];
}

function getUserCountForPreviousYear() {
    global $con;
    $py = date('Y') - 1;
    $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM users WHERE user_role != 1 AND YEAR(created_at) = $py"));
    return $row['c'];
}

function getNewUserCountForPreviousMonth($monthsAgo = 0) {
    global $con;
    $date  = date('Y-m-d', strtotime("$monthsAgo months"));
    $first = date('Y-m-01', strtotime($date));
    $last  = date('Y-m-t',  strtotime($date));
    $r = mysqli_query($con, "SELECT COUNT(*) as c FROM users WHERE user_role != 1 AND created_at BETWEEN '$first' AND '$last 23:59:59'");
    if ($r) { $row = mysqli_fetch_assoc($r); return $row['c']; }
    return 0;
}

function getNewUsersByMonth() {
    global $con;
    return mysqli_query($con, "SELECT YEAR(created_at) as year, MONTH(created_at) as month, COUNT(*) as new_users FROM users WHERE user_role != 1 GROUP BY YEAR(created_at), MONTH(created_at) ORDER BY year, month");
}

function getAllCustomers() {
    global $con;
    return mysqli_query($con, "SELECT * FROM users WHERE user_role = 0 ORDER BY created_at DESC");
}

function getAllAdmins() {
    global $con;
    return mysqli_query($con, "SELECT * FROM users WHERE user_role = 1 ORDER BY created_at ASC");
}

function getTotalOrderCount() {
    global $con;
    $query = "SELECT COUNT(*) as total_orders FROM orders WHERE status='0'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_orders'];
}

function calculatePercentageChange($previousValue, $currentValue)
{
    if ($previousValue == 0) {
        return 0; 
    }

    $percentageChange = (($currentValue - $previousValue) / $previousValue) * 100;
    return round($percentageChange, 2);
}

function getTotalOrderCountForPreviousMonth()
{
    global $con;
    $currentYear = date('Y');
    $currentMonth = date('m');
    $previousMonth = ($currentMonth - 1) <= 0 ? 12 : ($currentMonth - 1);
    $previousYear = ($currentMonth - 1) <= 0 ? ($currentYear - 1) : $currentYear;

    $query = "SELECT COUNT(*) as total_orders FROM orders WHERE status='0' AND
              YEAR(created_at) = $previousYear AND MONTH(created_at) = $previousMonth";

    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return $row['total_orders'];
}

// Function to calculate total revenue where status is 1
function getTotalRevenue()
{
    global $con;
    $query = "SELECT SUM(total_price) as total_revenue FROM orders WHERE status='1'";
    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return (float)($row['total_revenue'] ?? 0);
}

// Function to get total revenue for the previous month where status is 1
function getTotalRevenueForPreviousMonth()
{
    global $con;
    $currentYear = date('Y');
    $currentMonth = date('m');
    $previousMonth = ($currentMonth - 1) <= 0 ? 12 : ($currentMonth - 1);
    $previousYear = ($currentMonth - 1) <= 0 ? ($currentYear - 1) : $currentYear;

    $query = "SELECT SUM(total_price) as total_revenue FROM orders WHERE status='1' AND
              YEAR(created_at) = $previousYear AND MONTH(created_at) = $previousMonth";

    $result = mysqli_query($con, $query);
    $row = mysqli_fetch_assoc($result);
    return (float)($row['total_revenue'] ?? 0);
}




function formatDate($datetime) {
    if (empty($datetime)) return 'N/A';

    $timestamp = strtotime($datetime);
    if (!$timestamp) return 'Invalid Date';

    return date('d F Y \a\t g:i A', $timestamp);
}

function getTotalProductCount() {
    global $con;
    $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM products"));
    return $row['c'];
}

function getTotalCategoryCount() {
    global $con;
    $row = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM categories"));
    return $row['c'];
}

function getRecentOrders($limit = 5) {
    global $con;
    $limit = (int)$limit;
    return mysqli_query($con, "SELECT * FROM orders ORDER BY created_at DESC LIMIT $limit");
}

function getLowStockProducts($threshold = 5) {
    global $con;
    $threshold = (int)$threshold;
    return mysqli_query($con, "SELECT p.*, c.name as cat_name FROM products p LEFT JOIN categories c ON p.category_id=c.id WHERE p.remaining_quantity <= $threshold ORDER BY p.quantity ASC");
}

?>