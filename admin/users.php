<?php
include('includes/header.php');
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">

                <div class="card-header p-0 position-relative mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-3 d-flex align-items-center justify-content-between pe-3">
                        <h6 class="text-white ps-3 mb-0">
                            <i class="fa-solid fa-users me-2"></i>Customers
                        </h6>
                        <span class="jenny-add-btn" style="pointer-events:none;opacity:.8;">
                            <?php
                            global $con;
                            $cnt = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM users WHERE user_role=0"));
                            echo $cnt['c'] . ' total';
                            ?>
                        </span>
                    </div>
                </div>

                <!-- Filter bar -->
                <div class="jenny-filter-bar">
                    <input type="text" id="filterUserName" class="form-control" placeholder="Search by name, email or city…">
                    <input type="text" id="filterUserPhoneCity" class="form-control" placeholder="Filter by phone or city…">
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="usersTable">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Customer</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Phone</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">City</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Joined</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $users = getAllCustomers();
                                if (mysqli_num_rows($users) > 0) {
                                    while ($u = mysqli_fetch_assoc($users)) {
                                        $joined = formatDate($u['created_at']);
                                        ?>
                                        <tr class="user-row">
                                            <td class="text-center align-middle">
                                                <h6 class="text-xs font-weight-bold mb-0"><?= $u['id']; ?></h6>
                                            </td>
                                            <td class="align-middle ps-3">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar avatar-sm me-3 border-radius-lg d-flex align-items-center justify-content-center flex-shrink-0" style="background:linear-gradient(195deg,#b5838d,#6d3b47);">
                                                        <i class="fa-solid fa-user text-white" style="font-size:.75rem;"></i>
                                                    </div>
                                                    <div>
                                                        <h6 class="mb-0 text-sm"><?= htmlspecialchars($u['username']); ?></h6>
                                                        <p class="text-xs text-secondary mb-0"><?= htmlspecialchars($u['email']); ?></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs"><?= htmlspecialchars($u['phone_no'] ?: '—'); ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs"><?= htmlspecialchars($u['city_name'] ?: '—'); ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs"><?= $joined; ?></span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <button type="button"
                                                    class="btn btn-link text-info px-2 mb-0 view-user-btn"
                                                    data-id="<?= $u['id']; ?>"
                                                    data-username="<?= htmlspecialchars($u['username'], ENT_QUOTES); ?>"
                                                    data-email="<?= htmlspecialchars($u['email'], ENT_QUOTES); ?>"
                                                    data-phone="<?= htmlspecialchars($u['phone_no'], ENT_QUOTES); ?>"
                                                    data-workphone="<?= htmlspecialchars($u['work_phone_no'], ENT_QUOTES); ?>"
                                                    data-city="<?= htmlspecialchars($u['city_name'], ENT_QUOTES); ?>"
                                                    data-address="<?= htmlspecialchars($u['address'], ENT_QUOTES); ?>"
                                                    data-joined="<?= $joined; ?>"
                                                    data-bs-toggle="modal" data-bs-target="#viewUserModal">
                                                    <i class="fa-solid fa-eye me-1"></i>View
                                                </button>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                } else {
                                    echo '<tr><td colspan="6">
                                        <div class="text-center py-5">
                                            <i class="fa-solid fa-users fa-3x mb-3" style="color:#b5838d;opacity:.4;"></i>
                                            <h5 class="mt-2 mb-1" style="color:#6c757d;">No customers yet</h5>
                                            <p class="text-muted small">Customers will appear here once they sign up.</p>
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

<!-- ══ VIEW USER MODAL ══ -->
<div class="modal fade" id="viewUserModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background:linear-gradient(195deg,#ec407a,#d81b60);">
                <h5 class="modal-title text-white fw-bold"><i class="fa-solid fa-circle-info me-2"></i>Customer Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="text-center mb-4">
                    <div class="avatar avatar-xl mx-auto d-flex align-items-center justify-content-center border-radius-lg" style="width:72px;height:72px;background:linear-gradient(195deg,#b5838d,#6d3b47);">
                        <i class="fa-solid fa-user text-white fa-2x"></i>
                    </div>
                    <h5 class="mt-3 mb-0" id="vUserName"></h5>
                    <p class="text-secondary text-sm mb-0" id="vUserEmail"></p>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p class="user-info-label">Phone</p>
                        <p class="user-info-value" id="vUserPhone"></p>
                    </div>
                    <div class="col-6">
                        <p class="user-info-label">Work Phone</p>
                        <p class="user-info-value" id="vUserWPhone"></p>
                    </div>
                    <div class="col-6">
                        <p class="user-info-label">City</p>
                        <p class="user-info-value" id="vUserCity"></p>
                    </div>
                    <div class="col-6">
                        <p class="user-info-label">Member Since</p>
                        <p class="user-info-value" id="vUserJoined"></p>
                    </div>
                    <div class="col-12">
                        <p class="user-info-label">Address</p>
                        <p class="user-info-value" id="vUserAddress"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<script>
(function () {
    // Filters
    function applyFilters() {
        const q  = document.getElementById('filterUserName').value.toLowerCase();
        const pc = document.getElementById('filterUserPhoneCity').value.toLowerCase();
    
        const rows  = document.querySelectorAll('#usersTable tbody tr.user-row');
        const tbody = document.querySelector('#usersTable tbody');
    
        let visibleCount = 0;
    
        rows.forEach(function (row) {
            const txt = row.textContent.toLowerCase();
    
            const matchName = !q || txt.includes(q);
            const matchPC   = !pc || txt.includes(pc); // phone OR city search
    
            const match = matchName && matchPC;
    
            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });
    
        // remove old empty row
        const existingEmpty = document.getElementById('no-user-row');
        if (existingEmpty) existingEmpty.remove();
    
        // show message if no results
        if (visibleCount === 0) {
            const tr = document.createElement('tr');
            tr.id = "no-user-row";
            tr.innerHTML = `
                <td colspan="6" class="text-center py-4 text-secondary">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    No users found
                </td>`;
            tbody.appendChild(tr);
        }
    }
    
    document.getElementById('filterUserName').addEventListener('input', applyFilters);
    document.getElementById('filterUserPhoneCity').addEventListener('input', applyFilters);
    
    // View modal population
    document.querySelectorAll('.view-user-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            document.getElementById('vUserName').textContent    = this.dataset.username;
            document.getElementById('vUserEmail').textContent   = this.dataset.email;
            document.getElementById('vUserPhone').textContent   = this.dataset.phone  || '—';
            document.getElementById('vUserWPhone').textContent  = this.dataset.workphone || '—';
            document.getElementById('vUserCity').textContent    = this.dataset.city   || '—';
            document.getElementById('vUserAddress').textContent = this.dataset.address || '—';
            document.getElementById('vUserJoined').textContent  = this.dataset.joined;
        });
    });
})();
</script>

<?php include('includes/footer.php'); ?>
