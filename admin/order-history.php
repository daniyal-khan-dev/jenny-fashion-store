<?php
include('includes/header.php');
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">

                <div class="card-header p-0 position-relative mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-3 d-flex align-items-center justify-content-between pe-3">
                        <h6 class="text-white text-capitalize ps-3 mb-0">
                            <i class="fa-solid fa-clock-rotate-left me-2"></i>Order History
                        </h6>
                        <a href="orders.php" class="jenny-add-btn">
                            <i class="fa-solid fa-truck-fast"></i> Active Orders
                        </a>
                    </div>
                </div>

                <!-- Filter bar -->
                <div class="jenny-filter-bar">
                    <input type="text" id="filterOrderCustomer" class="form-control" placeholder="Search by customer name…">
                    <input type="text" id="filterOrderTracking" class="form-control" placeholder="Filter by tracking no…">
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="orderHistTable">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Customer</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Tracking No</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Total</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date & Time</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">View</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $orders = getOrderHistroy();
                                if (mysqli_num_rows($orders) > 0) {
                                    foreach ($orders as $item) {
                                ?>
                                        <tr>
                                            <td class="align-middle text-center">
                                                <h6 class="text-xs font-weight-bold mb-0 text-sm"><?= $item['id']; ?></h6>
                                            </td>
                                            <td class="align-middle text-center">
                                                <h6 class="text-xs font-weight-bold mb-0 text-sm"><?= htmlspecialchars($item['name']); ?></h6>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">#<?= $item['tracking_no']; ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <h6 class="text-xs font-weight-bold mb-0 text-sm">Rs. <?= number_format((float)$item['total_price'], 2); ?></h6>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?= formatDate($item['created_at']); ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="<?= $routes['admin']['orders']['co-details'] ?>?co-t=<?= $item['tracking_no']; ?>" class="btn btn-link text-success px-3 mb-0">
                                                    <i class="fa-solid fa-eye me-1"></i>View
                                                </a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="6">
                                        <div class="text-center py-5">
                                            <i class="fa-solid fa-box-archive fa-3x mb-3" style="color:#b5838d;opacity:.4;"></i>
                                            <h5 class="mt-2 mb-1" style="color:#6c757d;">No order history found</h5>
                                            <p class="text-muted small">Completed or cancelled orders will appear here.</p>
                                        </div>
                                    </td></tr>';
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

<script>
    (function() {
        function applyOrderFilters() {
            const cq = document.getElementById('filterOrderCustomer').value.toLowerCase();
            const tq = document.getElementById('filterOrderTracking').value.toLowerCase();
            const rows = document.querySelectorAll('#orderHistTable tbody tr');
            const tbody = document.querySelector('#orderHistTable tbody');
            let visibleCount = 0;

            rows.forEach(function(row) {
                const txt = row.textContent.toLowerCase();
                const match = (!cq || txt.includes(cq)) && (!tq || txt.includes(tq));
                row.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            // Remove existing "no data" row
            const existingEmpty = document.getElementById('no-order-row');
            if (existingEmpty) existingEmpty.remove();

            // Show message if no rows visible
            if (visibleCount === 0) {
                const tr = document.createElement('tr');
                tr.id = "no-order-row";
                tr.innerHTML = `
                <td colspan="6" class="text-center py-4 text-secondary">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    No orders found
                </td>`;
                tbody.appendChild(tr);
            }
        }

        document.getElementById('filterOrderCustomer').addEventListener('input', applyOrderFilters);
        document.getElementById('filterOrderTracking').addEventListener('input', applyOrderFilters);
    })();
</script>

<?php include('includes/footer.php'); ?>