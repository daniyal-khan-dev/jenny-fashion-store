<?php
declare(strict_types=1);

$host = "localhost";
$username = "root";
$password = "";
$database = "jenny_fashion_store";
$socket = "/home/runner/mysql.sock";

// Enable MySQLi exceptions (recommended in PHP 8+)
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    // Connect to MySQL server via socket
    $con = new mysqli();
    $con->real_connect($host, $username, $password, '', 3306, $socket);

    // Check if database exists
    $result = $con->query("SHOW DATABASES LIKE '{$database}'");

    if ($result->num_rows === 0) {
        // Create database if not exists
        $con->query("CREATE DATABASE `$database`");
    }

    // Select the database
    $con->select_db($database);

} catch (mysqli_sql_exception $e) {
    die("Database Error: " . $e->getMessage());
}


try {

    // USERS TABLE
    $con->query("
        CREATE TABLE IF NOT EXISTS `users` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `firstname` VARCHAR(50) NOT NULL,
            `lastname` VARCHAR(50) NOT NULL,
            `username` VARCHAR(50) NOT NULL UNIQUE,
            `image` VARCHAR(255) DEFAULT NULL,
            `email` VARCHAR(100) NOT NULL UNIQUE,
            `password` VARCHAR(255) NOT NULL,
            `work_phone_no` VARCHAR(20) DEFAULT NULL,
            `phone_no` VARCHAR(20) DEFAULT NULL,
            `city_name` VARCHAR(100) DEFAULT NULL,
            `address` VARCHAR(255) DEFAULT NULL,
            `user_role` TINYINT NOT NULL DEFAULT 0,
            `added_by` VARCHAR(100) NOT NULL DEFAULT 'System',
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_by` VARCHAR(100) NULL,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    
        ) 
    ");

    // CATEGORIES TABLE
    $con->query("
        CREATE TABLE IF NOT EXISTS `categories` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `name` VARCHAR(255) NOT NULL,
            `image` VARCHAR(100),
            `added_by` VARCHAR(100) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_by` VARCHAR(100) NULL,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");

    // PRODUCTS TABLE
    $con->query("
        CREATE TABLE IF NOT EXISTS `products` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `category_id` INT NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `s_description` TEXT NOT NULL,
            `description` LONGTEXT NOT NULL,
            `d_price` DECIMAL(10,2) NOT NULL,
            `price` DECIMAL(10,2) NOT NULL,
            `quantity` INT NOT NULL,
            `remaining_quantity` INT NULL,
            `status` TINYINT NOT NULL DEFAULT 0,
            `trending` TINYINT NOT NULL DEFAULT 0,
            `image` VARCHAR(255) NOT NULL,
            `added_by` VARCHAR(100) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            `updated_by` VARCHAR(100) NULL,
            `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE CASCADE
        )
    ");

    // CARTS TABLE
    $con->query("
        CREATE TABLE IF NOT EXISTS `carts` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT NOT NULL,
            `prod_id` INT NOT NULL,
            `prod_qty` INT NOT NULL,
            `total_price` DECIMAL(10,2) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`prod_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
        )
    ");

    // ORDERS TABLE
    $con->query("
        CREATE TABLE IF NOT EXISTS `orders` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `tracking_no` VARCHAR(255) NOT NULL,
            `user_id` INT NOT NULL,
            `name` VARCHAR(255) NOT NULL,
            `email` VARCHAR(255) NOT NULL,
            `work_phone_no` VARCHAR(20) NOT NULL,
            `cell_no` VARCHAR(20) NOT NULL,
            `date_of_birth` DATE NOT NULL,
            `address` TEXT NOT NULL,
            `remarks` VARCHAR(255),
            `total_price` DECIMAL(10,2) NOT NULL,
            `status` TINYINT NOT NULL DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
        )
    ");

    // ORDER ITEMS TABLE
    $con->query("
        CREATE TABLE IF NOT EXISTS `order_items` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `order_id` INT NOT NULL,
            `prod_id` INT NOT NULL,
            `qty` INT NOT NULL,
            `total_price` DECIMAL(10,2) NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
            FOREIGN KEY (`prod_id`) REFERENCES `products`(`id`) ON DELETE CASCADE
        )
    ");

    // CONTACT MESSAGES TABLE
    $con->query("
        CREATE TABLE IF NOT EXISTS `contact_messages` (
            `id` INT AUTO_INCREMENT PRIMARY KEY,
            `user_id` INT DEFAULT 0,
            `name` VARCHAR(100) NOT NULL,
            `email` VARCHAR(150) NOT NULL,
            `subject` VARCHAR(255) NOT NULL,
            `message` TEXT NOT NULL,
            `status` TINYINT NOT NULL DEFAULT 0,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");

} catch (mysqli_sql_exception $e) {
    die("Error creating tables: " . $e->getMessage());
}
?>
