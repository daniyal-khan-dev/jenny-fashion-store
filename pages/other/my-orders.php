<?php
session_start();
include_once __DIR__ . '/../../functions/userfunction.php';

if (!isset($_SESSION['auth'])) {
    $_SESSION['message2'] = "Please login to continue";
    header("Location: /jenny/login");
    exit();
}

include_once __DIR__ . '/../includes/header.php';

// Fetch ALL orders, then filter client-side via tabs
$ordersResult = getOdersItem();
$orders = [];
while ($row = mysqli_fetch_assoc($ordersResult)) {
    $orders[] = $row;
}
?>

<section class="my__account--section section--padding">
    <div class="container">
        <div class="my__account--section__inner border-radius-10 d-flex">
            <div class="account__left--sidebar">
                <h2 class="account__content--title mb-20">My Account</h2>
                <ul class="account__menu">
                    <li class="account__menu--list <?= $page == 'profile.php' ? 'active' : '' ?>">
                        <a href="<?= $routes['user']['profile'] ?>"><i class="fa-regular fa-user me-2"></i>Profile</a>
                    </li>
                    <li class="account__menu--list <?= $page == 'my-orders.php' ? 'active' : '' ?>">
                        <a href="<?= $routes['user']['myOrders'] ?>"><i class="fa-solid fa-box-open me-2"></i>My Orders</a>
                    </li>
                    <li class="account__menu--list">
                        <a href="<?= $routes['user']['logout'] ?>" class="text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i>Log Out</a>
                    </li>
                </ul>
            </div>

            <div class="account__wrapper" style="flex:1;">
                <div class="account__content">
                    <h2 class="account__content--title h3 mb-20">My Orders</h2>

                    <!-- Status Filter Tabs -->
                    <div style="display:flex;gap:.8rem;flex-wrap:wrap;margin-bottom:2.5rem;">
                        <?php
                        $tabs = [
                            ['label' => 'All Orders',    'val' => 'all',  'color' => '#3C3836', 'bg' => '#f4f1ef'],
                            ['label' => 'Pending',       'val' => '0',    'color' => '#2563eb', 'bg' => '#eff6ff'],
                            ['label' => 'Completed',     'val' => '1',    'color' => '#16a34a', 'bg' => '#f0fdf4'],
                            ['label' => 'Cancelled',     'val' => '2',    'color' => '#dc2626', 'bg' => '#fef2f2'],
                        ];
                        // count per status
                        $counts = ['all' => count($orders), '0' => 0, '1' => 0, '2' => 0];
                        foreach ($orders as $o) {
                            $counts[(string)$o['status']] = ($counts[(string)$o['status']] ?? 0) + 1;
                        }
                        foreach ($tabs as $tab):
                        ?>
                            <button class="order-tab" data-filter="<?= $tab['val'] ?>"
                                style="font-family:'Inter',sans-serif;font-size:1.3rem;font-weight:600;padding:.6rem 1.8rem;border-radius:50px;border:2px solid <?= $tab['color'] ?>22;background:<?= $tab['bg'] ?>;color:<?= $tab['color'] ?>;cursor:pointer;transition:all .2s ease;">
                                <?= $tab['label'] ?>
                                <span style="display:inline-flex;align-items:center;justify-content:center;min-width:20px;height:20px;padding:0 5px;border-radius:50px;background:<?= $tab['color'] ?>;color:#fff;font-size:1.05rem;font-weight:700;margin-left:.5rem;"><?= $counts[$tab['val']] ?? 0 ?></span>
                            </button>
                        <?php endforeach; ?>
                    </div>

                    <?php if (count($orders) > 0): ?>

                        <div id="orders-list" style="display:flex;flex-direction:column;gap:1.6rem;">
                            <?php foreach ($orders as $item):
                                $statuses = [
                                    0 => ['Under Process', '#2563eb'],
                                    1 => ['Completed',     '#16a34a'],
                                    2 => ['Cancelled',     '#dc2626'],
                                ];
                                [$label, $color] = $statuses[$item['status']] ?? ['Unknown', '#9a8f8b'];
                            ?>
                                <div class="order-card" data-status="<?= (int)$item['status'] ?>"
                                    style="background:#fff;border:1px solid rgba(201,127,95,.18);border-radius:16px;padding:2rem 2.4rem;box-shadow:0 2px 12px rgba(60,56,54,.06);display:flex;flex-wrap:wrap;align-items:center;gap:1.5rem;justify-content:space-between;transition:all .3s ease;">
                                    <div style="display:flex;align-items:center;gap:1.2rem;">
                                        <div style="width:48px;height:48px;background:linear-gradient(135deg,#FDF6F0,#EDD5C5);border-radius:12px;display:flex;align-items:center;justify-content:center;border:1px solid rgba(201,127,95,.2);">
                                            <svg width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="#C97F5F" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <div style="font-family:'Inter',sans-serif;font-size:1.5rem;font-weight:700;color:#3C3836;"><?= htmlspecialchars($item['tracking_no']) ?></div>
                                            <div style="font-family:'Inter',sans-serif;font-size:1.2rem;color:#9a8f8b;margin-top:.2rem;"><?= formatDate($item['created_at']) ?></div>
                                        </div>
                                    </div>

                                    <div style="text-align:center;">
                                        <div style="font-family:'Inter',sans-serif;font-size:1.15rem;color:#9a8f8b;margin-bottom:.2rem;">Total</div>
                                        <div style="font-family:'Inter',sans-serif;font-size:1.7rem;font-weight:700;color:#C97F5F;">$<?= number_format($item['total_price'], 2) ?></div>
                                    </div>

                                    <div style="text-align:center;">
                                        <div style="font-family:'Inter',sans-serif;font-size:1.15rem;color:#9a8f8b;margin-bottom:.4rem;">Status</div>
                                        <span style="display:inline-block;padding:.4rem 1.4rem;border-radius:50px;background:<?= $color ?>18;color:<?= $color ?>;font-family:'Inter',sans-serif;font-size:1.25rem;font-weight:700;"><?= $label ?></span>
                                    </div>

                                    <a href="<?= $routes['user']['order-details'] ?>?t=<?= urlencode($item['tracking_no']) ?>"
                                        style="display:inline-flex;align-items:center;gap:.6rem;background:linear-gradient(135deg,#C97F5F,#b56a4a);color:#fff;font-family:'Inter',sans-serif;font-size:1.25rem;font-weight:700;padding:.9rem 2rem;border-radius:50px;text-decoration:none;box-shadow:0 4px 16px rgba(201,127,95,.3);">
                                        View Details
                                        <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                        </svg>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <div id="no-orders-msg" style="display:none;text-align:center;padding:4rem 2rem;">
                            <div style="font-family:'Inter',sans-serif;font-size:1.5rem;color:#9a8f8b;">No orders in this category.</div>
                        </div>

                    <?php else: ?>
                        <div style="text-align:center;padding:6rem 2rem;">
                            <div style="width:80px;height:80px;background:linear-gradient(135deg,#FDF6F0,#EDD5C5);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 2rem;">
                                <svg width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="#C97F5F" stroke-width="1.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                            </div>
                            <h3 style="font-family:'Playfair Display',serif;font-size:2.4rem;color:#3C3836;margin-bottom:1rem;">No Orders Yet</h3>
                            <p style="font-family:'Inter',sans-serif;font-size:1.5rem;color:#9a8f8b;margin-bottom:2.5rem;">You haven't placed any orders yet. Start shopping!</p>
                            <a href="shop.php" class="primary__btn">Browse Shop</a>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</section>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>

<script>
    (function() {
        var tabs = document.querySelectorAll('.order-tab');
        var cards = document.querySelectorAll('.order-card');
        var noMsg = document.getElementById('no-orders-msg');

        function setActiveTab(btn) {
            tabs.forEach(function(t) {
                t.style.fontWeight = '600';
                t.style.boxShadow = 'none';
                t.style.transform = 'none';
            });
            btn.style.fontWeight = '800';
            btn.style.boxShadow = '0 4px 16px rgba(0,0,0,.1)';
            btn.style.transform = 'translateY(-1px)';
        }

        function filterOrders(filter) {
            var visible = 0;
            cards.forEach(function(card) {
                if (filter === 'all' || card.dataset.status === filter) {
                    card.style.display = 'flex';
                    visible++;
                } else {
                    card.style.display = 'none';
                }
            });
            if (noMsg) noMsg.style.display = visible === 0 ? '' : 'none';
        }

        tabs.forEach(function(tab) {
            tab.addEventListener('click', function() {
                setActiveTab(this);
                filterOrders(this.dataset.filter);
            });
        });

        // Default: activate first tab
        if (tabs.length > 0) setActiveTab(tabs[0]);
    })();
</script>