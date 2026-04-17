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
                            <i class="fa-solid fa-layer-group me-2"></i>Categories
                        </h6>
                        <button type="button" class="jenny-add-btn" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                            <i class="fa-solid fa-plus"></i> Add Category
                        </button>
                    </div>
                </div>

                <!-- Filter bar -->
                <div class="jenny-filter-bar">
                    <input type="text" id="filterCatName" class="form-control" placeholder="Search by category name…">
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="table-responsive table-scroll p-0">
                        <table class="table align-items-center mb-0" id="catTable">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Name</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Image</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Added By</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Added at</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Actions</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ══ ADD CATEGORY MODAL ══ -->
<div class="modal fade" id="addCategoryModal" tabindex="-1" aria-labelledby="addCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--j-deep) 0%, var(--j-rose) 100%) !important;">
                <h5 class="modal-title text-white fw-bold" id="addCategoryModalLabel">
                    <i class="fa-solid fa-plus me-2"></i>Add New Category
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="addCatForm" enctype="multipart/form-data">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="addCatName" id="addCatName" class="form-control" placeholder="e.g. Makeup" maxlength="100" oninput="allowValidChars(this)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Image <span class="text-danger">*</span></label>
                        <input type="file" name="addCatImage" id="addCatImage" class="form-control" accept="image/jpg,image/jpeg,image/png,image/webp">
                    </div>
                </div>

                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4" id="add-Btn" onclick="ValidationCheck('add');">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ══ EDIT CATEGORY MODAL ══ -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--j-deep) 0%, var(--j-rose) 100%) !important;">
                <h5 class="modal-title text-white fw-bold" id="editCategoryModalLabel">
                    <i class="fa-solid fa-pen-to-square me-2"></i>Edit Category
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form enctype="multipart/form-data" id="editCatForm">
                <input type="hidden" name="category_id" id="editCatId">
                <input type="hidden" name="old_image" id="editCatOldImage">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="editCatName" id="editCatName" class="form-control" maxlength="100" oninput="allowValidChars(this)">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Replace Image <small class="text-muted">(optional)</small></label>
                        <input type="file" name="editCatImage" id="editCatImage" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                        <label class="form-label mt-2 text-xs text-secondary">Current Image</label>
                        <img id="editImgPreview" class="jenny-img-preview d-block" alt="Current">
                    </div>

                    <div class="row text-xs text-secondary">
                        <div class="col-6"></i>Added by: <strong id="editCatAddedBy"></strong></div>
                        <div class="col-6"></i>Added at: <strong id="editCatDate"></strong></div>
                    </div>

                    <div class="row text-xs mt-2 text-secondary" id="updatedDiv" style="display: none;">
                        <div class="col-6"></i>Updated by: <strong id="editCatUpdatedBy"></strong></div>
                        <div class="col-6"></i>Updated at: <strong id="editCatUpdatedat"></strong></div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4" id="update-Btn" onclick="ValidationCheck('update');">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Update Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    window.routes = {
        getCategory: "<?= $routes['admin']['category']['get-api']; ?>",
        addCategory: "<?= $routes['admin']['category']['add-api']; ?>",
        updateCategory: "<?= $routes['admin']['category']['update-api']; ?>",
        deleteCategory: "<?= $routes['admin']['category']['delete-api']; ?>",
    };

    function ValidationCheck(actionType) {
        let formId;
        let btn;
        let validation;

        if (actionType === 'add') {
            formId = "addCatForm";
            btn = "add-Btn";
            validation = [{
                    id: "addCatName",
                    message: "Please enter Category Name.",
                    minLength: 2,
                    maxLength: 100
                },
                {
                    id: "addCatImage",
                    message: "Please Select Category Image.",
                    imgaccept: "jpg, jpeg, png, webp"
                },
            ];
        } else if (actionType === 'update') {
            formId = "addCatForm";
            btn = "update-Btn";
            validation = [{
                id: "editCatName",
                message: "Please enter Category Name.",
                minLength: 2,
                maxLength: 100
            }, ];
        }

        validateForm({
            formId: formId,
            btn: btn,
            btnTxt: "Category",
            fields: validation,
            onSuccess: () => {
                if (actionType === 'add') add();
                else if (actionType === 'update') update();
            }
        });

    }

    function add() {
        submitFormData({
            formId: 'addCatForm',
            btn: "add-Btn",
            btnTxt: "Category",
            url: window.routes.addCategory,
            successMessage: 'Category added successfully!',
        });
    }

    function update() {
        submitFormData({
            formId: 'editCatForm',
            btn: "update-Btn",
            btnTxt: "Category",
            url: window.routes.updateCategory,
            successMessage: 'Category Updated successfully!',
        });
    }

    // Populate edit modal from data attributes
    document.addEventListener('click', function(e) {
        // EDIT CATEGORY
        const editBtn = e.target.closest('.edit-cat-btn');

        if (editBtn) {
            document.getElementById('editCatId').value = editBtn.dataset.id;
            document.getElementById('editCatName').value = editBtn.dataset.name;
            document.getElementById('editCatOldImage').value = editBtn.dataset.image;
            document.getElementById('editCatAddedBy').textContent = editBtn.dataset.addedBy;
            document.getElementById('editCatDate').textContent = editBtn.dataset.addedDate;
            document.getElementById('editCatUpdatedBy').textContent = editBtn.dataset.updatedBy;
            document.getElementById('editCatUpdatedat').textContent = editBtn.dataset.updatedDate;
            document.getElementById('editImgPreview').src = './assets/img/category/' + editBtn.dataset.image;
            document.getElementById('editCatImage').value = '';

            if (editBtn.dataset.updatedBy && editBtn.dataset.updatedBy.trim() !== '') {
                document.getElementById('updatedDiv').style.display = "flex";
            }
            return;
        }

        // DELETE CATEGORY
        const deleteBtn = e.target.closest('.delete_category_btn');
        if (!deleteBtn) return;
        const id = deleteBtn.value;

        Swal.fire({
            title: 'Delete this category?',
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
            fd.append('category_id', id);
            fetch(window.routes.deleteCategory, {
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
                            text: 'Category removed.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        if (typeof loadCategories === "function") {
                            loadCategories();
                        } else {
                            location.reload();
                        }

                    } else {
                        Swal.fire('Error', 'Could not delete category.', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Server error occurred.', 'error');
                });
        });
    });

    // Table filter
    document.getElementById('filterCatName').addEventListener('input', function() {
        const q = this.value.toLowerCase();
        const rows = document.querySelectorAll('#catTable tbody tr');

        let visibleCount = 0;

        rows.forEach(function(row) {
            const match = row.textContent.toLowerCase().includes(q);
            row.style.display = match ? '' : 'none';
            if (match) visibleCount++;
        });

        const tbody = document.querySelector('#catTable tbody');

        // Remove existing empty row if any
        const existingEmpty = document.getElementById('no-data-row');
        if (existingEmpty) existingEmpty.remove();

        // If no rows visible → show message
        if (visibleCount === 0) {
            const tr = document.createElement('tr');
            tr.id = "no-data-row";
            tr.innerHTML = `
                <td colspan="6" class="text-center py-4 text-secondary">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    No categories found
                </td>`;
            tbody.appendChild(tr);
        }
    });
</script>

<?php include('includes/footer.php'); ?>