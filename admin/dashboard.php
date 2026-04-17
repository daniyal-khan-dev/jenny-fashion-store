<?php
include('includes/header.php');

$topSellingProducts = getTopSellingProducts(10);
$topClients         = getTopClients();
$recentOrders       = getRecentOrders(5);
$lowStock           = getLowStockProducts(5);
$totalProducts      = getTotalProductCount();
$totalCategories    = getTotalCategoryCount();

global $con;

// Orders by month (last 12 months)
$orderMonthData = [];
$result = mysqli_query($con, "SELECT DATE_FORMAT(created_at,'%b %Y') as lbl, COUNT(*) as cnt FROM orders GROUP BY YEAR(created_at), MONTH(created_at) ORDER BY MIN(created_at) DESC LIMIT 12");
while ($r = mysqli_fetch_assoc($result)) {
    $orderMonthData[] = $r;
}
$orderMonthData = array_reverse($orderMonthData);

// Revenue by category
$catRevData = [];
$result2 = mysqli_query($con, "SELECT c.name, SUM(p.d_price * oi.qty) AS revenue FROM orders o LEFT JOIN order_items oi ON o.id=oi.order_id LEFT JOIN products p ON oi.prod_id=p.id LEFT JOIN categories c ON p.category_id=c.id WHERE o.status='1' GROUP BY c.id");
while ($r = mysqli_fetch_assoc($result2)) {
    $catRevData[] = $r;
}

$orderLabels = json_encode(array_column($orderMonthData, 'lbl'));
$orderCounts = json_encode(array_column($orderMonthData, 'cnt'));
$catLabels   = json_encode(array_column($catRevData, 'name'));
$catRevs     = json_encode(array_column($catRevData, 'rev'));
?>

<style>
    .text-muted a {
        --bs-text-opacity: 1;
        color: rgba(33, 37, 41, 0.75) !important;
    }

    .table-scroll-2 {
        height: 300px;
        max-height: 300px;
        /* ~5 rows */
        overflow-y: auto;
    }

    /* sticky header */
    .table-scroll-2 thead th {
        position: sticky;
        top: 0;
        background: #fff;
        /* IMPORTANT (prevents overlap issue) */
        z-index: 10;
    }
</style>

<div class="container-fluid py-4">
    <!-- ── Row 1: 6 stat cards ── -->
    <?php
    $cards = [

        [
            "title" => "Total Users",
            "icon"  => "fa-user",
            "bg"    => "bg-primary",
            "value" => getTotalUserCount(),
            "footer" => function () {
                $prev = getUserCountForPreviousYear();
                $cur  = getTotalUserCount();
                $pct  = $prev ? round((($cur - $prev) / $prev) * 100, 1) : 0;

                if ($pct > 0) return "<span class='text-success fw-bold'>+$pct%</span> vs last year";
                elseif ($pct < 0) return "<span class='text-danger fw-bold'>$pct%</span> vs last year";
                return "No change vs last year";
            }
        ],

        [
            "title" => "New Users",
            "icon"  => "fa-user-plus",
            "bg"    => "bg-success",
            "value" => getNewUserCountForPreviousMonth(),
            "footer" => function () {
                $prev = getNewUserCountForPreviousMonth(-1);
                $cur  = getNewUserCountForPreviousMonth();
                $pct  = $prev ? round((($cur - $prev) / $prev) * 100, 1) : 0;

                if ($pct > 0) return "<span class='text-success fw-bold'>+$pct%</span> vs last month";
                elseif ($pct < 0) return "<span class='text-danger fw-bold'>$pct%</span> vs last month";
                return "No change vs last month";
            }
        ],

        [
            "title" => "Active Orders",
            "icon"  => "fa-bag-shopping",
            "bg"    => "bg-dark",
            "value" => getTotalOrderCount(),
            "footer" => function () {
                $cur  = getTotalOrderCount();
                $prev = getTotalOrderCountForPreviousMonth();
                $pct  = calculatePercentageChange($prev, $cur);

                if ($pct > 0) return "<span class='text-success fw-bold'>+$pct%</span> vs last month";
                elseif ($pct < 0) return "<span class='text-danger fw-bold'>$pct%</span> vs last month";
                return "No change vs last month";
            }
        ],

        [
            "title" => "Total Revenue",
            "icon"  => "fa-dollar-sign",
            "bg"    => "bg-info",
            "value" => "Rs." . number_format(getTotalRevenue(), 0),
            "footer" => function () {
                $cur  = getTotalRevenue();
                $prev = getTotalRevenueForPreviousMonth();
                $pct  = calculatePercentageChange($prev, $cur);

                if ($pct > 0) return "<span class='text-success fw-bold'>+$pct%</span> vs last month";
                elseif ($pct < 0) return "<span class='text-danger fw-bold'>$pct%</span> vs last month";
                return "No change vs last month";
            }
        ],

        [
            "title" => "Total Products",
            "icon"  => "fa-box-open",
            "bg"    => "bg-danger",
            "value" => $totalProducts,
            "link"  => ["text" => "Manage products", "url" => "products.php"]
        ],

        [
            "title" => "Categories",
            "icon"  => "fa-layer-group",
            "bg"    => "bg-purple",
            "value" => $totalCategories,
            "link"  => ["text" => "Manage categories", "url" => "category.php"]
        ],

    ];
    ?>

    <div class="row g-3 mb-2">
        <?php foreach ($cards as $i => $card): ?>
            <div class="main-cards col-xl-2 col-sm-6 card-<?= $i + 1 ?>">
                <div class="card h-100 shadow-sm">
                    <div class="card-body d-flex justify-content-between align-items-start">
                        <div class="icon-div <?= $card['bg'] ?> d-flex align-items-center justify-content-center">
                            <i class="fa-solid <?= $card['icon'] ?> text-white"></i>
                        </div>

                        <!-- Content -->
                        <div class="text-end">
                            <p class="mb-1 text-muted small"><?= $card['title'] ?></p>
                            <h5 class="mb-0"><?= $card['value'] ?></h5>
                        </div>
                    </div>

                    <hr class="dark my-0">

                    <div class="card-footer p-3">
                        <?php if (isset($card['footer'])): ?>
                            <p class="text-muted">
                                <?= $card['footer'](); ?>
                            </p>

                        <?php elseif (isset($card['link'])): ?>
                            <p class="text-muted">
                                <a href="<?= $card['link']['url'] ?>" class="text-decoration-none">
                                    <?= $card['link']['text'] ?>
                                </a>
                            </p>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- ── Row 2: Charts ── -->
    <div class="row mt-4 g-3">
        <!-- Orders by Month Chart -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header p-0 position-relative mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-3">
                        <h6 class="text-white ps-3 mb-0"><i class="fa-solid fa-chart-line me-2"></i>Orders Overview (All
                            Time)</h6>
                    </div>
                </div>
                <div class="card-body p-3">
                    <div class="chart">
                        <canvas id="ordersChart" style="max-height:260px;"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue by Category -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header p-0 position-relative mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-3">
                        <h6 class="text-white ps-3 mb-0"><i class="fa-solid fa-chart-pie me-2"></i>Revenue by Category
                        </h6>
                    </div>
                </div>
                <div class="card-body p-3 d-flex align-items-center justify-content-center">
                    <div class="chart w-100">
                        <canvas id="categoryChart" style="max-height:240px;"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Row 3: Recent Orders + Low Stock ── -->
    <div class="row mt-4 g-3">
        <!-- Recent Orders -->
        <div class="col-lg-7">
            <div class="card">
                <div class="card-header p-0 position-relative mx-3 z-index-2">
                    <div
                        class="bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-3 d-flex align-items-center justify-content-between pe-3">
                        <h6 class="text-white ps-3 mb-0"><i class="fa-solid fa-receipt me-2"></i>Recent Orders</h6>
                        <a href="orders.php" class="jenny-add-btn">View All</a>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive table-scroll-2 p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">
                                        Customer</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Total</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Status</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $hasOrders = false;
                                while ($o = mysqli_fetch_assoc($recentOrders)) {
                                    $hasOrders = true;
                                    switch ($o['status']) {
                                        case '1':
                                            $statusBadge = '<span class="badge badge-sm bg-gradient-success">Success</span>';
                                            break;
                                    
                                        case '2':
                                            $statusBadge = '<span class="badge badge-sm bg-gradient-danger">Declined</span>';
                                            break;
                                    
                                        case '0':
                                        default:
                                            $statusBadge = '<span class="badge badge-sm bg-gradient-primary">Pending</span>';
                                            break;
                                    } 
                                    echo '<tr>
                                        <td class="ps-3">
                                            <h6 class="mb-0 text-sm">' . htmlspecialchars($o['name']) . '</h6>
                                            <p class="text-xs text-secondary mb-0">' . htmlspecialchars($o['email']) . '</p>
                                        </td>
                                        <td class="text-center"><span class="text-secondary text-xs font-weight-bold">Rs. ' . number_format((float)$o['total_price'], 0) . '</span></td>
                                        <td class="text-center">' . $statusBadge . '</td>
                                        <td class="text-center"><span class="text-secondary text-xs">' . date('d M Y', strtotime($o['created_at'])) . '</span></td>
                                    </tr>';
                                }
                                if (!$hasOrders) {
                                    echo '<tr><td colspan="4"><div class="text-center py-4">
                                        <i class="fa-solid fa-inbox fa-2x mb-2" style="color:#b5838d;opacity:.4;"></i>
                                        <p class="text-muted small mt-2 mb-0">No orders yet.</p>
                                    </div></td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="col-lg-5">
            <div class="card">
                <div class="card-header p-0 position-relative mx-3 z-index-2">
                    <div class="shadow border-radius-lg pt-3 pb-3 d-flex align-items-center justify-content-between pe-3"
                        style="background:linear-gradient(195deg,#ef5350,#c62828);">
                        <h6 class="text-white ps-3 mb-0"><i class="fa-solid fa-triangle-exclamation me-2"></i>Low Stock
                            Alert</h6>
                        <a href="products.php" class="jenny-add-btn">Manage</a>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive table-scroll-2 p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">
                                        Product</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Category</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Qty</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $hasLow = false;
                                while ($p = mysqli_fetch_assoc($lowStock)) {
                                    $hasLow = true;
                                    $qtyClass = $p['remaining_quantity'] == 0 ? 'bg-gradient-danger' : 'bg-gradient-warning';
                                    echo '<tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center">
                                                <img src="./assets/img/products/' . $p['image'] . '" class="avatar avatar-sm me-2 border-radius-lg" alt="">
                                                <h6 class="mb-0 text-sm">' . htmlspecialchars($p['name']) . '</h6>
                                            </div>
                                        </td>
                                        <td class="text-center"><span class="text-secondary text-xs">' . htmlspecialchars($p['cat_name'] ?? '') . '</span></td>
                                        <td class="text-center"><span class="text-secondary badge badge-sm ' . $qtyClass . '">' . $p['remaining_quantity'] . '</span></td>
                                    </tr>';
                                }
                                if (!$hasLow) {
                                    echo '<tr><td style="border-bottom: none !important;" colspan="3"><div class="text-center py-4">
                                        <i class="fa-solid fa-circle-check fa-2x mb-2" style="color:#4caf50;opacity:.5;"></i>
                                        <p class="text-muted small mt-2 mb-0">All products are well-stocked.</p>
                                    </div></td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── Row 4: Top Selling + Top Clients ── -->
    <div class="row mt-4 g-3">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header p-0 position-relative mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-3">
                        <h6 class="text-white ps-3 mb-0"><i class="fa-solid fa-fire me-2"></i>Top 10 Selling Products
                        </h6>
                    </div>
                </div>
                
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive table-scroll-2 p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        #</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Product</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Units Sold</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $rank = 1;
                                $hasProd = false;
                                while ($product = mysqli_fetch_assoc($topSellingProducts)) {
                                    $hasProd = true;
                                    echo '<tr>
                                        <td class="text-center"><span class="text-secondary text-xs font-weight-bold">' . $rank . '</span></td>
                                        <td>
                                            <div class="d-flex align-items-center px-2 py-1">
                                                <img src="./assets/img/products/' . $product['image'] . '" class="avatar avatar-sm me-3 border-radius-lg" alt="">
                                                <h6 class="mb-0 text-sm">' . htmlspecialchars($product['name']) . '</h6>
                                            </div>
                                        </td>
                                        <td class="text-center"><span class="badge badge-sm bg-gradient-primary">' . $product['total_sold'] . '</span></td>
                                    </tr>';
                                    $rank++;
                                }
                                if (!$hasProd) {
                                    echo '<tr><td colspan="3"><div class="text-center py-4">
                                        <i class="fa-solid fa-chart-bar fa-2x mb-2" style="color:#b5838d;opacity:.4;"></i>
                                        <p class="text-muted small mt-2 mb-0">No sales data yet.</p>
                                    </div></td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card">
                <div class="card-header p-0 position-relative mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-3">
                        <h6 class="text-white ps-3 mb-0"><i class="fa-solid fa-crown me-2"></i>Top 10 Clients</h6>
                    </div>
                </div>
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive table-scroll-2 p-0">
                        <table class="table align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        #</th>
                                    <th
                                        class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">
                                        Username</th>
                                    <th
                                        class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                        Items Bought</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $rank = 1;
                                $hasClient = false;
                                while ($client = mysqli_fetch_assoc($topClients)) {
                                    $hasClient = true;
                                    echo '<tr>
                                        <td class="text-center"><span class="text-secondary text-xs font-weight-bold">' . $rank . '</span></td>
                                        <td class="ps-2"><span class="text-sm font-weight-bold">' . htmlspecialchars($client['username']) . '</span></td>
                                        <td class="text-center"><span class="badge badge-sm bg-gradient-success">' . $client['total_items_purchased'] . '</span></td>
                                    </tr>';
                                    $rank++;
                                }
                                if (!$hasClient) {
                                    echo '<tr><td colspan="3"><div class="text-center py-4">
                                        <i class="fa-solid fa-users fa-2x mb-2" style="color:#b5838d;opacity:.4;"></i>
                                        <p class="text-muted small mt-2 mb-0">No client data yet.</p>
                                    </div></td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    (function() {
        console.log(<?= $catRevs; ?>);
        const palette = ['#ec407a', '#ab47bc', '#7e57c2', '#42a5f5', '#26c6da', '#66bb6a', '#ffa726', '#ef5350'];

        // Orders line chart
        new Chart(document.getElementById('ordersChart'), {
            type: 'line',
            data: {
                labels: <?= $orderLabels; ?>,
                datasets: [{
                    label: 'Orders',
                    data: <?= $orderCounts; ?>,
                    borderColor: '#ec407a',
                    backgroundColor: 'rgba(236,64,122,.08)',
                    tension: .4,
                    fill: true,
                    pointBackgroundColor: '#ec407a',
                    pointRadius: 4,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            color: '#9e9e9e'
                        },
                        grid: {
                            color: 'rgba(0,0,0,.05)'
                        }
                    },
                    x: {
                        ticks: {
                            color: '#9e9e9e'
                        },
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Category pie chart
        new Chart(document.getElementById('categoryChart'), {
            type: 'doughnut',
            data: {
                labels: <?= $catLabels; ?>,
                datasets: [{
                    data: <?= $catRevs; ?>,
                    backgroundColor: palette,
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 12,
                            boxWidth: 12
                        }
                    }
                },
                cutout: '65%'
            }
        });
    })();
</script>

<?php include('includes/footer.php'); ?>