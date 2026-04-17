<?php 
include_once __DIR__ . '/../includes/header.php';

if (isset($_GET['co-t'])) {
    $tracking_no = $_GET['co-t'];
    $orderData   = checkTrackingNoValid($tracking_no);
    if (!$orderData || mysqli_num_rows($orderData) < 1) {
        echo '<div class="container py-5"><div class="alert alert-danger">Order not found.</div></div>';
        include('includes/footer.php');
        die();
    }
} else {
    echo '<div class="container py-5"><div class="alert alert-danger">No tracking number provided.</div></div>';
    include('includes/footer.php');
    die();
}

$data = mysqli_fetch_array($orderData, MYSQLI_ASSOC);

$statuses = [
    0 => ['Under Process', 'warning',  '#ca8a04'],
    1 => ['Completed',     'success',  '#16a34a'],
    2 => ['Cancelled',     'danger',   '#dc2626'],
];
[$statusLabel, $statusBadge, $statusColor] = $statuses[$data['status']] ?? ['Unknown', 'secondary', '#9a8f8b'];
?>

<style>
    .bg-white{
            background: rgba(255, 255, 255, 0.95) !important;
    backdrop-filter: blur(12px) !important;
    border-radius: 14px !important;
    box-shadow: 0 2px 16px rgba(61, 31, 40, 0.08) !important;
    margin: 8px 6px 0 !important;
    }

    .od-cards{
background:#fff;border:1px solid #e9ecef;border-radius:14px;padding:1.4rem 1.6rem;box-shadow:0 1px 6px rgba(0,0,0,.05);
    }
</style>
<div class="container-fluid">
    <div class="bg-white p-4">
        <!-- Header bar -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2 p-3" style="background: rgba(245, 239, 241, 0.7) !important; border-radius: 10px;">
            <div>
                <h4 class="mb-1 fw-bold" style="color:#3C3836;">Order Details</h4>
                <p class="mb-0 text-muted text-sm">Tracking: <strong style="color:#C97F5F;"><?= htmlspecialchars($data['tracking_no']) ?></strong></p>
            </div>
            <a href="<?= $routes['admin']['orders']['history'] ?>" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1">
                <i class="fa fa-arrow-left"></i> Back to Orders Histroy
            </a>
        </div>
    
        <!-- Status + meta summary -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="od-cards">
                    <div class="text-muted text-xs mb-1">Order Status</div>
                    <span class="badge bg-<?= $statusBadge ?> px-3 py-2" style="font-size:1.1rem;"><?= $statusLabel ?></span>
                </div>
            </div>

            <div class="col-md-3">
                <div class="od-cards">
                    <div class="text-muted text-xs mb-1">Order Total</div>
                    <div class="fw-bold" style="font-size:1.6rem;color:#C97F5F;">$<?= number_format((float)$data['total_price'], 2) ?></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="od-cards">
                    <div class="text-muted text-xs mb-1">Placed On</div>
                    <div class="fw-semibold text-sm"><?= formatDate($data['created_at']) ?></div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="od-cards">
                    <div class="text-muted text-xs mb-1">Customer</div>
                    <div class="fw-semibold text-sm"><?= htmlspecialchars($data['name']) ?></div>
                </div>
            </div>
        </div>
    
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm" style="border-radius:16px;overflow:hidden;">
                    <div class="card-header" style="background: rgba(245, 239, 241, 0.7) !important; margin: 0 !important; border:none !important; border-top: 2px solid var(--j-border) !important; border-bottom: 2px solid var(--j-border) !important; padding: 1.2rem 1.6rem !important;">
                        <h6 class="mb-0 fw-bold" style="color: #6d3b47 !important;"><i class="fa fa-truck me-2"></i>Delivery Details</h6>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label text-muted small">Full Name</label>
                                <input value="<?= htmlspecialchars($data['name']) ?>" class="form-control" readonly style="background:#f8f9fa;border-radius:10px;">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label text-muted small">Email</label>
                                <input value="<?= htmlspecialchars($data['email']) ?>" class="form-control" readonly style="background:#f8f9fa;border-radius:10px;">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label text-muted small">Phone No</label>
                                <input value="<?= htmlspecialchars($data['work_phone_no']) ?>" class="form-control" readonly style="background:#f8f9fa;border-radius:10px;">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label text-muted small">Secondary Phone</label>
                                <input value="<?= htmlspecialchars($data['cell_no']) ?>" class="form-control" readonly style="background:#f8f9fa;border-radius:10px;">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label text-muted small">Address</label>
                                <textarea class="form-control" style="height:8rem;resize:none;background:#f8f9fa;border-radius:10px;" readonly><?= htmlspecialchars($data['address']) ?></textarea>
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label text-muted small">Remarks</label>
                                <textarea class="form-control" style="height:8rem;resize:none;background:#f8f9fa;border-radius:10px;" readonly><?= htmlspecialchars($data['remarks'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    
            <!-- Order Items + Status Update -->
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm mb-4" style="border-radius:16px;overflow:hidden;">
                    <div class="card-header" style="background: rgba(245, 239, 241, 0.7) !important; margin: 0 !important; border:none !important; border-top: 2px solid var(--j-border) !important; border-bottom: 2px solid var(--j-border) !important; padding: 1.2rem 1.6rem !important;">
                        <h6 class="mb-0 fw-bold" style="color: #6d3b47 !important;"><i class="fa fa-box me-2"></i>Items Ordered</h6>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table align-items-center mb-0">
                                <thead style="background:#f8f9fa;">
                                    <tr>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Product</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Qty</th>
                                        <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 text-center">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $order_id    = $data['id'];
                                    $itemsQuery  = mysqli_query($con, "SELECT oi.qty, oi.total_price AS item_total, p.name AS product_name, p.image AS product_image
                                        FROM order_items oi
                                        JOIN products p ON oi.prod_id = p.id
                                        WHERE oi.order_id = $order_id");
                                    if ($itemsQuery && mysqli_num_rows($itemsQuery) > 0):
                                        while ($orderItem = mysqli_fetch_assoc($itemsQuery)):
                                    ?>
                                    <tr>
                                        <td class="ps-3">
                                            <div class="d-flex align-items-center gap-2 py-1">
                                                <img src="./assets/img/products/<?= htmlspecialchars($orderItem['product_image']) ?>"
                                                     class="rounded" style="width:42px;height:42px;object-fit:cover;"
                                                     alt="<?= htmlspecialchars($orderItem['product_name']) ?>">
                                                <span class="text-sm fw-semibold"><?= htmlspecialchars($orderItem['product_name']) ?></span>
                                            </div>
                                        </td>
                                        <td class="text-center text-sm fw-bold"><?= (int)$orderItem['qty'] ?></td>
                                        <td class="text-center text-sm fw-bold" style="color:#C97F5F;">$<?= number_format((float)$orderItem['item_total'], 2) ?></td>
                                    </tr>
                                    <?php endwhile; else: ?>
                                    <tr><td colspan="3" class="text-center py-3 text-muted">No items found.</td></tr>
                                    <?php endif; ?>
                                </tbody>
                                <tfoot style="background:#f8f9fa;">
                                    <tr>
                                        <td colspan="2" class="text-end fw-bold ps-3 py-2">Grand Total:</td>
                                        <td class="text-center fw-bold py-2" style="color:#C97F5F;font-size:1.3rem;">$<?= number_format((float)$data['total_price'], 2) ?></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</main>
<?php include_once __DIR__ . '/../includes/footer.php'; ?>
