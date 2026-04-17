<?php
$routes = include 'routes.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Jenny Fashion Store</title>
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <link rel="icon" type="image/png" href="assets/img/logo/logo.png">
    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

</head>
<body>
<div class="auth__wrap">

    <div id="successAlert" class="alert alert-success alert-dismissible fade position-fixed end-0 m-3 shadow"
        role="alert" style="display: none; z-index: 1055;">
        <span id="successAlertText"></span>
        <button type="button" class="btn-close" aria-label="Close" onclick="hideSuccessAlert()"></button>
    </div>

    <div id="errorAlert" class="alert alert-danger alert-dismissible fade position-fixed end-0 m-3 shadow"
        role="alert" style="display: none; z-index: 1055;">
        <span id="errorAlertText"></span>
        <button type="button" class="btn-close" aria-label="Close" onclick="hideErrorAlert()"></button>
    </div>

    <!-- LEFT BRAND PANEL -->
    <div class="auth__brand">
        <div class="auth__brand--circle1"></div>
        <div class="auth__brand--circle2"></div>
        <div class="auth__brand--inner">
            <div class="auth__brand--logo">
                <img src="assets/img/logo/logo.png" alt="Jenny Fashion Store">
            </div>
            <h1 class="auth__brand--name">Jenny Fashion<br>Store</h1>
            <p class="auth__brand--tagline">Your destination for premium cosmetics, fragrances &amp; jewellery — curated with love since 2009.</p>
            <ul class="auth__brand--features">
                <li>
                    <span class="feat-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
                    </span>
                    100% Authentic Products
                </li>
                <li>
                    <span class="feat-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4l3 3"/></svg>
                    </span>
                    Same-Day Dispatch
                </li>
                <li>
                    <span class="feat-icon">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round"><path d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z"/></svg>
                    </span>
                    50,000+ Happy Customers
                </li>
            </ul>
        </div>
    </div>

    <!-- RIGHT FORM PANEL -->
    <div class="auth__form--panel">
        <div class="auth__form--box">
            <a href="index.php" class="auth__back">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><path d="M19 12H5M12 5l-7 7 7 7"/></svg>
                Back to Store
            </a>
