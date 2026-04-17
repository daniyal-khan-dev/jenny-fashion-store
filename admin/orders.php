<?php
include('includes/header.php');
?>


<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-3 d-flex align-items-center justify-content-between pe-3">
                        <h6 class="text-white text-capitalize ps-3 mb-0">Active Orders</h6>
                        <a href="<?= $routes['admin']['orders']['history'] ?>" class="jenny-add-btn">
                            <i class="fa-solid fa-clock-rotate-left"></i> Order History
                        </a>
                    </div>
                </div>

                <div class="jenny-filter-bar">
                    <input type="text" id="filterOrders" class="form-control"
                        placeholder="Search by customer, tracking no or total…">
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0" id="products_table">
                        <table class="table align-items-center mb-0">
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
                                $orders = getAllOrders();
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
                                                <h6 class="text-xs font-weight-bold mb-0 text-sm">#<?= $item['tracking_no']; ?></h6>
                                            </td>
                                            <td class="align-middle text-center">
                                                <h6 class="text-xs font-weight-bold mb-0 text-sm">Rs. <?= number_format((float)$item['total_price'], 2); ?></h6>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold"><?= formatDate($item['created_at']); ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <a href="<?= $routes['admin']['orders']['details'] ?>?t=<?= $item['tracking_no']; ?>" class="btn btn-link text-success px-3 mb-0">
                                                    <i class="fa-solid fa-eye me-1"></i>View
                                                </a>
                                            </td>
                                        </tr>
                                <?php
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center py-5'>
                                            <i class='fa-solid fa-box-open fa-2x mb-2' style='color:#b5838d;opacity:.5;'></i>
                                            <p class='text-muted mt-2'>No active orders found.</p>
                                          </td></tr>";
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
        const input = document.getElementById('filterOrders');
        const tbody = document.querySelector('#products_table table tbody');

        input.addEventListener('input', function() {
            const q = this.value.toLowerCase();
            const rows = tbody.querySelectorAll('tr');
            let visibleCount = 0;

            rows.forEach(function(row) {
                const text = row.textContent.toLowerCase();
                const match = text.includes(q);
                row.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });

            // remove old message
            const existing = document.getElementById('no-orders-row');
            if (existing) existing.remove();

            // show message if nothing found
            if (visibleCount === 0) {
                const tr = document.createElement('tr');
                tr.id = "no-orders-row";
                tr.innerHTML = `
                <td colspan="6" class="text-center py-4 text-secondary">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    No orders found
                </td>
            `;
                tbody.appendChild(tr);
            }
        });
    })();
</script>
<?php include('includes/footer.php'); ?>