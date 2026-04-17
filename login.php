<?php
session_start();
if (isset($_SESSION['auth'])) {
    if ($_SESSION['user_role'] == 1) {
        header('Location: /jenny/admin/dashboard');
    } else {
        header('Location: /');
    }
    exit();
}
include('Pages/login/loginheader.php');
error_reporting(E_ALL);
ini_set('display_errors', 1);
?>

<!-- TAB SWITCHER -->
<div class="auth__tabs">
    <button class="auth__tab active" id="tab-login" onclick="switchTab('login')">Sign In</button>
    <button class="auth__tab" id="tab-signup" onclick="switchTab('signup')">Create Account</button>
</div>

<!-- ── SIGN IN FORM ── -->
<div class="auth__form active" id="form-login">
    <h2 class="auth__title">Welcome back</h2>
    <p class="auth__subtitle">Sign in to your account and continue shopping.</p>

    <form id="signInForm">
        <div class="auth__field">
            <label class="auth__label" for="login_email">Email Address</label>
            <div class="auth__input--wrap">
                <span class="auth__input--icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                </span>
                <input class="auth__input" id="login_email" oninput="validateEmail(this)" type="email" name="login_email" placeholder="name@example.com" autocomplete="email" required>
            </div>
        </div>

        <div class="auth__field">
            <label class="auth__label" for="login_pass">Password</label>
            <div class="auth__input--wrap">
                <span class="auth__input--icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" />
                        <path d="M7 11V7a5 5 0 0110 0v4" />
                    </svg>
                </span>
                <input class="auth__input" id="login_pass" type="password" name="login_pass" placeholder="Enter your password" autocomplete="current-password" required>
                <button type="button" class="auth__eye" onclick="togglePass('login_pass', this)" title="Show/Hide password">
                    <svg id="eye-login" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </button>
            </div>
        </div>
        <button type="button" id="login-btn" name="login-btn" class="auth__submit" onclick="signInValidationCheck();">Sign In</button>
    </form>

    <p class="auth__switch">
        Don't have an account?
        <button onclick="switchTab('signup')">Create one here</button>
    </p>
</div>

<!-- ── SIGN UP FORM ── -->
<div class="auth__form" id="form-signup">
    <h2 class="auth__title">Create account</h2>
    <p class="auth__subtitle">Join thousands of happy customers today.</p>

    <form id="signUpForm">
        <div class="auth__row" style="display: flex; gap: 15px;">
            <div class="auth__field" style="flex: 1;">
                <label class="auth__label" for="reg_firstname">First Name</label>
                <div class="auth__input--wrap">
                    <span class="auth__input--icon">
                        <!-- User icon -->
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="7" r="4" />
                            <path d="M5.5 21a6.5 6.5 0 0 1 13 0" />
                        </svg>
                    </span>
                    <input class="auth__input" id="reg_firstname" oninput="onlyAlphabets(this)" type="text" name="reg_firstname" placeholder="Enter First Name" autocomplete="given-name" required>
                </div>
            </div>

            <div class="auth__field" style="flex: 1;">
                <label class="auth__label" for="reg_lastname">Last Name</label>
                <div class="auth__input--wrap">
                    <span class="auth__input--icon">
                        <!-- User icon (same style, consistent UI) -->
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <circle cx="12" cy="7" r="4" />
                            <path d="M5.5 21a6.5 6.5 0 0 1 13 0" />
                        </svg>
                    </span>
                    <input class="auth__input" id="reg_lastname" type="text" oninput="onlyAlphabets(this)" name="reg_lastname" placeholder="Enter Last Name" autocomplete="family-name" required>
                </div>
            </div>
        </div>

        <div class="auth__field">
            <label class="auth__label" for="reg_username">Username</label>
            <div class="auth__input--wrap">
                <span class="auth__input--icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M16 12a4 4 0 1 1-4-4c2.2 0 4 1.8 4 4v1a2 2 0 0 0 4 0v-1" />
                    </svg>
                </span>
                <input class="auth__input" id="reg_username" type="text" name="reg_username" placeholder="Enter Username" oninput="validateUsername(this)" autocomplete="username" required>
            </div>
        </div>

        <div class="auth__field">
            <label class="auth__label" for="reg_email">Email Address</label>
            <div class="auth__input--wrap">
                <span class="auth__input--icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" />
                        <polyline points="22,6 12,13 2,6" />
                    </svg>
                </span>
                <input class="auth__input" id="reg_email" type="email" name="reg_email" oninput="validateEmail(this)" placeholder="name@example.com" autocomplete="email" required>
            </div>
        </div>

        <div class="auth__field">
            <label class="auth__label" for="reg_pass">Password</label>
            <div class="auth__input--wrap">
                <span class="auth__input--icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" />
                        <path d="M7 11V7a5 5 0 0110 0v4" />
                    </svg>
                </span>
                <input class="auth__input" id="reg_pass" oninput="validatePassword()" type="password" name="reg_pass" placeholder="Create a password" autocomplete="new-password" required>
                <button type="button" class="auth__eye" onclick="togglePass('reg_pass', this)" title="Show/Hide password">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </button>
            </div>
            <p id="pass-msg" style="font-size:14px; margin-top:5px;"></p>
        </div>

        <div class="auth__field">
            <label class="auth__label" for="reg_cpass">Confirm Password</label>
            <div class="auth__input--wrap">
                <span class="auth__input--icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <rect x="3" y="11" width="18" height="11" rx="2" />
                        <path d="M7 11V7a5 5 0 0110 0v4" />
                    </svg>
                </span>
                <input class="auth__input" id="reg_cpass" oninput="validatePassword()" type="password" name="reg_cpass" placeholder="Repeat your password" autocomplete="new-password" required>
                <button type="button" class="auth__eye" onclick="togglePass('reg_cpass', this)" title="Show/Hide password">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>
                </button>
            </div>
            <p id="confirm-pass-msg" style="font-size:14px; margin-top:5px;"></p>
        </div>
        <button type="button" id="signUp-btn" name="signUp-btn" class="auth__submit" onclick="signUpValidationCheck();">Sign up</button>
    </form>

    <p class="auth__switch">
        Already have an account?
        <button onclick="switchTab('login')">Sign in here</button>
    </p>
</div>

<?php include('pages/login/loginfooter.php'); ?>