<?php
session_start();
include_once __DIR__ . '/../../functions/userfunction.php';
$page =  substr($_SERVER['SCRIPT_NAME'], strrpos($_SERVER['SCRIPT_NAME'], "/") + 1);

if (!isset($_SESSION['auth'])) {
    $_SESSION['message2'] = "Please login to continue";
    header("Location: /jenny/login");
    exit();
}

$user_id   = (int)$_SESSION['auth_user']['id'];
$user_data = [];
$uq = mysqli_query($con, "SELECT * FROM users WHERE id='$user_id' LIMIT 1");
if ($uq && mysqli_num_rows($uq) > 0) {
    $user_data = mysqli_fetch_assoc($uq);
}

// Order counts for summary cards
$total_orders    = 0;
$pending_orders  = 0;
$done_orders     = 0;
$oq = mysqli_query($con, "SELECT status FROM orders WHERE user_id='$user_id'");
while ($o = mysqli_fetch_assoc($oq)) {
    $total_orders++;
    if ($o['status'] == 0) $pending_orders++;
    if ($o['status'] == 1) $done_orders++;
}

// Handle profile update
$success = false;
$errors  = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstname  = trim($_POST['firstname'] ?? '');
    $lastname   = trim($_POST['lastname'] ?? '');
    $phone      = trim($_POST['phone'] ?? '');
    $phone2     = trim($_POST['phone2'] ?? '');
    $city       = trim($_POST['city'] ?? '');
    $address    = trim($_POST['address'] ?? '');

    if ($firstname === '') $errors[] = 'First name is required.';
    if ($lastname === '')  $errors[] = 'Last name is required.';

    if (empty($errors)) {
        $uby = $user_data['username'] ?? 'user';

        $sql = "UPDATE users SET firstname = ?, lastname = ?, work_phone_no = ?, phone_no = ?, city_name = ?, address = ?, updated_by = ? WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssssssi", $firstname, $lastname, $phone, $phone2, $city, $address, $uby, $user_id);

        if ($stmt->execute()) {
            $success = true;
            // Refresh user data
            $uq2 = $con->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            $uq2->bind_param("i", $user_id);
            $uq2->execute();
            $result = $uq2->get_result();

            if ($result && $result->num_rows > 0) {
                $user_data = $result->fetch_assoc();
            }
        } else {
            $errors[] = 'Failed to update profile. Please try again.';
        }
    }

    echo json_encode([
        "status" => $success,
        "message" => $success ? "Profile updated successfully!" : implode(' ', $errors)
    ]);
    exit;
}

include_once __DIR__ . '/../includes/header.php';
?>

<style>
    .profile-stat-card {
        background: #fff;
        border: 1px solid rgba(201, 127, 95, .15);
        border-radius: 16px;
        padding: 1.8rem 2rem;
        text-align: center;
        box-shadow: 0 2px 12px rgba(60, 56, 54, .05);
        transition: all .25s ease;
    }

    .profile-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 24px rgba(201, 127, 95, .15);
    }

    .profile-info-row {
        display: flex;
        align-items: center;
        padding: 1.4rem;
        border: 1px solid #f5f0ec;
        border-radius: 10px;
        gap: 1.5rem;
        margin-bottom: 10px;
    }

    .profile-info-icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #FDF6F0, #EDD5C5);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        border: 1px solid rgba(201, 127, 95, .2);
    }

    @media (max-width: 576px) {
        .profile-stats-grid {
            grid-template-columns: repeat(3, 1fr) !important;
            gap: .8rem !important;
        }

        .profile-stat-card {
            padding: 1.2rem 1rem;
        }

        .profile-hero {
            padding: 2rem 1.5rem !important;
            gap: 1.5rem !important;
        }

        .profile-hero-avatar {
            width: 80px !important;
            height: 80px !important;
        }
    }
    
    .edit-profile-modal .modal-content {
    border-radius: 20px;
    border: none;
    box-shadow: 0 20px 60px rgba(60,56,54,.15);
    overflow: hidden;
}
.edit-profile-modal .modal-header {
    background: linear-gradient(135deg, #3C3836, #5a4a40);
    color: #fff;
    border-bottom: none;
    padding: 1.8rem 2rem;
}
.edit-profile-modal .modal-title { font-family: 'Inter', sans-serif; font-weight: 700; font-size: 1.8rem; }
.edit-profile-modal .btn-close { filter: invert(1); }
.edit-profile-modal .modal-body { padding: 2rem; }
.edit-profile-modal .form-label {
    font-family: 'Inter', sans-serif;
    font-size: 1.3rem;
    font-weight: 600;
    color: #3C3836;
    margin-bottom: .5rem;
}
.edit-profile-modal .form-control {
    font-family: 'Inter', sans-serif;
    font-size: 1.4rem;
    border: 1.5px solid #e8e0d9;
    border-radius: 10px;
    padding: .75rem 1rem;
    transition: border-color .2s ease, box-shadow .2s ease;
}
.edit-profile-modal .form-control:focus {
    border-color: #C97F5F;
    box-shadow: 0 0 0 3px rgba(201, 127, 95, .15);
}
.edit-profile-modal .modal-footer { border-top: 1px solid #f5f0ec; padding: 1.4rem 2rem; }

    .edit-profile-modal .form-control {
        border-radius: 12px !important;
        padding: 10px 12px !important;
        border: 1px solid #e6ddd7 !important;
        font-size: 1.4rem !important;
    }

    .edit-profile-modal .form-control:focus {
        border-color: #C97F5F !important;
        box-shadow: 0 0 0 0.15rem rgba(201, 127, 95, 0.25) !important;
    }

    .edit-profile-modal .input-group-text {
        background: #fff !important;
        border: 1px solid #e6ddd7 !important;
    }

    textarea#ep_address {
        resize: none;
        height: 100px;
    }
</style>

<section class="my__account--section section--padding">
    <div class="container">
        <div class="my__account--section__inner border-radius-10 d-flex">
            <div class="account__left--sidebar">
                <h2 class="account__content--title mb-20">My Account</h2>
                <ul class="account__menu">
                    <li class="account__menu--list <?= $page == 'profile.php' ? 'active' : '' ?>">
                        <a href="<?= $routes['user']['profile'] ?>"><i class="fa-regular fa-user me-2"></i>Profile</a>
                    </li>
                    <li class="account__menu--list <?= $page == 'my-orders.php' ? 'active' : '' ?>">
                        <a href="<?= $routes['user']['myOrders'] ?>"><i class="fa-solid fa-box-open me-2"></i>My Orders</a>
                    </li>
                    <li class="account__menu--list">
                        <a href="<?= $routes['user']['logout'] ?>" class="text-danger"><i class="fa-solid fa-right-from-bracket me-2"></i>Log Out</a>
                    </li>
                </ul>
            </div>

            <div class="account__wrapper" style="flex:1; min-width:0;">
                <div class="account__content">
                    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:2.5rem;flex-wrap:wrap;gap:1rem;">
                        <h2 class="account__content--title h3 mb-0">My Profile</h2>
                        <button data-bs-toggle="modal" data-bs-target="#editProfileModal"
                            style="display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#C97F5F,#b56a4a);color:#fff;font-family:'Inter',sans-serif;font-size:1.25rem;font-weight:700;padding:.75rem 1.8rem;border-radius:50px;border:none;cursor:pointer;box-shadow:0 4px 16px rgba(201,127,95,.25);transition:all .25s ease;"
                            onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 6px 24px rgba(201,127,95,.35)'"
                            onmouseout="this.style.transform='';this.style.boxShadow='0 4px 16px rgba(201,127,95,.25)'">
                            <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13l6.586-6.586a2 2 0 012.828 2.828L11.828 15.828a4 4 0 01-1.414.93l-3 1a1 1 0 01-1.243-1.243l1-3a4 4 0 01.93-1.414z" />
                            </svg>
                            Edit Profile
                        </button>
                    </div>

                    <!-- Profile Hero Card -->
                    <div class="profile-hero" style="background:linear-gradient(135deg,#3C3836 0%,#5a4a40 100%);border-radius:20px;padding:3rem 2.5rem;margin-bottom:2rem;display:flex;align-items:center;gap:2.5rem;flex-wrap:wrap;box-shadow:0 8px 32px rgba(60,56,54,.2);">
                        <div style="position:relative;">
                            <div class="profile-hero-avatar" style="width:100px;height:100px;border-radius:50%;background:linear-gradient(135deg,#C97F5F,#b56a4a);display:flex;align-items:center;justify-content:center;border:4px solid rgba(201,127,95,.6);box-shadow:0 4px 20px rgba(0,0,0,.3);font-family:'Inter',sans-serif;font-size:3.5rem;font-weight:800;color:#fff;">
                                <?= strtoupper(mb_substr($user_data['firstname'] ?? ($user_data['username'] ?? 'U'), 0, 1)) ?>
                            </div>
                            <span style="position:absolute;bottom:4px;right:4px;width:20px;height:20px;background:#22c55e;border-radius:50%;border:3px solid #3C3836;"></span>
                        </div>
                        <div>
                            <h3 style="font-family:'Playfair Display',serif;font-size:2.4rem;font-weight:700;color:#fff;margin:0 0 .4rem;">
                                <?= htmlspecialchars(trim(($user_data['firstname'] ?? '') . ' ' . ($user_data['lastname'] ?? ''))) ?: htmlspecialchars($user_data['username'] ?? '') ?>
                            </h3>
                            <p style="font-family:'Inter',sans-serif;font-size:1.35rem;color:rgba(255,255,255,.65);margin:0;"><?= htmlspecialchars($user_data['email'] ?? '') ?></p>
                            <?php if (!empty($user_data['city_name'])): ?>
                                <p style="font-family:'Inter',sans-serif;font-size:1.2rem;color:rgba(255,255,255,.45);margin:.4rem 0 0;">
                                    <svg width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,.45)" stroke-width="2" style="vertical-align:middle;margin-right:4px;">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <?= htmlspecialchars($user_data['city_name']) ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Stats Row -->
                    <div class="profile-stats-grid" style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;margin-bottom:2rem;">
                        <div class="profile-stat-card">
                            <div style="font-family:'Inter',sans-serif;font-size:2.8rem;font-weight:800;color:#C97F5F;"><?= $total_orders ?></div>
                            <div style="font-family:'Inter',sans-serif;font-size:1.25rem;color:#9a8f8b;margin-top:.3rem;">Total Orders</div>
                        </div>

                        <div class="profile-stat-card">
                            <div style="font-family:'Inter',sans-serif;font-size:2.8rem;font-weight:800;color:#2563eb;"><?= $pending_orders ?></div>
                            <div style="font-family:'Inter',sans-serif;font-size:1.25rem;color:#9a8f8b;margin-top:.3rem;">Pending</div>
                        </div>

                        <div class="profile-stat-card">
                            <div style="font-family:'Inter',sans-serif;font-size:2.8rem;font-weight:800;color:#16a34a;"><?= $done_orders ?></div>
                            <div style="font-family:'Inter',sans-serif;font-size:1.25rem;color:#9a8f8b;margin-top:.3rem;">Completed</div>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div style="background:#fff;border:1px solid rgba(201,127,95,.15);border-radius:18px;padding:2rem 2.5rem;box-shadow:0 2px 12px rgba(60,56,54,.05);">
                        <h4 style="font-family:'Inter',sans-serif;font-size:1.5rem;font-weight:700;color:#3C3836;margin-bottom:1.5rem;padding-bottom:1.2rem;border-bottom:2px solid #f5f0ec;">
                            <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C97F5F" stroke-width="2" style="vertical-align:middle;margin-right:.6rem;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                            </svg>
                            Account Information
                        </h4>

                        <div class="row">
                            <div class="col-lg-6">
                                <div class="profile-info-row">
                                    <div class="profile-info-icon">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C97F5F" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>

                                    <div style="flex:1;">
                                        <div style="font-family:'Inter',sans-serif;font-size:1.15rem;color:#9a8f8b;margin-bottom:.2rem;">First Name</div>
                                        <div style="font-family:'Inter',sans-serif;font-size:1.4rem;font-weight:600;color:#3C3836;"><?= htmlspecialchars($user_data['firstname'] ?? '—') ?></div>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-icon">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C97F5F" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>

                                    <div style="flex:1;">
                                        <div style="font-family:'Inter',sans-serif;font-size:1.15rem;color:#9a8f8b;margin-bottom:.2rem;">Username</div>
                                        <div style="font-family:'Inter',sans-serif;font-size:1.4rem;font-weight:600;color:#3C3836;"><?= htmlspecialchars($user_data['username'] ?? '—') ?></div>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-icon">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C97F5F" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>

                                    <div style="flex:1;">
                                        <div style="font-family:'Inter',sans-serif;font-size:1.15rem;color:#9a8f8b;margin-bottom:.2rem;">Primary Phone</div>
                                        <div style="font-family:'Inter',sans-serif;font-size:1.4rem;font-weight:600;color:#3C3836;"><?= htmlspecialchars($user_data['work_phone_no'] ?? '—') ?></div>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-icon">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C97F5F" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>

                                    <div style="flex:1;">
                                        <div style="font-family:'Inter',sans-serif;font-size:1.15rem;color:#9a8f8b;margin-bottom:.2rem;">City</div>
                                        <div style="font-family:'Inter',sans-serif;font-size:1.4rem;font-weight:600;color:#3C3836;"><?= htmlspecialchars($user_data['city_name'] ?? '—') ?></div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-6">
                                <div class="profile-info-row">
                                    <div class="profile-info-icon">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C97F5F" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>

                                    <div style="flex:1;">
                                        <div style="font-family:'Inter',sans-serif;font-size:1.15rem;color:#9a8f8b;margin-bottom:.2rem;">Last Name</div>
                                        <div style="font-family:'Inter',sans-serif;font-size:1.4rem;font-weight:600;color:#3C3836;"><?= htmlspecialchars($user_data['lastname'] ?? '—') ?></div>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-icon">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C97F5F" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div style="flex:1;">
                                        <div style="font-family:'Inter',sans-serif;font-size:1.15rem;color:#9a8f8b;margin-bottom:.2rem;">Email Address</div>
                                        <div style="font-family:'Inter',sans-serif;font-size:1.4rem;font-weight:600;color:#3C3836;"><?= htmlspecialchars($user_data['email'] ?? '—') ?></div>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-icon">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C97F5F" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                        </svg>
                                    </div>

                                    <div style="flex:1;">
                                        <div style="font-family:'Inter',sans-serif;font-size:1.15rem;color:#9a8f8b;margin-bottom:.2rem;">Secondary Phone</div>
                                        <div style="font-family:'Inter',sans-serif;font-size:1.4rem;font-weight:600;color:#3C3836;"><?= htmlspecialchars($user_data['phone_no'] ?? '—') ?></div>
                                    </div>
                                </div>

                                <div class="profile-info-row">
                                    <div class="profile-info-icon">
                                        <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="#C97F5F" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                    </div>

                                    <div style="flex:1;">
                                        <div style="font-family:'Inter',sans-serif;font-size:1.15rem;color:#9a8f8b;margin-bottom:.2rem;">Address</div>
                                        <div style="font-family:'Inter',sans-serif;font-size:1.4rem;font-weight:600;color:#3C3836;"><?= htmlspecialchars($user_data['address'] ?? '—') ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Edit Profile Modal -->
<div class="modal fade edit-profile-modal" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header border-0" style="background: linear-gradient(135deg,#C97F5F,#b56a4a); color:#fff;">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2">
                    <i class="fa-solid fa-user-pen"></i> Edit Profile
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <form id="editProfileForm" enctype="multipart/form-data" id="editProfileForm">
                <input type="hidden" name="update_profile" value="1">
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label" for="ep_firstname">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ep_firstname" name="ep_firstname" oninput="onlyAlphabets(this)" value="<?= htmlspecialchars($user_data['firstname'] ?? '') ?>" required placeholder="First name">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="ep_lastname">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ep_lastname" name="ep_lastname" oninput="onlyAlphabets(this)" value="<?= htmlspecialchars($user_data['lastname'] ?? '') ?>" required placeholder="Last name">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="ep_phone">Primary Phone</label>
                            <input type="tel" class="form-control" id="ep_phone" name="ep_phone" oninput="validatePhone(this)" value="<?= htmlspecialchars($user_data['work_phone_no'] ?? '') ?>" placeholder="+1 234 567 8900">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="ep_phone2">Secondary Phone</label>
                            <input type="tel" class="form-control" id="ep_phone2" name="ep_phone2" oninput="validatePhone(this)" value="<?= htmlspecialchars($user_data['phone_no'] ?? '') ?>" placeholder="+1 234 567 8900">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="ep_city">City</label>
                            <input type="text" class="form-control" id="ep_city" name="ep_city" oninput="onlyAlphabets(this)" value="<?= htmlspecialchars($user_data['city_name'] ?? '') ?>" placeholder="Your city">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label" for="ep_email">Email Address</label>
                            <input type="email" class="form-control" value="<?= htmlspecialchars($user_data['email'] ?? '') ?>" readonly style="background:#f8f5f3;cursor:not-allowed;">
                            <div class="form-text" style="font-size:1.15rem;color:#9a8f8b;">Email cannot be changed.</div>
                        </div>

                        <div class="col-12">
                            <label class="form-label" for="ep_address">Address</label>
                            <textarea class="form-control" id="ep_address" name="ep_address" rows="2" placeholder="Street address, apartment, etc."><?= htmlspecialchars($user_data['address'] ?? '') ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"
                        style="font-family:'Inter',sans-serif;font-size:1.35rem;border-radius:50px;padding:.6rem 1.8rem;">
                        Cancel
                    </button>

                    <button type="button" onclick="profileValidationCheck()" id="update-btn"
                        style="display:inline-flex;align-items:center;gap:.5rem;background:linear-gradient(135deg,#C97F5F,#b56a4a);color:#fff;font-family:'Inter',sans-serif;font-size:1.35rem;font-weight:700;padding:.65rem 2rem;border-radius:50px;border:none;cursor:pointer;box-shadow:0 4px 16px rgba(201,127,95,.25);">
                        <i class="fa-solid fa-floppy-disk"></i> Update Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function profileValidationCheck() {
        const firstname = document.getElementById("ep_firstname");
        const lastname = document.getElementById("ep_lastname");
        const phone = document.getElementById("ep_phone");
        const phone2 = document.getElementById("ep_phone2");
        const city = document.getElementById("ep_city");
        const address = document.getElementById("ep_address");
        const btn = document.getElementById("update-btn");
        const form = document.getElementById("editProfileForm");

        let isValid = true;
        const phoneRegex = /^\+?[0-9]{10,15}$/;

        // Reset messages
        [firstname, lastname, phone, phone2, city, address].forEach((field) => {
            field.setCustomValidity("");
            field.classList.remove('is-invalid');
        });

        // ✅ Fname Validation
        if (firstname.value.trim() === "") {
            firstname.setCustomValidity("Please enter your firstname.");
            firstname.classList.add('is-invalid');
            isValid = false;
        } else if (firstname.value.trim().length < 3) {
            firstname.setCustomValidity("First name must be at least 3 characters.");
            firstname.classList.add('is-invalid');
            isValid = false;
        }

        // ✅ Lname Validation
        if (lastname.value.trim() === "") {
            lastname.setCustomValidity("Please enter your lastname.");
            lastname.classList.add('is-invalid');
            isValid = false;
        } else if (lastname.value.trim().length < 3) {
            lastname.setCustomValidity("Last name must be at least 3 characters.");
            lastname.classList.add('is-invalid');
            isValid = false;
        }

        // ✅ Phone Validation
        if (phone.value.trim() === "") {
            phone.setCustomValidity("Please enter your phone number.");
            phone.classList.add('is-invalid');
            isValid = false;
        } else if (!phoneRegex.test(phone.value.trim())) {
            phone.setCustomValidity("Enter a valid phone number (10–15 digits).");
            phone.classList.add('is-invalid');
            isValid = false;
        }

        if (phone2.value.trim() !== "") {
            if (!phoneRegex.test(phone2.value.trim())) {
                phone2.setCustomValidity("Enter a valid alternate phone number.");
                phone2.classList.add('is-invalid');
                isValid = false;
            }
        }

        // ✅ City Validation
        if (city.value.trim() === "") {
            city.setCustomValidity("Please enter city.");
            city.classList.add('is-invalid');
            isValid = false;
        } else if (city.value.trim().length < 3) {
            city.setCustomValidity("City must be at least 3 characters.");
            city.classList.add('is-invalid');
            isValid = false;
        }

        // ✅ Address Validation
        if (address.value.trim() === "") {
            address.setCustomValidity("Please enter your address.");
            address.classList.add('is-invalid');
            isValid = false;
        } else if (address.value.trim().length < 10) {
            address.setCustomValidity("Message must be at least 10 characters.");
            address.classList.add('is-invalid');
            isValid = false;
        }

        // ❌ Show validation errors
        if (!isValid) {
            form.reportValidity();
            return;
        }

        // ✅ If valid
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Updating Changes...';

        update();
    }

    function update() {
        const btn = document.getElementById("update-btn");
        const formData = {
            firstname: document.getElementById("ep_firstname").value.trim(),
            lastname: document.getElementById("ep_lastname").value.trim(),
            phone: document.getElementById("ep_phone").value.trim(),
            phone2: document.getElementById("ep_phone2").value.trim(),
            city: document.getElementById("ep_city").value.trim(),
            address: document.getElementById("ep_address").value.trim(),
        };

        $.ajax({
            url: window.location.href,
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(res) {
                if (res.status) {
                    showAlert("success", res.message);
                    const modal = bootstrap.Modal.getInstance(document.getElementById('editProfileModal'));
                    if (modal) modal.hide();
                } else {
                    showAlert("error", res.message);
                }

                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-floppy-disk"></i> Update Changes`;
            },
            error: function(xhr) {
                console.log("RAW RESPONSE:", xhr.responseText);
                let msg = "Something went wrong. Please try again.";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                showAlert("error", msg);

                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-floppy-disk"></i> Update Changes`;
            }
        });
    }
</script>

<?php include_once __DIR__ . '/../includes/footer.php'; ?>