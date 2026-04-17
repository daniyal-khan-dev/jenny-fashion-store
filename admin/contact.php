<?php
include_once __DIR__ . '/../config/dbcon.php';
include_once __DIR__ . '/../middleware/usermiddlware.php';

// Mark as read if requested
if (isset($_GET['mark_read']) && is_numeric($_GET['mark_read'])) {
    $mid = (int)$_GET['mark_read'];
    mysqli_query($con, "UPDATE contact_messages SET status=1 WHERE id='$mid'");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete if requested
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $mid = (int)$_GET['delete'];
    mysqli_query($con, "DELETE FROM contact_messages WHERE id='$mid'");
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

$filter = $_GET['filter'] ?? 'all';
$search = trim($_GET['search'] ?? '');

$where = "WHERE 1=1";

// status filter
if ($filter === 'unread') {
    $where .= " AND status = 0";
} elseif ($filter === 'read') {
    $where .= " AND status = 1";
}

// search filter (ONLY if not empty)
if ($search !== '') {
    $search = mysqli_real_escape_string($con, $search);
    $where .= " AND (name LIKE '%$search%' OR email LIKE '%$search%')";
}

$messages = mysqli_query($con, "SELECT * FROM contact_messages $where ORDER BY created_at DESC");
$total = mysqli_num_rows($messages);

$unreadCount = 0;
$ucq = mysqli_query($con, "SELECT COUNT(*) as cnt FROM contact_messages WHERE status = 0");
if ($ucq) $unreadCount = (int)mysqli_fetch_assoc($ucq)['cnt'];
include_once __DIR__ . '/includes/header.php';

?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-3 d-flex align-items-center justify-content-between pe-3">
                        <div class="ps-3 mb-0">
                            <h6 class="text-white">
                                <i class="fa-solid fa-envelope me-2"></i> Contact Messages
                            </h6>

                            <p class="text-muted mb-0" style="font-size:1rem; color: white !important;-">
                                <?= $total ?> message<?= $total !== 1 ? 's' : '' ?> found
                                <?php if ($unreadCount > 0): ?>
                                    &mdash; <span class="badge bg-danger"><?= $unreadCount ?> unread</span>
                                <?php endif; ?>
                            </p>
                        </div>

                        <div class="btn-group" role="group">
                            <a href="?filter=all" class="btn mx-1 <?= $filter === 'all' ? 'btn-primary-2' : 'jenny-add-btn' ?> btn-sm">All</a>
                            <a href="?filter=unread" class="btn mx-1 <?= $filter === 'unread' ? 'btn-primary-2' : 'jenny-add-btn' ?> btn-sm">Unread <?= $unreadCount > 0 ? "($unreadCount)" : '' ?></a>
                            <a href="?filter=read" class="btn mx-1 <?= $filter === 'read' ? 'btn-primary-2' : 'jenny-add-btn' ?> btn-sm">Read</a>
                        </div>
                    </div>
                </div>

                <!-- Filter bar -->
                <div class="jenny-filter-bar" style="display: inline-block;">
                    <form method="GET" class="d-flex gap-2 justify-content-between">
                        <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                        <input type="text" name="search" class="form-control" placeholder="Search by name or email..." value="<?= htmlspecialchars($search) ?>">

                        <button type="submit" class="btn btn-primary-2">
                            Search
                        </button>
                    </form>
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="adminTable">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Sender</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Subject</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Date</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $i = 1;
                                while ($msg = mysqli_fetch_assoc($messages)): ?>
                                    <tr style="<?= !$msg['status'] ? 'background:#fdf9f7;' : '' ?>">
                                        <td class="text-center">
                                            <h6 class="text-xs font-weight-bold mb-0"><?= $i++ ?></h6>
                                        </td>

                                        <td class="text-center">
                                            <h6 class="mb-0 text-sm"><?= htmlspecialchars($msg['name']) ?></h6>
                                        </td>

                                        <td class="text-center">
                                            <h6 class="mb-0 text-sm"><?= htmlspecialchars($msg['subject']) ?></h6>
                                        </td>

                                        <td class="text-center">
                                            <?php if ($msg['status'] == 0): ?>
                                                <span class="badge" style="background:#C97F5F; font-size: .8rem;">New</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary" style="font-size: .8rem;">Read</span>
                                            <?php endif; ?>
                                        </td>

                                        <td class="text-center">
                                            <span class="text-secondary text-xs font-weight-bold"><?= date('d F Y \a\t g:ia', strtotime($msg['created_at'])) ?></span>
                                        </td>

                                        <td class='text-center'>
                                            <button type='button'
                                                class='btn btn-link text-success px-2 mb-0 view-contact-btn'
                                                data-id="<?= $msg['id'] ?>"
                                                data-name="<?= htmlspecialchars($msg['name']) ?>"
                                                data-email="<?= htmlspecialchars($msg['email']) ?>"
                                                data-subject="<?= htmlspecialchars($msg['subject']) ?>"
                                                data-message="<?= htmlspecialchars($msg['message']) ?>"
                                                data-status="<?= htmlspecialchars($msg['status']) ?>"
                                                data-date="<?= date('d F Y \a\t g:ia', strtotime($msg['created_at'])) ?>"
                                                data-bs-toggle='modal' data-bs-target='#viewContactModal'>
                                                <i class="fa-regular fa-eye"></i> View
                                            </button>

                                            <?php if (!$msg['status']): ?>
                                                <a href="?mark_read=<?= $msg['id'] ?>&filter=<?= $filter ?>" class="btn btn-link text-danger px-2 mb-0 edit-cat-btn">
                                                    <i class="fa-solid fa-check"></i> Mark Read
                                                </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>

                                <tr style="<?php echo mysqli_num_rows($messages) == 0 ? 'display: contents;' : 'display:none;'; ?>">
                                    <td colspan="6" class="text-center py-5 text-secondary">
                                        <i class="fa-solid fa-circle-info me-2"></i>
                                        No messages found
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══ VIEW CONTACT MODAL ══ -->
<div class="modal fade" id="viewContactModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header justify-content-between" style="background: linear-gradient(135deg, var(--j-deep) 0%, var(--j-rose) 100%) !important;">
                <h5 class="modal-title text-white fw-bold"><i class="fa-solid fa-pen-to-square me-2"></i>View Message Details</h5>
                <div class="">
                    <span class="badge bg-secondary" style="font-size: .8rem;">Read</span>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>

            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-12">
                        <label class="form-label fw-semibold">Name <span class="text-danger">*</span></label>
                        <input class="form-control" id="vName" placeholder="Name" readonly>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input class="form-control" id="vEmail" placeholder="admin@example.com" readonly>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Subject <span class="text-danger">*</span></label>
                        <input class="form-control" id="vSubject" placeholder="Subject" readonly>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Message <span class="text-danger">*</span></label>
                        <textarea id="vMessage" class="form-control" readonly></textarea>
                    </div>

                    <div class="col-12">
                        <div class="row text-xs text-secondary border-top pt-3 mt-1">
                            <div class="col-6">Date & Time: <strong id="vDate"></strong></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('click', function(e) {
        const btn = e.target.closest('.view-contact-btn');

        if (btn) {
            document.getElementById('vName').value = btn.dataset.name;
            document.getElementById('vEmail').value = btn.dataset.email;
            document.getElementById('vSubject').value = btn.dataset.subject;
            document.getElementById('vMessage').value = btn.dataset.message;
            document.getElementById('vDate').textContent = btn.dataset.date;

            // ✅ AUTO MARK AS READ (AJAX)
            const id = btn.dataset.id;

            fetch(`?mark_read=${id}`)
                .then(() => {
                    // update UI badge instantly
                    const row = btn.closest('tr');
                    row.style.background = '';
                    const badge = row.querySelector('.badge');
                    if (badge) {
                        badge.textContent = 'Read';
                        badge.classList.remove('bg-danger');
                        badge.classList.add('bg-secondary');
                    }
                });

            return;
        }
    });

    let timer;

    document.querySelector('input[name="search"]').addEventListener('input', function() {
        clearTimeout(timer);

        timer = setTimeout(() => {
            this.form.submit();
        }, 500); // auto search after typing stops
    });
</script>
<?php include_once __DIR__ . '/includes/footer.php'; ?>