<?php

// Base URL (change this according to your project)
define("BASE_URL", "http://localhost/jenny");

return [
    "auth" => [
        "login" => BASE_URL . "/login",
        "signup-api" => BASE_URL . "/functions/authcode.php?action=signup",
        "signin-api" => BASE_URL . "/functions/authcode.php?action=signin",
    ],

    "user" => [
        "home" => BASE_URL . "/",
        "collection" => BASE_URL . "/collection",
        "shop" => BASE_URL . "/shop",
        "about" => BASE_URL . "/about",
        "contact" => BASE_URL . "/contact",
        "cart" => BASE_URL . "/cart",
        "checkout" => BASE_URL . "/checkout",
        "product" => BASE_URL . "/product",
        "c-product" => BASE_URL . "/c-product",
        "profile" => BASE_URL . "/profile",
        "myOrders" => BASE_URL . "/my-orders",
        "order-details" => BASE_URL . "/order-details",
        "logout" => BASE_URL . "/logout",
    ],

    "admin" => [
        "dashboard" => BASE_URL . "/admin/dashboard",
        "backup" => BASE_URL . "/admin/backup",
        "adminUser" => [
            "page" => BASE_URL . "/admin/admins",
            "get-api" => BASE_URL . "/admin/controller/code.php?action=getAdmins",
            "add-api" => BASE_URL . "/admin/controller/code.php?action=addAdmins",
            "update-api" => BASE_URL . "/admin/controller/code.php?action=updateAdmins",
            "delete-api" => BASE_URL . "/admin/controller/code.php?action=deleteAdmins",
        ],
        "category" => [
            "page" => BASE_URL . "/admin/category",
            "get-api" => BASE_URL . "/admin/controller/code.php?action=getCategories",
            "add-api" => BASE_URL . "/admin/controller/code.php?action=addCategory",
            "update-api" => BASE_URL . "/admin/controller/code.php?action=updateCategory",
            "delete-api" => BASE_URL . "/admin/controller/code.php?action=deleteCategory",
        ],
        "product" => [
            "page" => BASE_URL . "/admin/products",
            "get-api" => BASE_URL . "/admin/controller/code.php?action=getProducts",
            "add-api" => BASE_URL . "/admin/controller/code.php?action=addProduct",
            "update-api" => BASE_URL . "/admin/controller/code.php?action=updateProduct",
            "delete-api" => BASE_URL . "/admin/controller/code.php?action=deleteProduct",
        ],
        "orders" => [
            "active" => BASE_URL . "/admin/orders",
            "details" => BASE_URL . "/admin/order-details",
            "update-api" => BASE_URL . "/admin/controller/code.php?action=updateOrderStatus",
            "history" => BASE_URL . "/admin/order-history",
            "co-details" => BASE_URL . "/admin/co-details",
        ],
        'contact' => BASE_URL . '/admin/contact',
        "users" => BASE_URL . "/admin/users",
    ],
];