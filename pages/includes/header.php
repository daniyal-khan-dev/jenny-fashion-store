<?php
$routes = include_once __DIR__ . '/../../routes.php';

if (isset($_SESSION['auth']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] == 1) {
    header("Location: " .  $routes['admin']['dashboard']);
    exit();
}

$page = substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);
$category = isset($_GET['category']) ? htmlspecialchars($_GET['category']) : 'collection';
$cproduct = (isset($_GET['category']) && isset($_GET['product'])); 
$product = isset($_GET['product']) ? htmlspecialchars($_GET['product']) : 'Shop';
$tracking = isset($_GET['t']) ? htmlspecialchars($_GET['t']) : 't';

// Ensure DB connection is available regardless of include order
include_once __DIR__ . '/../../config/dbcon.php';
include_once __DIR__ . '/../../middleware/usermiddlware.php';

// Cart item count for badge
$_cart_count = 0;
if (isset($_SESSION['auth_user']['id'])) {
    $_uid = (int) $_SESSION['auth_user']['id'];
    $_cr  = mysqli_query($con, "SELECT COUNT(*) as cnt FROM carts WHERE user_id='$_uid'");
    if ($_cr) $_cart_count = (int) mysqli_fetch_assoc($_cr)['cnt'];
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Jenny Fashion Store</title>
    <meta name="description" content="Morden Bootstrap HTML5 Template">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate, max-age=0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="0" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="assets/img/favicon.jpg">
    <!-- GOGGLE FONT - CDN -->
    <link href="https://fonts.googleapis.com/css2?family=Frank+Ruhl+Libre:wght@300;400;500;700;900&amp;family=Karma:wght@300;400;500;600;700&amp;display=swap" rel="stylesheet">
    
    <!-- FONT AWESOME - CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
    <!-- BOOTSTRAP - CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    
    <!-- SWEET ALERT - CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <!-- SWIPER - CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Swiper/7.4.1/swiper-bundle.min.css" integrity="sha512-kftIhGv/k/oRHmfDRDEb1MxlaWlW4tiz21rx0yNC2zUWM2n4nxRtX1z3Ijmu54he3Yf9sBX4skJaCe3LTJV1rQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 
    <!-- Custom Style CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body>
    <div id="preloader">
        <div id="ctn-preloader" class="ctn-preloader">
            <div class="animation-preloader">
                <div class="spinner"></div>
                <div class="txt-loading">
                    <span data-text-preloader="L" class="letters-loading">
                        L
                    </span>

                    <span data-text-preloader="O" class="letters-loading">
                        O
                    </span>

                    <span data-text-preloader="A" class="letters-loading">
                        A
                    </span>

                    <span data-text-preloader="D" class="letters-loading">
                        D
                    </span>

                    <span data-text-preloader="I" class="letters-loading">
                        I
                    </span>

                    <span data-text-preloader="N" class="letters-loading">
                        N
                    </span>

                    <span data-text-preloader="G" class="letters-loading">
                        G
                    </span>
                </div>
            </div>
            <div class="loader-section section-left"></div>
            <div class="loader-section section-right"></div>
        </div>
    </div>

    <header class="main-header border-bottom sticky-top">
        <nav class="navbar navbar-expand-lg navbar-light container">
            <!-- Mobile Offcanvas Toggle -->
            <button class="btn btn-toggle d-lg-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileMenu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Logo -->
            <a class="navbar-brand d-flex align-items-center" href="<?= $routes['user']['home'] ?>">
                <img src="assets/img/logo/logo.png" width="80" alt="logo">
            </a>

            <!-- Desktop Menu -->
            <div class="collapse navbar-collapse justify-content-center d-none d-lg-flex">
                <ul class="navbar-nav gap-3">
                    <li class="nav-item">
                        <a class="nav-link <?= $page == "index.php" ? 'active' : ''; ?>" href="<?= $routes['user']['home'] ?>">Home</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= $page == "collection.php" ? 'active' : ''; ?>" href="<?= $routes['user']['collection'] ?>">Collection</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= $page == "shop.php" ? 'active' : ''; ?>" href="<?= $routes['user']['shop'] ?>">Shop</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= $page == "about.php" ? 'active' : ''; ?>" href="<?= $routes['user']['about'] ?>">About</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="<?= $routes['user']['contact'] ?>">Contact</a>
                    </li>
                </ul>
            </div>

            <!-- Right Side -->
            <div class="nav-right d-flex align-items-center gap-3">
                <?php if (isset($_SESSION['auth'])) { ?>
                    <!-- Cart -->
                    <a href="<?= $routes['user']['cart'] ?>" class="btn position-relative btn-cart">
                        <svg width="20" height="20" viewBox="0 0 17 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.25 7.66667V4.33333C12.25 3.44928 11.8549 2.60143 11.1517 1.97631C10.4484 1.35119 9.49456 1 8.5 1C7.50544 1 6.55161 1.35119 5.84835 1.97631C5.14509 2.60143 4.75 3.44928 4.75 4.33333V7.66667M1.9375 6H15.0625L16 16H1L1.9375 6Z" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span id="cart-count" style="<?php echo $_cart_count > 0 ? 'display:block;' : 'display:none;'; ?>" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?= $_cart_count ?>
                        </span>
                    </a>

                    <!-- Account Dropdown -->
                    <?php
                        $__uname = $_SESSION['auth_user']['username'] ?? 'User';
                        $__email = $_SESSION['auth_user']['email'] ?? '';
                        $__init  = strtoupper(mb_substr($__uname, 0, 1));
                    ?>
                    <div class="dropdown nav-account-dropdown">
                        <button class="nav-account-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="nav-account-avatar"><?= $__init ?></span>
                            <span class="nav-account-name d-none d-lg-inline"><?= htmlspecialchars($__uname) ?></span>
                            <span id="cart-count" style="<?php echo $_cart_count > 0 ? 'display:block;' : 'display:none;'; ?> left: 75% !important; top: -3% !important;" class="mbl-cart-count position-absolute translate-middle badge rounded-pill bg-danger">
                                <?= $_cart_count ?>
                            </span>
                            <i class="fa-solid fa-chevron-down nav-account-caret d-none d-lg-inline"></i>
                        </button>

                        <div class="dropdown-menu dropdown-menu-end nav-account-menu">
                            <div class="nav-account-menu__header">
                                <div class="nav-account-menu__avatar"><?= $__init ?></div>
                                <div class="nav-account-menu__info">
                                    <div class="nav-account-menu__name"><?= htmlspecialchars($__uname) ?></div>
                                    <div class="nav-account-menu__email"><?= htmlspecialchars($__email) ?></div>
                                </div>
                            </div>
                            
                            <div class="nav-account-menu__divider"></div>

                            <a class="nav-account-menu__item" href="<?= $routes['user']['profile'] ?>">
                                <span class="nav-account-menu__icon"><i class="fa-regular fa-user"></i></span>
                                My Profile
                            </a>

                            <a class="nav-account-menu__item" href="<?= $routes['user']['myOrders'] ?>">
                                <span class="nav-account-menu__icon"><i class="fa-solid fa-box-open"></i></span>
                                My Orders
                            </a>

                            <a class="nav-account-menu__item" href="<?= $routes['user']['cart'] ?>">
                                <span class="nav-account-menu__icon"><i class="fa-solid fa-cart-shopping"></i></span>
                                My Cart
                                <?php if ($_cart_count > 0): ?>
                                    <span class="nav-account-menu__badge"><?= $_cart_count ?></span>
                                <?php endif; ?>
                            </a>

                            <div class="nav-account-menu__divider"></div>

                            <a class="nav-account-menu__item nav-account-menu__item--danger" href="<?= $routes['user']['logout'] ?>">
                                <span class="nav-account-menu__icon"><i class="fa-solid fa-right-from-bracket"></i></span>
                                Sign Out
                            </a>
                        </div>
                    </div>
                <?php } else { ?>
                    <a href="<?= $routes['auth']['login'] ?>" class="btn-login">
                        Login / Register
                    </a>
                <?php } ?>
            </div>
        </nav>

        <div class="offcanvas offcanvas-start" tabindex="-1" id="mobileMenu">
            <div class="offcanvas-inner">
                <div class="offcanvas-header">
                    <a class="offcanvas-logo" href="index.html">
                        <img src="assets/img/logo/logo.png" alt="Logo-img" width="158" height="36">
                    </a>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                </div>

                <div class="offcanvas-body">
                    <ul class="navbar-nav">
                        <li class="nav-item"><a class="nav-link" href="<?= $routes['user']['home'] ?>">Home</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= $routes['user']['collection'] ?>">Collection</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= $routes['user']['shop'] ?>">Shop</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= $routes['user']['about'] ?>">About</a></li>
                        <li class="nav-item"><a class="nav-link" href="<?= $routes['user']['contact'] ?>">Contact</a></li>

                        <?php if (isset($_SESSION['auth'])) { ?>
                            <li class="nav-item">
                                <a class="nav-link text-danger" href="<?= $routes['user']['logout'] ?>">Logout</a>
                            </li>
                        <?php } else { ?>
                            <li class="nav-item">
                                <a class="nav-link" href="<?= $routes['auth']['login'] ?>">
                                    <i class="fa-solid fa-user"></i>
                                    Login / Register
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <main class="main__content_wrapper">
        <div id="successAlert" class="alert alert-success alert-dismissible fade position-fixed end-0 m-3 shadow" role="alert" style="display: none; z-index: 1055;">
            <span id="successAlertText"></span>
            <button type="button" class="btn-close" aria-label="Close" onclick="hideSuccessAlert()"></button>
        </div>
        
        <div id="errorAlert" class="alert alert-danger alert-dismissible fade position-fixed end-0 m-3 shadow" role="alert" style="display: none; z-index: 1055;">
            <span id="errorAlertText"></span>
            <button type="button" class="btn-close" aria-label="Close" onclick="hideErrorAlert()"></button>
        </div>
        
        <!-- BREAD CRUM SECTION START -->
        <?php if ($page !== "index.php"): ?>
            <nav class="breadcrumb-section">
                <div class="container">
                    <div class="row">
                        <div class="col text-center">
                            <ol class="breadcrumb justify-content-center mb-0">
                                <li class="breadcrumb-item">
                                    <a href="<?= $routes['user']['home'] ?>" class="text-decoration-none">
                                        Home
                                    </a>
                                </li>

                                <?php if ($page == "collection.php"): ?>
                                    <li class="breadcrumb-item active" aria-current="page">Collection</li>
                                <?php elseif ($page == "shop.php"): ?>
                                    <li class="breadcrumb-item active" aria-current="page">Shop</li>
                                <?php elseif ($page == "category.product.php"): ?>
                                    <li class="breadcrumb-item">
                                        <a href="<?= $routes['user']['collection'] ?>" class="text-decoration-none">
                                            Collection
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page"><?= $category ?></li>
                                <?php elseif ($page == "product-view.php"): ?>
                                    <?php if ($cproduct): ?>
                                        <li class="breadcrumb-item">
                                            <a href="<?= $routes['user']['collection'] ?>" class="text-decoration-none">
                                                Collection
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page"><?= $category ?></li>
                                        <li class="breadcrumb-item active" aria-current="page"><?= $product ?></li>
                                    <?php elseif ($product): ?>
                                        <li class="breadcrumb-item">
                                            <a href="<?= $routes['user']['shop'] ?>" class="text-decoration-none">
                                                Shop
                                            </a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page"><?= $product ?></li>
                                    <?php endif; ?>
                                <?php elseif ($page == "about.php"): ?>
                                    <li class="breadcrumb-item active" aria-current="page">About</li>
                                <?php elseif ($page == "contact.php"): ?>
                                    <li class="breadcrumb-item active" aria-current="page">Contact</li>
                                <?php elseif ($page == "cart.php"): ?>
                                    <li class="breadcrumb-item active" aria-current="page">Cart</li>
                                <?php elseif ($page == "checkout.php"): ?>
                                    <li class="breadcrumb-item active" aria-current="page">Checkout</li>
                                <?php elseif ($page == "profile.php"): ?>
                                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                                <?php elseif ($page == "my-orders.php"): ?>
                                    <li class="breadcrumb-item active" aria-current="page">My Orders</li>
                                <?php elseif ($page == "order-details.php"): ?>
                                    <li class="breadcrumb-item">
                                        <a href="<?= $routes['user']['myOrders'] ?>" class="text-decoration-none">
                                            My Orders
                                        </a>
                                    </li>

                                    <li class="breadcrumb-item active" aria-current="page"><?= $tracking ?></li>
                                <?php endif; ?>
                            </ol>
                        </div>
                    </div>
                </div>
            </nav>
        <?php endif; ?>
        <!-- BREAD CRUM SECTION END -->