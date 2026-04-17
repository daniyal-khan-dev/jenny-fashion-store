<?php
include('includes/header.php');

$categories = getAll("categories");
$catOptions = '';
while ($c = mysqli_fetch_assoc($categories)) {
    $catOptions .= '<option value="' . $c['id'] . '">' . htmlspecialchars($c['name']) . '</option>';
}
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mx-3 z-index-2">
                    <div class="bg-gradient-primary shadow-primary border-radius-lg pt-3 pb-3 d-flex align-items-center justify-content-between pe-3">
                        <h6 class="text-white text-capitalize ps-3 mb-0">
                            <i class="fa-solid fa-box-open me-2"></i>Products
                        </h6>
                        <button type="button" class="jenny-add-btn" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="fa-solid fa-plus"></i> Add Product
                        </button>
                    </div>
                </div>

                <!-- Filter bar -->
                <div class="jenny-filter-bar">
                    <input type="text" id="filterProdName" class="form-control" placeholder="Search by product name…">
                    <select id="filterProdCat" class="form-select">
                        <option value="">All Categories</option>
                        <?php
                        global $con;
                        $catsF = mysqli_query($con, "SELECT id, name FROM categories ORDER BY name");
                        while ($cf = mysqli_fetch_assoc($catsF)) {
                            echo '<option value="' . $cf['id'] . '">' . htmlspecialchars($cf['name']) . '</option>';
                        }
                        ?>
                    </select>
                    <select id="filterProdStatus" class="form-select" style="max-width:140px;">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="2">Inactive</option>
                    </select>
                </div>

                <div class="card-body px-0 pb-2">
                    <div class="table-responsive table-scroll p-0">
                        <table class="table align-items-center mb-0" id="prodTable">
                            <thead>
                                <tr>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">ID</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Categroy</th>
                                    <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Product Details</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Status</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Trending</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Added By</th>
                                    <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Added At</th>
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

<!-- ══ ADD PRODUCT MODAL ══ -->
<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--j-deep) 0%, var(--j-rose) 100%) !important;">
                <h5 class="modal-title text-white fw-bold" id="addProductModalLabel">
                    <i class="fa-solid fa-plus me-2"></i>Add New Product
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form enctype="multipart/form-data" id="addProdForm">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="add_category_id" id="add_category_id" class="form-select">
                                <option value="0">— Select a category —</option>
                                <?= $catOptions; ?>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="addProdName" id="addProdName" class="form-control" placeholder="Enter product name" maxlength="100" oninput="allowValidChars(this)">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Product Image <span class="text-danger">*</span></label>
                            <input type="file" name="addProdImage" id="addProdImage" class="form-control" accept="image/jpeg,image/png,image/webp">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                            <input type="number" name="addProdQty" id="addProdQty" class="form-control" min="0" step="1" placeholder="0">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="add_status" id="add_status" class="form-select">
                                <option value="0">— Select a status —</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Mark as Trending <span class="text-danger">*</span></label>
                            <select name="add_trending" id="add_trending" class="form-select">
                                <option value="0">— Select a Trending Status —</option>
                                <option value="1">Yes</option>
                                <option value="2">No</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Short Description <span class="text-danger">*</span> <small class="text-muted">(10–300 chars)</small></label>
                            <textarea name="addProdSdesc" id="addProdSdesc" class="form-control" style="height:6rem;resize:none;" maxlength="300" placeholder="Brief product summary…"></textarea>
                            <div class="d-flex justify-content-between">
                                <span class="char-counter" id="aShortCount">0 / 300</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Full Description <span class="text-danger">*</span> <small class="text-muted">(20–1200 chars)</small></label>
                            <textarea name="addProdDesc" id="addProdDesc" class="form-control" style="height:8rem;resize:none;" maxlength="1200" placeholder="Full product details…"></textarea>
                            <div class="d-flex justify-content-between">
                                <span class="char-counter" id="aLongCount">0 / 1200</span>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Original Price (Rs.) <span class="text-danger">*</span></label>
                            <input type="number" name="addProdPrice" id="addProdPrice" class="form-control" min="0.01" step="0.01" placeholder="0.00">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Discount Price (Rs.) <span class="text-danger">*</span> <small class="text-muted">(≤ original)</small></label>
                            <input type="number" name="addProdDprice" id="addProdDprice" class="form-control" min="0" step="0.01" placeholder="0.00">
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4" id="add-Btn" onclick="ValidationCheck('add');">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ══ EDIT PRODUCT MODAL ══ -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content border-0 shadow">
            <div class="modal-header" style="background: linear-gradient(135deg, var(--j-deep) 0%, var(--j-rose) 100%) !important;">
                <h5 class="modal-title text-white fw-bold" id="editProductModalLabel">
                    <i class="fa-solid fa-pen-to-square me-2"></i>Edit Product
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form enctype="multipart/form-data" id="editProdForm">
                <input type="hidden" name="product_id" id="editProdId">
                <input type="hidden" name="old_image" id="editProdOldImage">
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-12">
                            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
                            <select name="edit_category_id" id="edit_category_id" class="form-select">
                                <option value="0">— Select a category —</option>
                                <?= $catOptions; ?>
                            </select>
                        </div>

                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="editProdName" id="editProdName" class="form-control" maxlength="100" oninput="allowValidChars(this)">
                        </div>

                        <div class="col-md-7">
                            <div class="d-flex">
                                <div class="col-md-7">
                                    <label class="form-label fw-semibold">New Image <small class="text-muted">(optional)</small></label>
                                    <input type="file" name="editProdImage" id="editProdImage" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                                </div>
                                <div class="col-md-6" style="padding-left: 10px;">
                                    <label class="form-label mt-2 text-xs text-secondary">Current Image</label>
                                    <img id="editProdPreview" class="jenny-img-preview d-block" alt="">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Quantity <span class="text-danger">*</span></label>
                            <input type="hidden" name="quanitityOld" id="quanitityOld">
                            <input type="number" name="editProdQty" id="editProdQty" class="form-control" min="0" step="1">
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Remaining Qty <span class="text-danger">*</span></label>
                            <input type="number" name="editRemainProdQty" id="editRemainProdQty" readonly class="form-control" min="0" step="1">
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
                            <select name="editProdStatus" id="editProdStatus" class="form-select">
                                <option value="0">— Select a status —</option>
                                <option value="1">Active</option>
                                <option value="2">Inactive</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Mark as Trending <span class="text-danger">*</span></label>
                            <select name="editProdTrending" id="editProdTrending" class="form-select">
                                <option value="0">— Select a Trending Status —</option>
                                <option value="1">Yes</option>
                                <option value="2">No</option>
                            </select>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Short Description <span class="text-danger">*</span> <small class="text-muted">(10–300 chars)</small></label>
                            <textarea name="editProdSdesc" id="editProdSdesc" class="form-control" style="height:6rem;resize:none;" maxlength="300"></textarea>
                        </div>

                        <div class="col-12">
                            <label class="form-label fw-semibold">Full Description <span class="text-danger">*</span> <small class="text-muted">(20–2000 chars)</small></label>
                            <textarea name="editProdDesc" id="editProdDesc" class="form-control" style="height:8rem;resize:none;" maxlength="2000"></textarea>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Original Price (Rs.) <span class="text-danger">*</span></label>
                            <input type="number" name="editProdPrice" id="editProdPrice" class="form-control" min="0.01" step="0.01">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-semibold">Discount Price (Rs.) <span class="text-danger">*</span></label>
                            <input type="number" name="editProdDprice" id="editProdDprice" class="form-control" min="0" step="0.01">
                        </div>

                        <div class="col-12">
                            <div class="row text-xs text-secondary border-top pt-3 mt-1">
                                <div class="col-6"></i>Added by: <strong id="editAddedBy"></strong></div>
                                <div class="col-6"></i>Added at: <strong id="editDate"></strong></div>
                            </div>
                        </div>

                        <div class="col-12" id="updatedDiv" style="display: none;">
                            <div class="row text-xs text-secondary border-top pt-3 mt-1">
                                <div class="col-6"></i>Updated by: <strong id="editUpdatedBy"></strong></div>
                                <div class="col-6"></i>Updated at: <strong id="editUpdatedat"></strong></div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary px-4" id="update-Btn" onclick="ValidationCheck('update');">
                        <i class="fa-solid fa-floppy-disk me-1"></i> Update Product
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    // Table filters
    function applyProdFilters() {
        const nameQ = document.getElementById('filterProdName').value.toLowerCase();
        const catVal = document.getElementById('filterProdCat').value;
        const stVal = document.getElementById('filterProdStatus').value;

        const rows = document.querySelectorAll('#prodTable tbody tr');
        const tbody = document.querySelector('#prodTable tbody');

        let visibleCount = 0;

        rows.forEach(function(row) {

            // skip empty row if already exists
            if (row.id === "no-prod-row") return;

            const nameText = row.textContent.toLowerCase();
            const matchName = !nameQ || nameText.includes(nameQ);
            const matchCat = !catVal || row.dataset.catId === catVal;
            const matchSt = !stVal || row.dataset.status === stVal;

            const isVisible = (matchName && matchCat && matchSt);

            row.style.display = isVisible ? '' : 'none';

            if (isVisible) visibleCount++;
        });

        // remove old empty row
        const existing = document.getElementById('no-prod-row');
        if (existing) existing.remove();

        // show empty message if no match
        if (visibleCount === 0) {
            const tr = document.createElement('tr');
            tr.id = "no-prod-row";
            tr.innerHTML = `
            <td colspan="8" class="text-center py-5">
                <div class="d-flex flex-column align-items-center">
                    <i class="fa-solid fa-box-open text-secondary mb-2" style="font-size:24px;"></i>
                    <span class="text-secondary">No products found</span>
                </div>
            </td>
        `;
            tbody.appendChild(tr);
        }
    }

    function counter(taId, countId, max) {
        const ta = document.getElementById(taId);
        const ct = document.getElementById(countId);

        function update() {
            const n = ta.value.length;
            ct.textContent = n + ' / ' + max;
            ct.className = 'char-counter' + (n >= max ? ' over' : n > max * .85 ? ' warn' : '');
        }
        ta.addEventListener('input', update);
        return update;
    }

    document.getElementById('filterProdName').addEventListener('input', applyProdFilters);
    document.getElementById('filterProdCat').addEventListener('change', applyProdFilters);
    document.getElementById('filterProdStatus').addEventListener('change', applyProdFilters);
    const updateAShort = counter('addProdSdesc', 'aShortCount', 300);
    const updateALong = counter('addProdDesc', 'aLongCount', 1200);
    const updateEShort = counter('editProdSdesc', 'eShortCount', 300);
    const updateELong = counter('editProdDesc', 'eLongCount', 1200);

    window.routes = {
        getProduct: "<?= $routes['admin']['product']['get-api']; ?>",
        addProduct: "<?= $routes['admin']['product']['add-api']; ?>",
        updateProduct: "<?= $routes['admin']['product']['update-api']; ?>",
        deleteProduct: "<?= $routes['admin']['product']['delete-api']; ?>",
    };

    function ValidationCheck(actionType) {
        let formId;
        let btn;
        let validation;

        if (actionType === 'add') {
            formId = "addProdForm";
            btn = "add-Btn";
            validation = [{
                    id: "add_category_id",
                    message: "Please Select Category.",
                    skipIf: "0"
                },
                {
                    id: "addProdName",
                    message: "Please enter Product Name.",
                    minLength: 2,
                    maxLength: 100
                },
                {
                    id: "addProdImage",
                    message: "Please Select Product Image.",
                    imgaccept: "jpg, jpeg, png, webp"
                },
                {
                    id: "addProdQty",
                    message: "Please enter Product Quantity.",
                    min: 5,
                    max: 500
                },
                {
                    id: "add_status",
                    message: "Please Select Status.",
                    skipIf: "0"
                },
                {
                    id: "add_trending",
                    message: "Please Select Trending Status.",
                    skipIf: "0"
                },
                {
                    id: "addProdSdesc",
                    message: "Please enter Product Short Description.",
                    minLength: 50,
                    maxLength: 300
                },
                {
                    id: "addProdDesc",
                    message: "Please enter Product Full Description.",
                    minLength: 200,
                    maxLength: 1200
                },
                {
                    id: "addProdPrice",
                    message: "Please enter Product Original Price.",
                    min: 1,
                },
                {
                    id: "addProdDprice",
                    message: "Please enter Product Dicounted Price.",
                    min: 0,
                },
            ];
        } else if (actionType === 'update') {
            formId = "editProdForm";
            btn = "update-Btn";
            validation = [{
                    id: "edit_category_id",
                    message: "Please Select Category.",
                    skipIf: "0"
                },
                {
                    id: "editProdName",
                    message: "Please enter Product Name.",
                    minLength: 2,
                    maxLength: 100
                },
                {
                    id: "editProdQty",
                    message: "Please enter Product Quantity.",
                    min: 5,
                    max: 500
                },
                {
                    id: "edit_status",
                    message: "Please Select Status.",
                    skipIf: "0"
                },
                {
                    id: "edit_trending",
                    message: "Please Select Trending Status.",
                    skipIf: "0"
                },
                {
                    id: "editProdSdesc",
                    message: "Please enter Product Short Description.",
                    minLength: 50,
                    maxLength: 300
                },
                {
                    id: "editProdDesc",
                    message: "Please enter Product Full Description.",
                    minLength: 200,
                    maxLength: 1200
                },
                {
                    id: "editProdPrice",
                    message: "Please enter Product Original Price.",
                    min: 1,
                },
                {
                    id: "editProdDprice",
                    message: "Please enter Product Dicounted Price.",
                    min: 0,
                },
            ];
        }

        validateForm({
            formId: formId,
            btn: btn,
            btnTxt: "Product",
            fields: validation,
            onSuccess: () => {
                if (actionType === 'add') add();
                else if (actionType === 'update') update();
            }
        });
    }

    function add() {
        submitFormData({
            formId: 'addProdForm',
            btn: "add-Btn",
            btnTxt: "Product",
            url: window.routes.addProduct,
            successMessage: 'Product added successfully!',
        });
    }

    function update() {
        submitFormData({
            formId: 'editProdForm',
            btn: "update-Btn",
            btnTxt: "Product",
            url: window.routes.updateProduct,
            successMessage: 'Product Updated successfully!',
        });
    }

    const oldQtyInput = document.getElementById('quanitityOld');
    const qtyInput = document.getElementById('editProdQty');
    const remainingInput = document.getElementById('editRemainProdQty');

    qtyInput.addEventListener('input', function () {
        let newQty = parseInt(qtyInput.value);
        let remaining = parseInt(remainingInput.value);
        let oldQty = oldQtyInput.value;

        let diff = newQty - oldQty;
        let newRemaining = remaining + diff;

        if (newRemaining < 0) newRemaining = 0;
        remainingInput.value = newRemaining;

        oldQtyInput.value = newQty;
    });

    // Populate edit modal from data attributes
    document.addEventListener('click', function(e) {
        // EDIT CATEGORY
        const editBtn = e.target.closest('.edit-prod-btn');

        if (editBtn) {
            document.getElementById('editProdId').value = editBtn.dataset.id;
            document.getElementById('editProdOldImage').value = editBtn.dataset.image;
            document.getElementById('edit_category_id').value = editBtn.dataset.cat;
            document.getElementById('editProdName').value = editBtn.dataset.name;
            document.getElementById('quanitityOld').value = editBtn.dataset.qty;
            document.getElementById('editProdQty').value = editBtn.dataset.qty;
            document.getElementById('editRemainProdQty').value = editBtn.dataset.remainqty;
            document.getElementById('editProdStatus').value = editBtn.dataset.status;
            document.getElementById('editProdTrending').value = editBtn.dataset.trending;
            document.getElementById('editProdSdesc').value = editBtn.dataset.sdesc;
            document.getElementById('editProdDesc').value = editBtn.dataset.desc;
            document.getElementById('editProdPrice').value = editBtn.dataset.price;
            document.getElementById('editProdDprice').value = editBtn.dataset.dprice;
            document.getElementById('editProdPreview').src = './assets/img/products/' + editBtn.dataset.image;
            document.getElementById('editProdPreview').style.display = 'block';
            document.getElementById('editProdImage').value = '';
            document.getElementById('editAddedBy').textContent = editBtn.dataset.addedBy;
            document.getElementById('editDate').textContent = editBtn.dataset.date;
            document.getElementById('editUpdatedBy').textContent = editBtn.dataset.updatedBy;
            document.getElementById('editUpdatedat').textContent = editBtn.dataset.updatedDate;

            if (editBtn.dataset.updatedBy && editBtn.dataset.updatedBy.trim() !== '') {
                document.getElementById('updatedDiv').style.display = "block";
            }
            return;
        }

        // DELETE CATEGORY
        const deleteBtn = e.target.closest('.delete_product_btn');
        if (!deleteBtn) return;

        const id = deleteBtn.value;

        Swal.fire({
            title: 'Delete this product?',
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
            fd.append('product_id', id); // ✅ FIXED

            fetch(window.routes.deleteProduct, {
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
                            text: 'Product removed.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // 🔥 refresh table
                        if (typeof loadProducts === "function") {
                            loadProducts();
                        } else {
                            location.reload();
                        }
                    } else {
                        Swal.fire('Error', 'Could not delete product.', 'error');
                    }
                })
                .catch(() => {
                    Swal.fire('Error', 'Server error occurred.', 'error');
                });
        });
    });
</script>

<?php include('includes/footer.php'); ?>