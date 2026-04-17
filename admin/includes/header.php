<?php
include_once __DIR__ . '/../../middleware/adminmiddleware.php';
include_once __DIR__ . '/../../functions/myfunction.php';

$routes = include_once __DIR__ . '/../../routes.php';

$page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$tracking = isset($_GET['t']) ? htmlspecialchars($_GET['t']) : 't';
$cotracking = isset($_GET['co-t']) ? htmlspecialchars($_GET['co-t']) : 'co-t';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="0" />
  <title> Jenny fashion Store </title>
  <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.jpg">

  <!-- GOGGLE FONTS - CDN -->
  <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700,900|Roboto+Slab:400,700" />

  <!-- FONT AWESOME - CDN -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />

  <!-- BOOTSTRAP - CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">

  <!-- CUSTOM - CSS -->
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body class="g-sidenav-show  bg-gray-200">
  <aside id="sidenav-main" class="sidenav navbar-side d-block position-fixed top-0 bottom-0 w-100 overflow-auto shadow-none ms-3 my-3 start-0 border-0 border-radius-xl p-0">
    <div class="sidenav-header">
      <i class="fas fa-times p-3 cursor-pointer text-white opacity-5 position-absolute end-0 top-0 d-none d-xl-none" id="iconSidenav"></i>
      <a class="navbar-brand m-0 d-flex align-items-center gap-2" href="dashboard.php">
        <img src="../assets/img/logo/logo.png" alt="Jenny Logo">

        <span class="text-white fw-bold">
          Jenny Fashion
          <br>
          <small class="ms-1 opacity-75 fw-normal" style="font-size:.75em;">
            Admin Panel
          </small>
        </span>
      </a>
    </div>
    <hr class="horizontal light mt-0 mb-2">

    <!-- Menu -->
    <div class="collapse navbar-collapse w-auto d-block overflow-auto align-item-center" id="sidenav-collapse-main">
      <ul class="navbar-nav d-flex flex-direction-column mb-0">

        <li class="nav-item">
          <a href="<?= $routes['admin']['dashboard'] ?>" class="nav-link text-white <?= $page == 'dashboard.php' ? 'active bg-primary' : ''; ?>">
            <i class="fa-solid fa-gauge-high me-2"></i>
            <span class="nav-link-text">Dashboard</span>
          </a>
        </li>

        <li class="nav-item">
          <h6>Catalogue</h6>
        </li>

        <li class="nav-item">
          <a href="<?= $routes['admin']['category']['page'] ?>" class="nav-link text-white <?= $page == 'category.php' ? 'active bg-primary' : ''; ?>">
            <i class="fa-solid fa-layer-group me-2"></i>
            <span class="nav-link-text">Category</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $routes['admin']['product']['page'] ?>" class="nav-link text-white <?= $page == 'products.php' ? 'active bg-primary' : ''; ?>">
            <i class="fa-solid fa-box-open me-2"></i>
            <span class="nav-link-text">Products</span>
          </a>
        </li>

        <li class="nav-item">
          <h6>Orders</h6>
        </li>

        <li class="nav-item">
          <a href="<?= $routes['admin']['orders']['active'] ?>" class="nav-link text-white <?= ($page == 'orders.php' || $page == 'order-details.php') ? 'active bg-primary' : ''; ?>">
            <i class="fa-solid fa-truck-fast me-2"></i>
            <span class="nav-link-text">Active Orders</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $routes['admin']['orders']['history'] ?>" class="nav-link text-white <?= ($page == 'order-history.php' || $page == 'co-details.php') ? 'active bg-primary' : ''; ?>">
            <i class="fa-solid fa-clock-rotate-left me-2"></i>
            <span class="nav-link-text">Orders History</span>
          </a>
        </li>

        <li class="nav-item">
          <h6>Management</h6>
        </li>

        <li class="nav-item">
          <a href="<?= $routes['admin']['users'] ?>" class="nav-link text-white <?= $page == 'users.php' ? 'active bg-primary' : ''; ?>">
            <i class="fa-solid fa-users me-2"></i>
            <span class="nav-link-text">Customers</span>
          </a>
        </li>

        <li class="nav-item">
          <a href="<?= $routes['admin']['adminUser']['page'] ?>" class="nav-link text-white <?= $page == 'admins.php' ? 'active bg-primary' : ''; ?>">
            <i class="fa-solid fa-user-shield me-2"></i>
            <span class="nav-link-text">Admins</span>
          </a>
        </li>

        <li class="nav-item">
          <h6>Support</h6>
        </li>

        <li class="nav-item">
          <?php
          $unreadMsgs = 0;
          $umq = mysqli_query($con, "SELECT COUNT(*) as cnt FROM contact_messages WHERE status=0");
          if ($umq) $unreadMsgs = (int)mysqli_fetch_assoc($umq)['cnt'];
          ?>
          <a href="<?= $routes['admin']['contact'] ?>" class="nav-link text-white <?= $page == 'contact-messages.php' ? 'active bg-primary' : ''; ?>">
            <i class="fa-regular fa-envelope me-2"></i>
            <span class="nav-link-text">Messages</span>
            <?php if ($unreadMsgs > 0): ?>
              <span class="badge bg-danger ms-auto"><?= $unreadMsgs ?></span>
            <?php endif; ?>
          </a>
        </li>
      </ul>
    </div>

    <div class="sidenav-footer position-absolute w-100 bottom-0">
      <div class="mx-3">
        <a class="btn btn-outline-primary w-100" href="<?= $routes['admin']['backup'] ?>">
          <i class="fa-solid fa-database me-1"></i> Database Backup
        </a>
        <a class="btn bg-gradient-primary w-100 mt-2" href="<?= $routes['user']['logout'] ?>">
          <i class="fa-solid fa-right-from-bracket me-1"></i> Logout
        </a>
      </div>
    </div>
  </aside>

  <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">

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

    <nav class="navbar navbar-main navbar-expand-lg bg-white shadow-sm px-0 mx-4">
      <div class="container-fluid py-1 px-3">
        <?php if ($page !== "index.php"): ?>
          <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 bg-transparent p-0">
              <?php if ($page == "dashboard.php"): ?>
                <li class="breadcrumb-item active text-dark" aria-current="page">Dashboard</li>
              <?php elseif ($page == "category.php"): ?>
                <li class="breadcrumb-item active text-dark" aria-current="page">Category</li>
              <?php elseif ($page == "products.php"): ?>
                <li class="breadcrumb-item active text-dark" aria-current="page">Products</li>
              <?php elseif ($page == "orders.php"): ?>
                <li class="breadcrumb-item active text-dark" aria-current="page">Active Orders</li>
              <?php elseif ($page == "order-details.php"): ?>
                <li class="breadcrumb-item">
                  <a href="<?= $routes['admin']['orders']['active'] ?>" class="text-decoration-none">
                    Active Orders
                  </a>
                </li>

                <li class="breadcrumb-item active" aria-current="page"><?= $tracking ?></li>
              <?php elseif ($page == "co-details.php"): ?>
                <li class="breadcrumb-item">
                  <a href="<?= $routes['admin']['orders']['co-details'] ?>" class="text-decoration-none">
                    Orders Histroy
                  </a>
                </li>

                <li class="breadcrumb-item active" aria-current="page"><?= $cotracking ?></li>
              <?php elseif ($page == "order-history.php"): ?>
                <li class="breadcrumb-item active text-dark" aria-current="page">Orders History</li>
              <?php elseif ($page == "users.php"): ?>
                <li class="breadcrumb-item active text-dark" aria-current="page">Customers</li>
              <?php elseif ($page == "admins.php"): ?>
                <li class="breadcrumb-item active text-dark" aria-current="page">Admins</li>
              <?php elseif ($page == "contact.php"): ?>
                <li class="breadcrumb-item active text-dark" aria-current="page">Contact</li>
              <?php endif; ?>
            </ol>
          </nav>
        <?php endif; ?>

        <!-- Toggle + Right Menu -->
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
          <ul class="navbar-nav justify-content-end ms-md-auto pe-md-3 d-flex align-items-center">
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
              <a href="javascript:;" class="nav-link text-body p-0 mx-2" id="iconNavbarSidenav">
                <div class="sidenav-toggler-inner">
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                  <i class="sidenav-toggler-line"></i>
                </div>
              </a>
            </li>
            <li class="nav-item d-flex align-items-center">
              <a href="<?= $routes['user']['logout'] ?>" class="nav-link text-body font-weight-bold px-0">
                <i class="fa-solid fa-right-from-bracket me-1"></i>
                <span class="d-sm-inline d-none">Logout</span>
              </a>
            </li>
          </ul>
        </div>
      </div>
    </nav>