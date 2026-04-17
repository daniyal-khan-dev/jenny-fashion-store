<?php

session_start();

include __DIR__ . '/../../config/dbcon.php';
include __DIR__ . '/../../functions/myfunction.php';

date_default_timezone_set('Asia/Karachi');
$current_time = date('Y-m-d H:i:s');

function response($status, $message, $data = null)
{
    echo json_encode([
        "status" => $status,
        "message" => $message,
        "data" => $data
    ]);
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'getCategories') {
    $category = getAll("categories");

    $html = '';

    if (mysqli_num_rows($category) > 0) {
        $sr = 1;
        foreach ($category as $item) {
            $addedBy  = htmlspecialchars($item['added_by'] ?? 'Admin');
            $updatedBy  = htmlspecialchars($item['updated_by']);
            $dateStr  = formatDate($item['created_at']);
            $updateddateStr  = formatDate($item['updated_at']);
            $imgSrc = "./assets/img/category/" . $item['image'];
            $html .= "
                <tr>
                    <td class='align-middle text-center'>
                        <h6 class='text-xs font-weight-bold mb-0'>{$sr}</h6>
                    </td>
                
                    <td class='align-middle text-center'>
                        <h6 class='mb-0 text-sm'>" . htmlspecialchars($item['name']) . "</h6>
                    </td>
                
                    <td class='align-middle text-center'>
                        <img src='{$imgSrc}' class='avatar avatar-sm border-radius-lg' alt='" . htmlspecialchars($item['name']) . "'>
                    </td>
                
                    <td class='align-middle text-center'>
                        <span class='text-secondary text-xs font-weight-bold'>{$addedBy}</span>
                    </td>
                
                    <td class='align-middle text-center'>
                        <span class='text-secondary text-xs font-weight-bold'>{$dateStr}</span>
                    </td>
                
                    <td class='align-middle text-center'>
                        <button type='button'
                            class='btn btn-link text-success px-2 mb-0 edit-cat-btn'
                            data-id='{$item['id']}'
                            data-name='" . htmlspecialchars($item['name'], ENT_QUOTES) . "'
                            data-image='" . htmlspecialchars($item['image'], ENT_QUOTES) . "'
                            data-added-by='{$addedBy}'
                            data-added-date='{$dateStr}'
                            data-updated-by='{$updatedBy}'
                            data-updated-date='{$updateddateStr}'
                            data-bs-toggle='modal' data-bs-target='#editCategoryModal'>
                            <i class='fa-solid fa-pen-to-square me-1'></i>Edit
                        </button>
                
                        <button type='button'
                            class='btn btn-link text-danger px-2 mb-0 delete_category_btn'
                            value='{$item['id']}'>
                            <i class='fa-solid fa-trash me-1'></i>Delete
                        </button>
                    </td>
                </tr>";
            $sr++;
        }
    } else {
        $html = "<tr>
                <td colspan='6'>
                    <div class='text-center py-5'>
                        <i class='fa-solid fa-tags fa-3x mb-3' style='color:#b5838d;opacity:.4;'></i>
                        <h5 class='mt-2 mb-1' style='color:#6c757d;'>No categories found</h5>
                        <p class='text-muted small'>Click <strong>Add Category</strong> to create your first category.</p>
                    </div>
                </td>
            </tr>";
    }

    echo $html;
    exit;
} 

if (isset($_GET['action']) && $_GET['action'] === 'addCategory') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get data
        $name = trim($_POST['addCatName']);
        $added_by = $_SESSION['auth_user']['username'] ?? 'Admin';

        $image = $_FILES['addCatImage'];

        // Validation
        if (empty($name)) {
            response(false, "Category name is required");
        }

        if ($image['error'] !== 0) {
            response(false, "Image upload failed");
        }

        // File handling
        $path = __DIR__ . '/../assets/img/category/';
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $image_ext = strtolower(pathinfo($image['name'], PATHINFO_EXTENSION));

        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($image_ext, $allowed)) {
            response(false, "Invalid image format");
        }

        $filename = time() . '_' . rand(1000, 9999) . '.' . $image_ext;

        // Insert query (prepared statement 🔥)
        $stmt = $con->prepare("INSERT INTO categories (name, image, added_by, created_at) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $filename, $added_by, $current_time);

        if ($stmt->execute()) {
            move_uploaded_file($image['tmp_name'], $path . $filename);
            response(true, "Category Added Successfully", [
                "modal" => "addCategoryModal",
                "getFunc" => "loadCategories",
            ]);
        } else {
            response(false, "Something went wrong");
        }
    }
} 

if (isset($_GET['action']) && $_GET['action'] === 'updateCategory') {

    $category_id = $_POST['category_id'];
    $name = $_POST['editCatName'];
    $old_image = $_POST['old_image'];
    $new_image = $_FILES['editCatImage']['name'] ?? '';
    $updated_by = $_SESSION['auth_user']['username'] ?? 'Admin';

    $update_filename = $old_image;

    $path = __DIR__ . '/../assets/img/category/';
    if (!file_exists($path)) {
        mkdir($path, 0777, true); // true = create nested folders if needed
    }
    if (!empty($new_image)) {
        $ext = pathinfo($new_image, PATHINFO_EXTENSION);
        $update_filename = time() . '_' . rand(1000, 9999) . '.' . $ext;
    }

    // SAFE UPDATE (prepared statement)
    $stmt = $con->prepare("UPDATE categories SET name = ?, image = ?, updated_by = ?, updated_at = ? WHERE id = ?");
    $stmt->bind_param("ssssi", $name, $update_filename, $updated_by, $current_time, $category_id);

    if ($stmt->execute()) {
        // delete old image if new uploaded
        if (!empty($new_image)) {

            $old_image_path = $path . $old_image;

            if (file_exists($old_image_path)) {
                unlink($old_image_path);
            }

            move_uploaded_file($_FILES['editCatImage']['tmp_name'], $path . $update_filename);
        }

        response(true, "Category Updated Successfully", [
            "modal" => "editCategoryModal",
            "getFunc" => "loadCategories"
        ]);
    } else {
        response(false, "Category Update Failed");
    }
} 

if (isset($_GET['action']) && $_GET['action'] === 'deleteCategory') {
    $category_id = mysqli_real_escape_string($con, $_POST['category_id']);
    $category_query = "SELECT image FROM categories WHERE id='$category_id'";
    $category_query_run = mysqli_query($con, $category_query);

    if (mysqli_num_rows($category_query_run) > 0) {
        $category_data = mysqli_fetch_assoc($category_query_run);
        $image = $category_data['image'];

        $delete_query = "DELETE FROM categories WHERE id='$category_id'";
        $delete_query_run = mysqli_query($con, $delete_query);

        if ($delete_query_run) {
            
            $imgPath = __DIR__ . "/../assets/img/category/" . $image;
            
            if (!empty($image) && file_exists($imgPath) && is_file($imgPath)) {
                unlink($imgPath);
            }
            echo 200;
        } else {
            echo 500;
        }
    } else {
        echo 404;
    }
} 

if (isset($_GET['action']) && $_GET['action'] === 'getProducts') {
    $products = getProducts();
    $html = '';

    if (mysqli_num_rows($products) > 0) {
        $sr = 1;
        foreach ($products as $item) {
            $category_name = htmlspecialchars($item['category_name'] ?? 'N/A');
            $quantity  = htmlspecialchars($item['quantity']);
            $remainQty  = htmlspecialchars($item['remaining_quantity']);
            $price     = number_format((float)$item['price'], 0);
            $dprice    = number_format((float)$item['d_price'], 0);
            $status    = $item['status'];
            $trending  = $item['trending'];
            $addedBy   = htmlspecialchars($item['added_by'] ?? 'Admin');
            $updatedBy = htmlspecialchars($item['updated_by'] ?? '');
            $dateStr         = formatDate($item['created_at']);
            $updateddateStr  = formatDate($item['updated_at']);
            $imgSrc = "./assets/img/products/" . $item['image'];

            $html .= "
                <tr data-cat-id='{$item['category_id']}' data-status='{$item['status']}'>
                    <td class='align-middle text-center'>
                        <h6 class='text-xs font-weight-bold mb-0'>{$sr}</h6>
                    </td>

                    <td class='align-middle text-center'>
                        <h6 class='text-xs font-weight-bold mb-0'>{$category_name}</h6>
                    </td>
            
                    <td class='align-middle ps-2'>
                        <div class='d-flex align-items-start px-2 py-1'>
                            <img src='{$imgSrc}' class='avatar avatar-sm me-3 border-radius-lg flex-shrink-0'>
                            <div>
                                <h6 class='mb-0 text-sm'>" . htmlspecialchars($item['name']) . "</h6>
                                <p class='text-xs text-secondary mb-0'>
                                    Rs. {$dprice}
                                    <span class='text-muted text-decoration-line-through ms-1'>Rs. {$price}</span>
                                </p>
                                <p class='text-xs text-secondary mb-0'>Qty: {$quantity}</p>
                            </div>
                        </div>
                    </td>
            
                    <td class='align-middle text-center'>
                        " . ($status == 1 ? "<span class='badge bg-gradient-success'>Active</span>" : "<span class='badge bg-gradient-danger'>Inactive</span>") . "
                    </td>
            
                    <td class='align-middle text-center'>
                        " . ($trending == 1 ? "<span class='badge bg-gradient-success'>Yes</span>" : "<span class='badge bg-gradient-danger'>No</span>") . "
                    </td>
            
                    <td class='align-middle text-center'>
                        <span class='text-secondary text-xs font-weight-bold'>{$addedBy}</span>
                    </td>
            
                    <td class='align-middle text-center'>
                        <span class='text-secondary text-xs font-weight-bold'>{$dateStr}</span>
                    </td>
            
                    <td class='align-middle text-center'>
                        <button type='button'
                            class='btn btn-link text-success px-2 mb-0 edit-prod-btn'
                            data-id='{$item['id']}' data-cat='{$item['category_id']}' data-qty='{$item['quantity']}' data-remainqty='{$remainQty}'
                            data-status='{$item['status']}' data-trending='{$item['trending']}' data-price='{$item['price']}'
                            data-dprice='{$item['d_price']}' data-added-by='{$addedBy}' data-date='{$dateStr}'
                            data-updated-by='{$updatedBy}' data-updated-date='{$updateddateStr}'
                            data-name='" . htmlspecialchars($item['name'], ENT_QUOTES) . "'
                            data-image='" . htmlspecialchars($item['image'], ENT_QUOTES) . "'
                            data-sdesc='" . htmlspecialchars($item['s_description'], ENT_QUOTES) . "'
                            data-desc='" . htmlspecialchars($item['description'], ENT_QUOTES) . "'
                            data-bs-toggle='modal'
                            data-bs-target='#editProductModal'>
                            <i class='fa-solid fa-pen-to-square me-1'></i>Edit
                        </button>
            
                        <button type='button'
                            class='btn btn-link text-danger px-2 mb-0 delete_product_btn'
                            value='{$item['id']}'>
                            <i class='fa-solid fa-trash me-1'></i>Delete
                        </button>
                    </td>
                </tr>";
            $sr++;
        }
    } else {
        $html = "<tr>
                <td colspan='8'>
                    <div class='text-center py-5'>
                        <i class='fa-solid fa-box-open fa-3x mb-' style='color:#b5838d;opacity:.4;'></i>
                        <h5 class='mt-2 mb-1' style='color:#6c757d;'>No products found</h5>
                        <p class='text-muted small'>Click <strong>Add Products</strong> to create your first products.</p>
                    </div>
                </td>
            </tr>";
    }

    echo $html;
    exit;
} 

if (isset($_GET['action']) && $_GET['action'] === 'addProduct') {
    $category_id = mysqli_real_escape_string($con, $_POST['add_category_id']);
    $name = mysqli_real_escape_string($con, $_POST['addProdName']);
    $added_by = $_SESSION['auth_user']['username'] ?? 'Admin';
    $quantity = mysqli_real_escape_string($con, $_POST['addProdQty']);
    $status = isset($_POST['add_status']);
    $trending = isset($_POST['add_trending']);
    $s_description = mysqli_real_escape_string($con, $_POST['addProdSdesc']);
    $description = mysqli_real_escape_string($con, $_POST['addProdDesc']);

    $price = mysqli_real_escape_string($con, $_POST['addProdPrice']);
    $d_price = mysqli_real_escape_string($con, $_POST['addProdDprice']);
    $imageName = $_FILES['addProdImage']['name'] ?? '';
    $imageTmp  = $_FILES['addProdImage']['tmp_name'] ?? '';

    $path = __DIR__ . '/../assets/img/products/';
    if (!file_exists($path)) {
        mkdir($path, 0777, true); // true = create nested folders if needed
    }

    // ✅ VALIDATION FIX
    if (
        empty($category_id) || empty($name) ||
        empty($quantity) || empty($s_description) ||
        empty($description) || empty($price) || $d_price === ''
    ) {
        response(false, "All fields are mandatory");
    }

    // ✅ SAFE IMAGE UPLOAD
    $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp'];

    if (!in_array($ext, $allowed)) {
        response(false, "Invalid image format");
    }

    $filename = time() . '_' . rand(1000, 9999) . '.' . $ext;

    // ✅ INSERT (prepared statement)
    $stmt = $con->prepare("INSERT INTO products  (category_id, name, image, added_by, created_at, quantity, remaining_quantity, status, trending, s_description, description, price, d_price)  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("issssiiiissdd", $category_id, $name, $filename, $added_by, $current_time, $quantity, $quantity, $status, $trending, $s_description, $description, $price, $d_price);

    if ($stmt->execute()) {

        move_uploaded_file($imageTmp, $path . $filename);

        response(true, "Product Added Successfully", [
            "modal" => "addProductModal",
            "getFunc" => "loadProducts"
        ]);
    } else {
        response(false, "Something went wrong");
    }
} 

if (isset($_GET['action']) && $_GET['action'] === 'updateProduct') {
    $product_id  = $_POST['product_id'];
    $category_id = $_POST['edit_category_id'];

    $name        = $_POST['editProdName'];
    $quantity    = $_POST['editProdQty'];
    $remainQty    = $_POST['editRemainProdQty'];
    $status      = $_POST['editProdStatus'];
    $trending    = $_POST['editProdTrending'];
    $s_desc      = $_POST['editProdSdesc'];
    $desc        = $_POST['editProdDesc'];
    $price       = $_POST['editProdPrice'];
    $d_price     = $_POST['editProdDprice'];
    $updated_by  = $_SESSION['auth_user']['username'] ?? 'Admin';

    $old_image   = $_POST['old_image'];
    $imageName   = $_FILES['editProdImage']['name'] ?? '';
    $imageTmp    = $_FILES['editProdImage']['tmp_name'] ?? '';

    $path = __DIR__ . '/../assets/img/products/';
    if (!file_exists($path)) {
        mkdir($path, 0777, true); // true = create nested folders if needed
    }
    // ✅ VALIDATION
    if (empty($category_id) || empty($name) || empty($quantity) || empty($s_desc) || empty($desc) || $price === '' || $d_price === '') {
        response(false, "All fields are required");
    }

    // ✅ IMAGE HANDLING
    if (!empty($imageName)) {
        $ext = strtolower(pathinfo($imageName, PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array($ext, $allowed)) {
            response(false, "Invalid image format");
        }
        $filename = time() . '_' . rand(1000, 9999) . '.' . $ext;
        move_uploaded_file($imageTmp, $path . $filename);
        // delete old image
        if (!empty($old_image) && file_exists($path . $old_image)) {
            unlink($path . $old_image);
        }
    } else {
        $filename = $old_image;
    }

    // ✅ SAFE UPDATE (prepared)
    $stmt = $con->prepare("UPDATE products SET  category_id = ?, name = ?, image = ?, quantity = ?, remaining_quantity = ?, status = ?, trending = ?, s_description = ?, description = ?, price = ?, d_price = ?, updated_by = ?, updated_at = ? WHERE id = ? ");
    $stmt->bind_param("issiiiissddssi", $category_id, $name, $filename, $quantity, $remainQty, $status, $trending, $s_desc, $desc, $price, $d_price, $updated_by, $current_time, $product_id);

    if ($stmt->execute()) {
        response(true, "Product Updated Successfully", [
            "modal" => "editProductModal",
            "getFunc" => "loadProducts"
        ]);
    } else {
        response(false, "Update failed");
    }
} 

if (isset($_GET['action']) && $_GET['action'] === 'deleteProduct') {
    $product_id = $_POST['product_id'] ?? '';

    if (empty($product_id)) {
        echo 400;
        exit;
    }

    // ✅ GET IMAGE FIRST
    $stmt = $con->prepare("SELECT image FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        echo 404; // not found
        exit;
    }

    $product = $result->fetch_assoc();
    $image = $product['image'];

    // ✅ DELETE PRODUCT
    $stmt = $con->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        $filePath = __DIR__ . "/../assets/img/products/" . $image;
        if (!empty($image) && file_exists($filePath) && is_file($filePath)) {
            unlink($filePath);
        }
        echo 200;
    } else {
        echo 500;
    }
} 

if (isset($_GET['action']) && $_GET['action'] === 'updateOrderStatus') {
    $track_no = $_POST['tracking_no'];
    $order_status = $_POST['order_status'];
    $updateOrder_query = "UPDATE orders SET status='$order_status' WHERE tracking_no='$track_no' ";
    $updateOrder_query_exec = mysqli_query($con, $updateOrder_query);

    if ($updateOrder_query_exec) {
        // 1. Get order ID
        $getOrder = mysqli_query($con, "SELECT id FROM orders WHERE tracking_no='$track_no'");
        $orderData = mysqli_fetch_assoc($getOrder);
        $order_id = $orderData['id'];
        
        $getItems = mysqli_query($con, "SELECT prod_id, qty FROM order_items WHERE order_id='$order_id'");
        while ($item = mysqli_fetch_assoc($getItems)) {
            $prod_id = $item['prod_id'];
            $qty = $item['qty'];
            mysqli_query($con, "UPDATE products SET remaining_quantity = GREATEST(remaining_quantity - $qty, 0) WHERE id = '$prod_id'");
        }

        response(true, "Order Status Updated successfully", [
            "redirect" => "orders"
        ]);
        exit();
    } else {
        response(false, "Order Status Update Failed: " . mysqli_error($con));
        header("Location: /jenny/admin/orders");
        exit();
    }
} 

if (isset($_GET['action']) && $_GET['action'] === 'getAdmins') {
    $admins = getAllAdmins();

    $html = '';

    if (mysqli_num_rows($admins) > 0) {
        $sr = 1;
        foreach ($admins as $item) {
            $currentAdminId = $_SESSION['auth_user']['id'];
            $isSelf   = ($item['id'] == $currentAdminId);
            $addedBy  = htmlspecialchars($item['added_by']);
            $updatedBy  = htmlspecialchars($item['updated_by']);
            $dateStr  = formatDate($item['created_at']);
            $updateddateStr  = formatDate($item['updated_at']);
            $html .= "
                <tr  class='admin-row'>
                    <td class='align-middle text-center'>
                        <h6 class='text-xs font-weight-bold mb-0'>{$sr}</h6>
                    </td>

                    <td class='align-middle text-center'>
                        <h6 class='mb-0 text-sm'>" . htmlspecialchars($item['username']) . "</h6>
                    </td>

                    <td class='align-middle text-center'>
                        <span class='text-secondary text-xs font-weight-bold'>" . htmlspecialchars($item['email']) . "</span>
                    </td>
                    
                    <td class='align-middle text-center'>
                        <span class='text-secondary text-xs font-weight-bold'>{$addedBy}</span>
                    </td>
                
                    <td class='align-middle text-center'>
                        <span class='text-secondary text-xs font-weight-bold'>{$dateStr}</span>
                    </td>
                
                    <td class='align-middle text-center'>
                        <button type='button'
                            class='btn btn-link text-success px-2 mb-0 edit-admin-btn'
                            data-id='{$item['id']}'
                            data-firstname='" . htmlspecialchars($item['firstname'], ENT_QUOTES) . "' data-lastname='" . htmlspecialchars($item['lastname'], ENT_QUOTES) . "'
                            data-username='" . htmlspecialchars($item['username'], ENT_QUOTES) . "' data-email='" . htmlspecialchars($item['email'], ENT_QUOTES) . "'
                            data-added-by='{$addedBy}' data-added-date='{$dateStr}' data-updated-by='{$updatedBy}' data-updated-date='{$updateddateStr}'
                            data-bs-toggle='modal' data-bs-target='#editAdminModal'>
                            <i class='fa-solid fa-pen-to-square me-1'></i>Edit
                        </button>
            ";
            if (!$isSelf) {
                $html .= "<button type='button' class='btn btn-link text-danger px-2 mb-0 delete_admin_btn' value='{$item['id']}'>
                    <i class='fa-solid fa-trash me-1'></i>Delete
                </button>";
            }
            
            $html .= "</td></tr>";
            $sr++;
        }
    } else {
        $html = "<tr>
                <td colspan='6'>
                    <div class='text-center py-5'>
                        <i class='fa-solid fa-user-shield fa-3x mb-3' style='color:#b5838d;opacity:.4;'></i>
                        <h5 class='mt-2 mb-1' style='color:#6c757d;'>No admins found</h5>
                        <p class='text-muted small'>Click <strong>Add Admins</strong> to create your first admins.</p>
                    </div>
                </td>
            </tr>";
    }

    echo $html;
    exit;
} 

if (isset($_GET['action']) && $_GET['action'] === 'addAdmins') {
    $firstName = mysqli_real_escape_string($con, trim($_POST['aAdminFName']));
    $lastName  = mysqli_real_escape_string($con, trim($_POST['aAdminLName']));
    $username  = mysqli_real_escape_string($con, trim($_POST['aAdminUsername']));
    $email     = mysqli_real_escape_string($con, trim($_POST['aAdminEmail']));
    $password  = $_POST['aAdminPass'];
    $cpassword = $_POST['aAdminCPass'];
    $added_by = $_SESSION['auth_user']['username'] ?? 'Admin';
    $role = 1;

    if (empty($firstName)) response(false, "First name is required.");
    if (empty($lastName)) response(false, "Last name is required.");

    if (!preg_match('/^[a-zA-Z0-9_.]+$/', $username)) {
        response(false, "Invalid username format.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        response(false, "Invalid email format.");
    }

    if (strlen($password) < 7) {
        response(false, "Password must be at least 7 characters.");
    }

    if ($password !== $cpassword) {
        response(false, "Passwords do not match.");
    }

    $check = mysqli_query($con, "SELECT username, email FROM users 
        WHERE username='$username' OR email='$email' LIMIT 1
    ");

    if ($check && mysqli_num_rows($check) > 0) {
        $row = mysqli_fetch_assoc($check);
        if ($row['username'] === $username) {
            response(false, "Username is already taken.");
        }

        if ($row['email'] === $email) {
            response(false, "Email is already registered.");
        }
    }

    $hashed = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $con->prepare("
        INSERT INTO users (firstname, lastname, username, email, password, user_role, added_by, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->bind_param("sssssiss", 
        $firstName, $lastName, $username, $email, $hashed, $role, $added_by, $current_time,
    );

    if ($stmt->execute()) {
        response(true, "Admin Added Successfully", [
            "modal" => "addAdminModal",
            "getFunc" => "loadAdmins"
        ]);
    } else {
        response(false, "Something went wrong");
    }
} 

if (isset($_GET['action']) && $_GET['action'] === 'updateAdmins') {
    $admin_id = (int)$_POST['eAdminId'];
    $firstName = mysqli_real_escape_string($con, trim($_POST['eAdminFName'] ?? ''));
    $lastName  = mysqli_real_escape_string($con, trim($_POST['eAdminLName'] ?? ''));
    $username  = mysqli_real_escape_string($con, trim($_POST['eAdminUsername']));
    $email     = mysqli_real_escape_string($con, trim($_POST['eAdminEmail']));
    $updated_by  = $_SESSION['auth_user']['username'] ?? 'Admin';

    // 🔴 VALIDATION
    if (empty($firstName)) response(false, "First name is required.");
    if (empty($lastName)) response(false, "Last name is required.");

    if (!preg_match('/^[a-zA-Z0-9_.]+$/', $username)) {
        response(false, "Invalid username format.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        response(false, "Invalid email format.");
    }

    // 🔴 CHECK DUPLICATES (username + email)
    $chk = mysqli_query($con, "SELECT id FROM users WHERE (email='$email' OR username='$username') 
        AND id != $admin_id LIMIT 1
    ");

    if (mysqli_num_rows($chk) > 0) {
        response(false, "Username or Email already in use.");
    }

    // 🔴 PASSWORD (optional update)
    $newpass = trim($_POST['eAdminPass'] ?? '');

    if ($newpass !== '' && strlen($newpass) < 7) {
        response(false, "Password must be at least 7 characters.");
    }

    if ($newpass !== '') {
        $hashed = password_hash($newpass, PASSWORD_DEFAULT);

        $update = mysqli_query($con, "UPDATE users SET 
            firstname='$firstName',
            lastname='$lastName',
            username='$username',
            email='$email',
            updated_by = '$updated_by',
            updated_at = '$current_time'
            WHERE id=$admin_id AND user_role=1
        ");
    } else {
        $update = mysqli_query($con, "UPDATE users SET 
            firstname='$firstName',
            lastname='$lastName',
            username='$username',
            email='$email',
            password='$hashed',
            updated_by = '$updated_by',
            updated_at = '$current_time'
            WHERE id=$admin_id AND user_role=1
        ");
    }

    if ($update) {
        response(true, "Admin updated successfully", [
            "modal" => "editAdminModal",
            "getFunc" => "loadAdmins"
        ]);
    } else {
        response(false, "Update failed: " . mysqli_error($con));
    }
}

if (isset($_GET['action']) && $_GET['action'] === 'deleteAdmins') {
    $admin_id = $_POST['admin_id'] ?? '';

    if (empty($admin_id)) {
        echo 400;
        exit;
    }

    $current  = isset($_SESSION['auth_user']['id']) ? (int)$_SESSION['auth_user']['id'] : 0;
    if ($admin_id === $current) {
        echo 403;
        exit();
    }
    $cnt = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(*) as c FROM users WHERE user_role=1"));
    if ($cnt['c'] <= 1) {
        echo 409;
        exit();
    }
    $r = mysqli_query($con, "DELETE FROM users WHERE id=$admin_id AND user_role=1");
    echo $r ? 200 : 500;
}