<?php 
session_start();
include_once __DIR__ . '/../../functions/userfunction.php';

// Auth check before any HTML
if (!isset($_SESSION['auth'])) {
    $_SESSION['message2'] = "Please login to continue";
    header("Location: /jenny/login");
    exit();
}

if (!isset($_GET['t'])) {
    header('Location: /jenny/my-orders');
    exit();
}

$tracking_no = $_GET['t'];
$orderData   = checkTrackingNoValid($tracking_no);
if (!$orderData || mysqli_num_rows($orderData) === 0) {
    header('Location: /jenny/my-orders');
    exit();
}
$data = mysqli_fetch_assoc($orderData);

include_once __DIR__ . '/../includes/header.php';
?>

<section class="my__account--section section--padding">
    <div class="container">
        <div class="my__account--section__inner border-radius-10 d-flex">
            <div class="account__left--sidebar">
                <h2 class="account__content--title mb-20">My Account</h2>
                <ul class="account__menu">
                    <li class="account__menu--list <?= $page == "profile.php" ? 'active' : ''; ?>"><a href="<?= $routes['user']['profile'] ?>">Profile</a></li>
                    <li class="account__menu--list <?= ($page == "my-orders.php" || $page == "order-details.php") ? 'active' : ''; ?>"><a href="<?= $routes['user']['myOrders'] ?>">My Orders</a></li>
                    <li class="account__menu--list <?= $page == "logout.php" ? 'active' : ''; ?>"><a href="?= $routes['user']['logout'] ?>">Log Out</a></li>
                </ul>
            </div>

            <div class="account__wrapper" style="flex:1;">
                <div class="account__content">
                    <div class="d-flex align-items-center justify-content-between mb-30">
                        <h2 class="account__content--title h3 mb-0">Order Details</h2>
                        <a href="<?= $routes['user']['myOrders'] ?>" class="primary__btn" style="padding:.8rem 2rem!important;font-size:1.2rem!important;">← Back to Orders</a>
                    </div>

                    <!-- Tracking + Status bar -->
                    <div style="background:linear-gradient(135deg,#FDF6F0,#F5EEE8);border:1px solid rgba(201,127,95,.2);border-radius:14px;padding:1.8rem 2rem;margin-bottom:2.5rem;display:flex;flex-wrap:wrap;gap:1.5rem;align-items:center;justify-content:space-between;">
                        <div>
                            <div style="font-size:1.15rem;color:#9a8f8b;font-family:'Inter',sans-serif;margin-bottom:.3rem;">Tracking Number</div>
                            <div style="font-size:1.7rem;font-weight:700;color:#3C3836;font-family:'Inter',sans-serif;letter-spacing:.04em;"><?= htmlspecialchars($data['tracking_no']) ?></div>
                        </div>
                        <div>
                            <div style="font-size:1.15rem;color:#9a8f8b;font-family:'Inter',sans-serif;margin-bottom:.3rem;">Placed On</div>
                            <div style="font-size:1.4rem;font-weight:600;color:#3C3836;font-family:'Inter',sans-serif;"><?= formatDate($data['created_at']) ?></div>
                        </div>
                        <div>
                            <div style="font-size:1.15rem;color:#9a8f8b;font-family:'Inter',sans-serif;margin-bottom:.3rem;">Status</div>
                            <?php
                            $statuses = [0 => ['Under Process','#2563eb'], 1 => ['Completed','#16a34a'], 2 => ['Cancelled','#dc2626']];
                            [$label, $color] = $statuses[$data['status']] ?? ['Unknown','#9a8f8b'];
                            ?>
                            <span style="display:inline-block;padding:.4rem 1.4rem;border-radius:50px;background:<?= $color ?>18;color:<?= $color ?>;font-family:'Inter',sans-serif;font-size:1.3rem;font-weight:700;"><?= $label ?></span>
                        </div>
                        <div>
                            <div style="font-size:1.15rem;color:#9a8f8b;font-family:'Inter',sans-serif;margin-bottom:.3rem;">Total</div>
                            <div style="font-size:1.8rem;font-weight:700;color:#C97F5F;font-family:'Inter',sans-serif;">$<?= number_format($data['total_price'], 2) ?></div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Delivery Details -->
                        <div class="col-lg-6 mb-30">
                            <div style="background:#fff;border:1px solid rgba(201,127,95,.18);border-radius:16px;overflow:hidden;height:100%;">
                                <div style="background:linear-gradient(135deg,#C97F5F,#b56a4a);padding:1.4rem 2rem;">
                                    <h3 style="color:#fff;font-family:'Inter',sans-serif;font-size:1.5rem;font-weight:700;margin:0;">Delivery Details</h3>
                                </div>
                                <div style="padding:2rem;">
                                    <?php
                                    $fields = [
                                        ['Name',               $data['name']],
                                        ['Email',              $data['email']],
                                        ['Phone No',           $data['work_phone_no']],
                                        ['Secondary Phone',    $data['cell_no']],
                                    ];
                                    foreach ($fields as [$lbl, $val]):
                                    ?>
                                    <div style="display:flex;justify-content:space-between;padding:.9rem 0;border-bottom:1px solid #f0ebe6;">
                                        <span style="font-family:'Inter',sans-serif;font-size:1.3rem;font-weight:600;color:#9a8f8b;"><?= $lbl ?></span>
                                        <span style="font-family:'Inter',sans-serif;font-size:1.3rem;color:#3C3836;text-align:right;max-width:60%;"><?= htmlspecialchars($val ?: '—') ?></span>
                                    </div>
                                    <?php endforeach; ?>
                                    <div style="padding-top:1.2rem;">
                                        <div style="font-family:'Inter',sans-serif;font-size:1.3rem;font-weight:600;color:#9a8f8b;margin-bottom:.6rem;">Address</div>
                                        <div style="font-family:'Inter',sans-serif;font-size:1.3rem;color:#3C3836;line-height:1.7;"><?= nl2br(htmlspecialchars($data['address'])) ?></div>
                                    </div>
                                    <?php if (!empty($data['remarks'])): ?>
                                    <div style="padding-top:1.2rem;">
                                        <div style="font-family:'Inter',sans-serif;font-size:1.3rem;font-weight:600;color:#9a8f8b;margin-bottom:.6rem;">Remarks</div>
                                        <div style="font-family:'Inter',sans-serif;font-size:1.3rem;color:#3C3836;line-height:1.7;"><?= nl2br(htmlspecialchars($data['remarks'])) ?></div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div class="col-lg-6 mb-30">
                            <div style="background:#fff;border:1px solid rgba(201,127,95,.18);border-radius:16px;overflow:hidden;height:100%;">
                                <div style="background:linear-gradient(135deg,#3C3836,#2a2422);padding:1.4rem 2rem;">
                                    <h3 style="color:#fff;font-family:'Inter',sans-serif;font-size:1.5rem;font-weight:700;margin:0;">Items Ordered</h3>
                                </div>
                                <div style="padding:2rem;">
                                    <?php
                                    $order_id    = (int)$data['id'];
                                    $itemsQuery  = "SELECT oi.qty, oi.total_price AS item_total,
                                                           p.name AS product_name, p.image AS product_image, p.d_price
                                                    FROM order_items oi
                                                    JOIN products p ON oi.prod_id = p.id
                                                    WHERE oi.order_id = $order_id";
                                    $itemsResult = mysqli_query($con, $itemsQuery);
                                    if ($itemsResult && mysqli_num_rows($itemsResult) > 0):
                                        while ($item = mysqli_fetch_assoc($itemsResult)):
                                    ?>
                                    <div style="display:flex;align-items:center;gap:1.4rem;padding:1rem 0;border-bottom:1px solid #f0ebe6;">
                                        <img src="admin/assets/img/products/<?= htmlspecialchars($item['product_image']) ?>"
                                             style="width:60px;height:60px;object-fit:cover;border-radius:10px;border:1px solid rgba(201,127,95,.2);"
                                             alt="<?= htmlspecialchars($item['product_name']) ?>">
                                        <div style="flex:1;">
                                            <div style="font-family:'Inter',sans-serif;font-size:1.3rem;font-weight:600;color:#3C3836;"><?= htmlspecialchars($item['product_name']) ?></div>
                                            <div style="font-family:'Inter',sans-serif;font-size:1.2rem;color:#9a8f8b;">Qty: <?= (int)$item['qty'] ?></div>
                                        </div>
                                        <div style="font-family:'Inter',sans-serif;font-size:1.4rem;font-weight:700;color:#C97F5F;">$<?= number_format($item['item_total'], 2) ?></div>
                                    </div>
                                    <?php
                                        endwhile;
                                    else:
                                    ?>
                                    <p style="color:#9a8f8b;font-family:'Inter',sans-serif;">No items found.</p>
                                    <?php endif; ?>

                                    <div style="display:flex;justify-content:space-between;padding:1.4rem 0 0;margin-top:.5rem;">
                                        <span style="font-family:'Inter',sans-serif;font-size:1.3rem;color:#9a8f8b;">Shipping</span>
                                        <span style="font-family:'Inter',sans-serif;font-size:1.3rem;font-weight:600;color:#16a34a;">FREE</span>
                                    </div>
                                    <div style="display:flex;justify-content:space-between;padding:.6rem 0 0;border-top:2px solid rgba(201,127,95,.2);margin-top:.6rem;">
                                        <span style="font-family:'Inter',sans-serif;font-size:1.5rem;font-weight:700;color:#3C3836;">Total</span>
                                        <span style="font-family:'Inter',sans-serif;font-size:1.7rem;font-weight:700;color:#C97F5F;">$<?= number_format($data['total_price'], 2) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>