<?php
include('includes/header.php');
?>
<style>
    .form-pass {
        width: 91%;
        background: var(--white);
        border: 2px solid var(--border);
        border-radius: 10px;
        font-family: 'Inter', sans-serif;
        color: var(--brand-dark);
        outline: none;
        transition: all .3s var(--ease);
    }

    .auth__eye {
        background: none;
        border: none;
        cursor: pointer;
        color: var(--text-muted);
    }
</style>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-3 d-flex align-items-center justify-content-between pe-3">
                        <h6 class="text-white ps-3 mb-0">
                            <i class="fa-solid fa-user-shield me-2"></i>Admins
                        </h6>
                        <button type="button" class="jenny-add-btn" data-bs-toggle="modal" data-bs-target="#addAdminModal">
                            <i class="fa-solid fa-plus"></i> Add Admin
                        </button>
                    </div>
                </div>

                <!-- Filter bar -->
                <div class="jenny-filter-bar">
                    <input type="text" id="filterAdminName" class="form-control" placeholder="Search by name or email…">
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-0">
                        <table class="table align-items-center mb-0" id="adminTable">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-3">Username</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Email</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Added By</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Added at</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══ ADD ADMIN MODAL ══ -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--j-deep) 0%, var(--j-rose) 100%) !important;">
                <h5 class="modal-title text-white fw-bold"><i class="fa-solid fa-user-plus me-2"></i>Add New Admin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="addAdminForm" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="row g-3 mt-2">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    First Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="aAdminFName" id="aAdminFName" oninput="onlyAlphabets(this)" maxlength="20" class="form-control" placeholder="First name">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Last Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="aAdminLName" id="aAdminLName" oninput="onlyAlphabets(this)" maxlength="20" class="form-control" placeholder="Last name">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">username <span class="text-danger">*</span></label>
                            <input type="text" name="aAdminUsername" id="aAdminUsername" class="form-control" maxlength="25" oninput="validateUsername(this)" placeholder="Username">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="aAdminEmail" id="aAdminEmail" class="form-control" oninput="validateEmail(this)" placeholder="admin@example.com">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Password <span class="text-danger">*</span></label>
                            <div class="form-control">
                                <input class="form-pass" id="aAdminPass" oninput="validatePassword()" type="password" name="aAdminPass" placeholder="Create a password" autocomplete="new-password" required>
                                <button type="button" class="auth__eye" onclick="togglePass('aAdminPass', this)" title="Show/Hide password">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>
                            <p id="pass-msg" style="font-size:14px; margin-top:5px;"></p>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Confirm Password <span class="text-danger">*</span></label>
                            <div class="form-control">
                                <input class="form-pass" id="aAdminCPass" oninput="validatePassword()" type="password" name="aAdminCPass" placeholder="repeat your password" autocomplete="new-password" required>
                                <button type="button" class="auth__eye" onclick="togglePass('aAdminCPass', this)" title="Show/Hide password">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>
                            <p id="cpass-msg" style="font-size:14px; margin-top:5px;"></p>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4" id="add-Btn" onclick="ValidationCheck('add');">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ══ EDIT ADMIN MODAL ══ -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--j-deep) 0%, var(--j-rose) 100%) !important;">
                <h5 class="modal-title text-white fw-bold"><i class="fa-solid fa-pen-to-square me-2"></i>Edit Admin</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="editAdminForm" enctype="multipart/form-data">
                <input type="hidden" name="eAdminId" id="eAdminId">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    First Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="eAdminFName" id="eAdminFName" oninput="onlyAlphabets(this)" maxlength="20" class="form-control" placeholder="First name">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">
                                    Last Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="eAdminLName" id="eAdminLName" oninput="onlyAlphabets(this)" maxlength="20" class="form-control" placeholder="Last name">
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">username <span class="text-danger">*</span></label>
                            <input type="text" name="eAdminUsername" id="eAdminUsername" class="form-control" maxlength="25" oninput="validateUsername(this)" placeholder="Username">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="eAdminEmail" id="eAdminEmail" class="form-control" oninput="validateEmail(this)" placeholder="admin@example.com">
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">New Password <small class="text-muted">(leave blank to keep current)</small></label>
                            <div class="form-control">
                                <input class="form-pass" id="eAdminPass" oninput="validateCPassword()" type="password" name="eAdminPass" placeholder="Leave blank to keep unchanged">
                                <button type="button" class="auth__eye" onclick="togglePass('eAdminPass', this)" title="Show/Hide password">
                                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </button>
                            </div>
                            <p id="epass-msg" style="font-size:14px; margin-top:5px;"></p>
                        </div>

                        <div class="col-12">
                            <div class="row text-xs text-secondary border-top pt-3 mt-1">
                                <div class="col-6">Added by: <strong id="eAdminAddedBy"></strong></div>
                                <div class="col-6">Added at: <strong id="eAdminDate"></strong></div>
                            </div>

                            <div class="row text-xs mt-2 text-secondary" id="updatedDiv" style="display: none;">
                                <div class="col-6"></i>Updated by: <strong id="eAdminUpdatedBy"></strong></div>
                                <div class="col-6"></i>Updated at: <strong id="eAdminUpdatedat"></strong></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4" id="update-Btn" onclick="ValidationCheck('update');">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Update Admin
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    window.routes = {
        getAdmin: "<?= $routes['admin']['adminUser']['get-api']; ?>",
        addAdmin: "<?= $routes['admin']['adminUser']['add-api']; ?>",
        updateAdmin: "<?= $routes['admin']['adminUser']['update-api']; ?>",
        deleteAdmin: "<?= $routes['admin']['adminUser']['delete-api']; ?>",
    };

    function ValidationCheck(actionType) {
        let formId;
        let inputId;
        let btn;

        if (actionType === 'add') {
            formId = "addAdminForm";
            inputId = "a";
            btn = "add-Btn";
        } else if (actionType === 'update') {
            formId = "editAdminForm";
            inputId = "e";
            btn = "update-Btn";

        }

        const firstName = document.getElementById(`${inputId}AdminFName`);
        const lastName = document.getElementById(`${inputId}AdminLName`);
        const username = document.getElementById(`${inputId}AdminUsername`);
        const email = document.getElementById(`${inputId}AdminEmail`);
        const password = document.getElementById(`${inputId}AdminPass`);

        const signUpBtn = document.getElementById(btn);
        const form = document.getElementById(formId);

        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
        const usernameRegex = /^[a-zA-Z0-9_.]+$/;
        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d]).{7,}$/;

        let isValid = true;
        if (actionType === 'add') {
            const cPassword = document.getElementById("aAdminCPass");
            // reset validity
            [firstName, lastName, username, email, password, cPassword].forEach(
                (field) => {
                    field.setCustomValidity("");
                },
            );
            
        } else if (actionType === 'update') {
            // reset validity
            [firstName, lastName, username, email, password].forEach(
                (field) => {
                    field.setCustomValidity("");
                },
            );
        }

        isValid = true;

        // First Name
        if (firstName.value.trim() === "") {
            firstName.setCustomValidity("Please enter your first name.");
            isValid = false;
        }

        // Last Name
        if (lastName.value.trim() === "") {
            lastName.setCustomValidity("Please enter your last name.");
            isValid = false;
        }

        // Username
        if (username.value.trim() === "") {
            username.setCustomValidity("Please enter your username.");
            isValid = false;
        } else if (!usernameRegex.test(username.value.trim())) {
            username.setCustomValidity(
                "Username can contain letters, numbers, underscore (_) and dot (.).",
            );
            isValid = false;
        }

        // Email
        if (email.value.trim() === "") {
            email.setCustomValidity("Please enter your email address.");
            isValid = false;
        } else if (!emailRegex.test(email.value.trim())) {
            email.setCustomValidity("Please enter a valid email address.");
            isValid = false;
        }

        if (actionType === 'add') {
            // Password REQUIRED
            if (password.value.trim() === "") {
                password.setCustomValidity("Please enter a password.");
                isValid = false;
            } else if (!passwordRegex.test(password.value.trim())) {
                password.setCustomValidity(
                    "Password must be 7+ characters with letter, number, and special character."
                );
                isValid = false;
            }
        } else if (actionType === 'update') {
            // Password OPTIONAL
            if (password.value.trim() !== "") {
                if (!passwordRegex.test(password.value.trim())) {
                    password.setCustomValidity(
                        "Password must be 7+ characters with letter, number, and special character."
                    );
                    isValid = false;
                }
            }
        }

            
        if (actionType === 'add') {
            // Confirm Password
            if (cPassword.value.trim() === "") {
                cPassword.setCustomValidity("Please confirm your password.");
                isValid = false;
            } else if (password.value !== cPassword.value) {
                cPassword.setCustomValidity("Passwords do not match.");
                isValid = false;
            }
        }

        // Show messages if invalid
        if (!isValid) {
            form.reportValidity();
            return;
        }

        signUpBtn.disabled = true;
        signUpBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Saving Admin...';
        if (actionType === 'add') {
            add();
        } else if (actionType === 'update') {
            update();
        }
    }

    function add() {
        submitFormData({
            formId: 'addAdminForm',
            btn: "add-Btn",
            btnTxt: "Admin",
            url: window.routes.addAdmin,
            successMessage: 'Admin added successfully!',
        });
        document.getElementById("pass-msg").innerHTML = '';
        document.getElementById("cpass-msg").innerHTML = '';
    }

    function update() {
        submitFormData({
            formId: 'editAdminForm',
            btn: "update-Btn",
            btnTxt: "Admin",
            url: window.routes.updateAdmin,
            successMessage: 'Admin Updated successfully!',
        });
        document.getElementById("epass-msg").innerHTML = '';
    }

    document.addEventListener('click', function(e) {
        // EDIT ADMIN
        const editBtn = e.target.closest('.edit-admin-btn');

        if (editBtn) {
            document.getElementById('eAdminId').value = editBtn.dataset.id;
            document.getElementById('eAdminFName').value = editBtn.dataset.firstname;
            document.getElementById('eAdminLName').value = editBtn.dataset.lastname;
            document.getElementById('eAdminUsername').value = editBtn.dataset.username;
            document.getElementById('eAdminEmail').value = editBtn.dataset.email;
            document.getElementById('eAdminAddedBy').textContent = editBtn.dataset.addedBy;
            document.getElementById('eAdminDate').textContent = editBtn.dataset.addedDate;
            document.getElementById('eAdminUpdatedBy').textContent = editBtn.dataset.updatedBy;
            document.getElementById('eAdminUpdatedat').textContent = editBtn.dataset.updatedDate;

            if (editBtn.dataset.updatedBy && editBtn.dataset.updatedBy.trim() !== '') {
                document.getElementById('updatedDiv').style.display = "flex";
            }
            return;
        }

        // DELETE ADMIN
        const deleteBtn = e.target.closest('.delete_admin_btn');
        if (!deleteBtn) return;
        const id = deleteBtn.value;

        Swal.fire({
            title: 'Delete this admin?',
            text: 'This action cannot be undone.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then(result => {
            if (!result.isConfirmed) return;
            const fd = new FormData();
            fd.append('admin_id', id);
            fetch(window.routes.deleteAdmin, {
                    method: 'POST',
                    body: fd
                })
                .then(r => r.text())
                .then(res => {
                    res = res.trim();

                    if (res === '200') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Admin removed.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        if (typeof loadAdmins === "function") {
                            loadAdmins();
                        } else {
                            location.reload();
                        }

                    } else {
                        Swal.fire('Error', 'Could not delete admin.', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Server error occurred.', 'error');
                });
        });
    });
    
    (function () {
        const input = document.getElementById('filterAdminName');
        const tbody = document.querySelector('#adminTable tbody');
    
        input.addEventListener('input', function () {
            const q = this.value.toLowerCase();
            const rows = document.querySelectorAll('#adminTable tbody tr');
            let visibleCount = 0;
    
            rows.forEach(function (row) {
                const text = row.textContent.toLowerCase();
                const match = text.includes(q);
                row.style.display = match ? '' : 'none';
                if (match) visibleCount++;
            });
    
            // remove old message
            const existing = document.getElementById('no-admin-row');
            if (existing) existing.remove();
    
            // show message if no results
            if (visibleCount === 0) {
                const tr = document.createElement('tr');
                tr.id = "no-admin-row";
                tr.innerHTML = `
                    <td colspan="6" class="text-center py-4 text-secondary">
                        <i class="fa-solid fa-circle-exclamation me-2"></i>
                        No admins found
                    </td>
                `;
                tbody.appendChild(tr);
            }
        });
    })();
</script>

<?php include('includes/footer.php'); ?>