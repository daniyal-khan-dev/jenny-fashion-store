<?php
session_start();
include_once __DIR__ . '/../../functions/userfunction.php';
// Auth check before any HTML
if (!isset($_SESSION['auth'])) {
    $_SESSION['message2'] = "Please login to continue";
    header("Location: /jenny/login");
    exit();
}

if (isset($_SESSION['user_role']) && $_SESSION['user_role'] != 0) {
    header("Location: /jenny/admin/dashboard");
    exit();
}

$items      = getCartItems();
$totalPrice = 0;
$itemCount  = 0;
$cartArr    = [];
while ($citem = mysqli_fetch_assoc($items)) {
    $cartArr[] = $citem;
}
if (empty($cartArr)) {
    $_SESSION['message2'] = "No items in cart.";
    header("Location: /jenny/");
    exit();
}
include_once __DIR__ . '/../includes/header.php';
$checkout_user = [];
if (isset($_SESSION['auth_user']['id'])) {
    $uid = (int) $_SESSION['auth_user']['id'];
    $uq  = mysqli_query($con, "SELECT username, email, work_phone_no, phone_no, address FROM users WHERE id='$uid' LIMIT 1");
    if ($uq && mysqli_num_rows($uq) > 0) {
        $checkout_user = mysqli_fetch_assoc($uq);
    }
}

function co($key)
{
    global $checkout_user;
    return htmlspecialchars($checkout_user[$key] ?? '');
}
?>

<section class="checkout__section section--padding">
    <div class="container">
        <form action="functions/placeorder.php" method="POST">
            <div class="row g-5">
                <div class="col-lg-7">
                    <div class="billing-form">
                        <div class="bf-head">
                            <div class="head-svg">
                                <svg width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="#fff" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                            </div>
                            <h2>Billing Details</h2>
                        </div>

                        <div class="bf-body row">
                            <div class="col-sm-6">
                                <div class="checkout-field-group">
                                    <label class="checkout-label">Full Name <span class="required">*</span></label>
                                    <input class="checkout-input" oninput="onlyAlphabets(this)" placeholder="Your full name" name="name" maxlength="30" minlength="2" type="text" required value="<?= co('username') ?>">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="checkout-field-group">
                                    <label class="checkout-label">Email Address <span class="required">*</span></label>
                                    <input class="checkout-input" oninput="validateEmail(this)" placeholder="your@email.com" name="email" maxlength="40" minlength="2" type="email" required value="<?= co('email') ?>">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="checkout-field-group">
                                    <label class="checkout-label">Phone No <span class="required">*</span></label>
                                    <input class="checkout-input" oninput="validatePhone(this)" placeholder="e.g. +923001234567" min="11" max="13" name="workphoneno" id="workphoneno" type="text" required value="<?= co('work_phone_no') ?>">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="checkout-field-group">
                                    <label class="checkout-label">Secondary Phone</label>
                                    <input class="checkout-input" placeholder="Optional" name="cellno" id="cellno" type="text" value="<?= co('phone_no') ?>">
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="checkout-field-group">
                                    <label class="checkout-label">Delivery Address <span class="required">*</span></label>
                                    <textarea class="checkout-input" placeholder="Street, City, Country" id="address" name="address" required maxlength="100" style="height:14rem;resize:none;"><?= co('address') ?></textarea>
                                    <div class="d-flex justify-content-between">
                                        <span class="char-counter" id="addressCount">0 / 100</span>
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="checkout-field-group">
                                    <label class="checkout-label">Order Remarks</label>
                                    <textarea class="checkout-input" placeholder="Any special instructions?" id="remarks" name="remarks" maxlength="100" style="height:14rem;resize:none;"></textarea>
                                    <div class="d-flex justify-content-between">
                                        <span class="char-counter" id="remarksCount">0 / 100</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bf-footer">
                            <a href="<?= $routes['user']['cart'] ?>">
                                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                                Return to Cart
                            </a>
                        </div>
                    </div>
                </div>

                <div class="col-lg-5">
                    <div class="checkout-summary">
                        <div class="cs-head">
                            <h3>
                                <svg width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.7)" stroke-width="2" style="vertical-align:middle;margin-right:.5rem;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                Your Order
                            </h3>
                        </div>

                        <!-- Items -->
                        <div class="cs-body">
                            <?php
                            foreach ($cartArr as $citem):
                                $totalPrice += (float)$citem['total_price'];
                                $itemCount++;
                            ?>
                                <div class="cs-sub-body">
                                    <div class="cssb-img-div">
                                        <img src="admin/assets/img/products/<?= htmlspecialchars($citem['image']) ?>" alt="<?= htmlspecialchars($citem['name']) ?>">
                                        <span><?= (int)$citem['prod_qty'] ?></span>
                                    </div>

                                    <div class="cssb-detail-div">
                                        <div class="cssb-name"><?= htmlspecialchars($citem['name']) ?></div>
                                        <div class="cssb-price">$<?= number_format((float)$citem['d_price'], 2) ?> each</div>
                                    </div>
                                    
                                    <div class="cssb-total-price">$<?= number_format((float)$citem['total_price'], 2) ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Totals -->
                        <div class="cs-footer">
                            <div class="sum-sub-head">
                                <span class="label">Subtotal (<?= $itemCount ?> items)</span>
                                <span class="detail">$<?= number_format($totalPrice, 2) ?></span>
                            </div>

                            <div class="sum-sub-head">
                                <span class="label">Shipping</span>
                                <span class="shipping">FREE</span>
                            </div>

                            <div class="sum-sub-head2">
                                <span class="span-1">Total</span>
                                <span class="span-2">$<?= number_format($totalPrice, 2) ?></span>
                            </div>

                            <button class="btn-placeorder" name="placeOrderbtn" type="submit" style="width:100%;font-size:1.5rem;border:none;">
                                Place Order
                            </button>

                            <p>🔒 Your information is secure</p>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>