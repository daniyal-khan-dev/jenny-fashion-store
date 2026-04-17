function switchTab(tab) {
  document.getElementById("form-login").classList.remove("active");
  document.getElementById("form-signup").classList.remove("active");
  document.getElementById("tab-login").classList.remove("active");
  document.getElementById("tab-signup").classList.remove("active");

  document.getElementById("form-" + tab).classList.add("active");
  document.getElementById("tab-" + tab).classList.add("active");
}

function validateUsername(input) {
  input.value = input.value.replace(/[^a-zA-Z0-9_.]/g, "");
}

function onlyAlphabets(input) {
  input.value = input.value.replace(/[^a-zA-Z\s]/g, '');
}

function validateEmail(input) {
  let value = input.value;
  value = value.toLowerCase();
  value = value.replace(/\s/g, "");
  value = value.replace(/[^a-z0-9@._]/g, "");
  input.value = value;
}

function validatePassword() {
  const pass = document.getElementById("reg_pass");
  const cpass = document.getElementById("reg_cpass");
  const msg = document.getElementById("pass-msg");
  const confirmmsg = document.getElementById("confirm-pass-msg");

  const value = pass.value;
  const cvalue = cpass.value;

  // Regex: min 7 chars, 1 letter, 1 number, 1 special char
  const pattern = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d]).{7,}$/;

  // 🔹 Password validation
  if (value.length === 0) {
    msg.textContent = "";
    pass.style.borderColor = "";
  } else if (!pattern.test(value)) {
    msg.textContent =
      "Password must be at least 7 characters and include a letter, number, and special character.";
    msg.style.color = "red";
    pass.style.borderColor = "red";
  } else {
    msg.textContent = "Strong password ✔";
    msg.style.color = "green";
    pass.style.borderColor = "green";
  }

  // 🔹 Confirm password validation
  if (cvalue.length === 0) {
    confirmmsg.textContent = "";
    cpass.style.borderColor = "";
  } else if (value !== cvalue) {
    confirmmsg.textContent = "Passwords do not match";
    confirmmsg.style.color = "red";
    cpass.style.borderColor = "red";
  } else {
    confirmmsg.textContent = "Passwords match ✔";
    confirmmsg.style.color = "green";
    cpass.style.borderColor = "green";
  }
}

function togglePass(inputId, btn) {
  var input = document.getElementById(inputId);
  var isText = input.type === "text";
  input.type = isText ? "password" : "text";
  btn.querySelector("svg").innerHTML = isText
    ? '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>'
    : '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
}

function signInValidationCheck() {
  const email = document.getElementById("login_email");
  const password = document.getElementById("login_pass");
  const loginBtn = document.getElementById("login-btn");
  const form = document.getElementById("signInForm");

  const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d]).{7,}$/;

  let isValid = true;

  // Reset all custom messages
  [email, password].forEach((field) => field.setCustomValidity(""));

  // Email
  if (email.value.trim() === "") {
    email.setCustomValidity("Please enter your email address.");
    isValid = false;
  } else if (!emailRegex.test(email.value.trim())) {
    email.setCustomValidity("Please enter a valid email address.");
    isValid = false;
  }
  
  // Password
  if (password.value.trim() === "") {
    password.setCustomValidity("Please enter a password.");
    isValid = false;
  } else if (!passwordRegex.test(password.value.trim())) {
    password.setCustomValidity(
      "Password must be 7+ characters with letter, number, and special character.",
    );
    isValid = false;
  }

  // Show messages if invalid
  if (!isValid) {
    form.reportValidity();
    return;
  }

  loginBtn.disabled = true;
  loginBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Logging in...';

  SignIn();
}

function SignIn() {
  const loginBtn = document.getElementById("login-btn");

  const formData = {
    login_email: document.getElementById("login_email").value.trim(),
    login_pass: document.getElementById("login_pass").value.trim(),
  };

  $.ajax({
    url: window.routes.signin,
    type: "POST",
    data: formData,
    dataType: "json",
    success: function (res) {
      if (res.status) {
        showAlert("success", res.message);
        document.getElementById("signInForm").reset();

        // redirect from backend
        if (res.data && res.data.redirect) {
          window.location.href = res.data.redirect;
        }

      } else {
        showAlert("error", res.message);
      }
      loginBtn.disabled = false;
      loginBtn.innerHTML = "Sign In";
    },
    error: function () {
      showAlert("error", "Something went wrong. Please try again.");
      loginBtn.disabled = false;
      loginBtn.innerHTML = "Sign In";
    },
  });
}

function signUpValidationCheck() {
  const firstName = document.getElementById("reg_firstname");
  const lastName = document.getElementById("reg_lastname");
  const username = document.getElementById("reg_username");
  const email = document.getElementById("reg_email");
  const password = document.getElementById("reg_pass");
  const cPassword = document.getElementById("reg_cpass");

  const signUpBtn = document.getElementById("signUp-btn");
  const form = document.getElementById("signUpForm");

  const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  const usernameRegex = /^[a-zA-Z0-9_.]+$/;
  const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)(?=.*[^A-Za-z\d]).{7,}$/;

  let isValid = true;

  // reset validity
  [firstName, lastName, username, email, password, cPassword].forEach(
    (field) => {
      field.setCustomValidity("");
    },
  );
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
  // Password
  if (password.value.trim() === "") {
    password.setCustomValidity("Please enter a password.");
    isValid = false;
  } else if (!passwordRegex.test(password.value.trim())) {
    password.setCustomValidity(
      "Password must be 7+ characters with letter, number, and special character.",
    );
    isValid = false;
  }

  // Confirm Password
  if (cPassword.value.trim() === "") {
    cPassword.setCustomValidity("Please confirm your password.");
    isValid = false;
  } else if (password.value !== cPassword.value) {
    cPassword.setCustomValidity("Passwords do not match.");
    isValid = false;
  }

  // Show messages if invalid
  if (!isValid) {
    form.reportValidity();
    return;
  }

  signUpBtn.disabled = true;
  signUpBtn.innerHTML =
    '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Signing up...';

  SignUp();
}

function SignUp() {
  const signUpBtn = document.getElementById("signUp-btn");
  const pass = document.getElementById("reg_pass");
  const cpass = document.getElementById("reg_cpass");
  const msg = document.getElementById("pass-msg");
  const confirmmsg = document.getElementById("confirm-pass-msg");

  const formData = {
    firstName: document.getElementById("reg_firstname").value.trim(),
    lastName: document.getElementById("reg_lastname").value.trim(),
    username: document.getElementById("reg_username").value.trim(),
    email: document.getElementById("reg_email").value.trim(),
    password: document.getElementById("reg_pass").value.trim(),
    cPassword: document.getElementById("reg_cpass").value.trim(),
  };

  $.ajax({
    url: window.routes.signup,
    type: "POST",
    data: formData,
    dataType: "json",
    success: function (response) {
      if (response.status === false) {
        showAlert("error", response.message || "Error occurred!");
      } else {
        showAlert("success", response.message || "Signup successful!");
        document.getElementById("signUpForm").reset();
        switchTab("login");
      }
      
      signUpBtn.disabled = false;
      signUpBtn.innerHTML = "Sign Up";
      msg.textContent = "";
      pass.style.borderColor = "";
      confirmmsg.textContent = "";
      cpass.style.borderColor = "";
    },
    error: function (xhr) {
      let res = xhr.responseJSON;
      showAlert("error", res.message);
      signUpBtn.disabled = false;
      signUpBtn.innerHTML = "Sign Up";
    }
    
  });
}

function showAlert(type, message) {
  const alert =
    type === "success"
      ? document.getElementById("successAlert")
      : document.getElementById("errorAlert");
  const text =
    type === "success"
      ? document.getElementById("successAlertText")
      : document.getElementById("errorAlertText");

  text.innerHTML = message;
  alert.classList.add("show");
  alert.style.display = "block";

  setTimeout(() => {
    if (type === "success") hideSuccessAlert();
    else hideErrorAlert();
  }, 5000);
}

function hideSuccessAlert() {
  const alert = document.getElementById("successAlert");
  alert.classList.remove("show");
  setTimeout(() => (alert.style.display = "none"), 150); // Smooth hide
}

function hideErrorAlert() {
  const alert = document.getElementById("errorAlert");
  alert.classList.remove("show");
  setTimeout(() => (alert.style.display = "none"), 150); // Smooth hide
}
