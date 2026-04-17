document.addEventListener("DOMContentLoaded", function () {
  // Sidebar element
  var sidenav = document.querySelector("#sidenav-main");

  if (sidenav) {
    const ps = new PerfectScrollbar(sidenav, {
      wheelSpeed: 1,
      wheelPropagation: true,
      minScrollbarLength: 20,
    });
  }

  // Toggle Sidebar (Mobile)
  var toggler = document.getElementById("iconNavbarSidenav");
  var body = document.body;

  if (toggler) {
    toggler.addEventListener("click", function () {
      body.classList.toggle("g-sidenav-pinned");
    });
  }

  const path = window.location.pathname;

  if (path.includes("/admin/category")) {
    loadCategories();
  }

  if (path.includes("/admin/products")) {
    loadProducts();
  }

  if (path.includes("/admin/admins")) {
    loadAdmins();
  }
});

function loadCategories() {
  $.ajax({
    url: window.routes.getCategory,
    type: "GET",
    success: function (html) {
      $("#catTable tbody").html(html);
    },
  });
}

function loadProducts() {
  $.ajax({
    url: window.routes.getProduct,
    type: "GET",
    success: function (html) {
      $("#prodTable tbody").html(html);
    },
  });
}

function loadAdmins() {
  $.ajax({
    url: window.routes.getAdmin,
    type: "GET",
    success: function (html) {
      $("#adminTable tbody").html(html);
    },
  });
}

function allowValidChars(input) {
  let value = input.value;
  value = value.replace(/[^a-zA-Z0-9 -]/g, "");
  value = value.replace(/\s+/g, " ");
  value = value.replace(/-+/g, "-");
  value = value.replace(/^\s+/, "");
  input.value = value;
}

function validateUsername(input) {
  input.value = input.value.replace(/[^a-zA-Z0-9_.]/g, "");
}

function onlyAlphabets(input) {
  input.value = input.value.replace(/[^a-zA-Z\s]/g, "");
}

function validateEmail(input) {
  let value = input.value;
  value = value.toLowerCase();
  value = value.replace(/\s/g, "");
  value = value.replace(/[^a-z0-9@._]/g, "");
  input.value = value;
}

function validatePassword() {
  const pass = document.getElementById("aAdminPass");
  const cpass = document.getElementById("aAdminCPass");
  const msg = document.getElementById("pass-msg");
  const confirmmsg = document.getElementById("cpass-msg");

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

function validateCPassword() {
  const pass = document.getElementById("eAdminPass");
  const msg = document.getElementById("epass-msg");

  const value = pass.value;

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
}

function togglePass(inputId, btn) {
  var input = document.getElementById(inputId);
  var isText = input.type === "text";
  input.type = isText ? "password" : "text";
  btn.querySelector("svg").innerHTML = isText
    ? '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>'
    : '<path d="M17.94 17.94A10.07 10.07 0 0112 20c-7 0-11-8-11-8a18.45 18.45 0 015.06-5.94M9.9 4.24A9.12 9.12 0 0112 4c7 0 11 8 11 8a18.5 18.5 0 01-2.16 3.19m-6.72-1.07a3 3 0 11-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
}

// REUSABLE VALIDATION FUNCTION ON ADD OR UPDATE
function validateForm({ formId, fields, btn, btnTxt, onSuccess }) {
  const form = document.getElementById(formId);
  const Button = document.getElementById(btn);

  let isValid = true;

  fields.forEach((field) => {
    const input = document.getElementById(field.id);
    if (!input) return; // Skip if input is not found

    input.setCustomValidity("");

    // Handle file inputs separately
    let value = input.type === "file" ? input.value : input.value.trim();

    // Check if empty or skipIf value
    if (!value || (field.skipIf && value === field.skipIf)) {
      input.setCustomValidity(field.message);
      isValid = false;
    }

    // Check minimum numeric value
    if (
      field.min !== undefined &&
      !isNaN(value) &&
      parseFloat(value) <= field.min
    ) {
      input.setCustomValidity(
        `${field.id.replace(/_/g, " ")} must be greater than ${field.min}`,
      );
      isValid = false;
    }

    // ✅ Check maximum numeric value
    if (
      field.max !== undefined &&
      !isNaN(value) &&
      parseFloat(value) > field.max
    ) {
      input.setCustomValidity(
        `${field.id.replace(/_/g, " ")} must be less than or equal to ${field.max}`,
      );
      isValid = false;
    }

    // Check minimum string length
    if (field.minLength !== undefined && value.length < field.minLength) {
      input.setCustomValidity(
        `${field.id.replace(/_/g, " ")} must be at least ${field.minLength} characters long`,
      );
      isValid = false;
    }

    // ✅ Check maximum string length
    if (field.maxLength !== undefined && value.length > field.maxLength) {
      input.setCustomValidity(
        `${field.id.replace(/_/g, " ")} must be at most ${field.maxLength} characters long`,
      );
      isValid = false;
    }

    // ✅ Check: Email validation
    if (field.validate === "email" && value) {
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailRegex.test(value)) {
        input.setCustomValidity("Please enter a valid email address.");
        isValid = false;
      }
    }

    // ✅ Check: Phone number validation
    if (field.validate === "phone no" && value) {
      // Remove all invalid chars except digits, +, space
      let phone = value.replace(/[^\d\s+]/g, "");

      // + only at start
      if (phone.includes("+")) phone = "+" + phone.replace(/\+/g, "");

      // Single spaces only
      phone = phone.replace(/\s+/g, " ").trim();

      // Count digits
      let digits = phone.replace(/\D/g, "");
      if (digits.length === 0 || digits.length > 15) {
        input.setCustomValidity(
          "Please enter a valid phone number (max 15 digits).",
        );
        isValid = false;
      }

      input.value = phone; // update input with cleaned format
    }

    // Check accepted image types (jpeg, png, etc.)
    if (field.imgaccept && input.type === "file" && input.files.length > 0) {
      const file = input.files[0];
      const fileType = file.type.split("/")[1].toLowerCase(); // e.g., "image/jpeg" → "jpeg"
      const allowedTypes = field.imgaccept
        .split(",")
        .map((t) => t.trim().toLowerCase());

      if (!allowedTypes.includes(fileType)) {
        input.setCustomValidity(
          `Only ${allowedTypes.join(", ")} files are allowed`,
        );
        isValid = false;
      }
    }
  });

  if (!isValid) {
    form.reportValidity();
    return;
  }

  if (Button) {
    Button.disabled = true;
    if (Button.id == "add-Btn") {
      Button.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Saving ${btnTxt} ...`;
    } else if (Button.id == "update-Btn") {
      Button.innerHTML = `<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Updating ${btnTxt} ...`;
    }
  }

  if (typeof onSuccess === "function") {
    onSuccess();
  }
}
// REUSABLE SUBMIT FUNCTION ON ADD OR UPDATE
function submitFormData({
  formId,
  btn,
  btnTxt,
  url,
  successMessage,
  onSuccess,
}) {
  const formElement = document.getElementById(formId);
  const formData = new FormData(formElement);
  const Button = document.getElementById(btn);

  $.ajax({
    url: url,
    type: "POST",
    data: formData,
    dataType: "json",
    processData: false,
    contentType: false,
    success: function (response) {
      if (response.status) {
        // ✅ SUCCESS
        showAlert("success", successMessage || response.message);

        formElement.reset();

        // close modal
        if (response.data?.modal) {
          const modalEl = document.getElementById(response.data.modal);
          if (modalEl) {
            const modal =
              bootstrap.Modal.getInstance(modalEl) ||
              new bootstrap.Modal(modalEl);
            modal.hide();
          }
        }

        // reload function
        if (response.data?.redirect === "orders") {
          window.location.href = "/admin/orders";
        } else if (
          response.data?.getFunc &&
          typeof window[response.data.getFunc] === "function"
        ) {
          window[response.data.getFunc]();
        }

        if (typeof onSuccess === "function") {
          onSuccess(response);
        }
      } else {
        // ❌ ERROR FROM BACKEND (response(false, ...))
        showAlert("error", response.message);
      }

      // reset button
      if (Button) {
        Button.disabled = false;
        Button.innerHTML =
          Button.id == "add-Btn"
            ? `<i class="fa-solid fa-floppy-disk me-1"></i> Add ${btnTxt}`
            : `<i class="fa-solid fa-floppy-disk me-1"></i> Update ${btnTxt}`;
      }
    },
    error: function (xhr) {
      let response = {};

      try {
        response = xhr.responseJSON || JSON.parse(xhr.responseText);
      } catch (e) {
        response = { message: xhr.responseText };
      }

      if (xhr.status === 422) {
        let messages = response.message || "Validation error";
        showAlert("error", "Validation Error", messages);
      } else if (xhr.status >= 400) {
        showAlert("error", "Error!", response.message || "Server error");
      } else {
        showAlert("error", "Error!", response.message || "Network issue");
      }

      if (Button) {
        Button.disabled = false;
        if (Button.id == "add-Btn") {
          Button.innerHTML = `<i class="fa-solid fa-floppy-disk me-1"></i> Add ${btnTxt}`;
        } else {
          Button.innerHTML = `<i class="fa-solid fa-floppy-disk me-1"></i> Update ${btnTxt}`;
        }
      }
    },
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
