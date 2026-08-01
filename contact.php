<?php
session_start();
include_once('functions/userfunction.php');

$success = false;
$errors  = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');

    if ($name === '') $errors[] = 'Name is required.';
    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required.';
    if ($subject === '') $errors[] = 'Subject is required.';
    if ($message === '') $errors[] = 'Message is required.';

    if (empty($errors)) {
        $user_id = $_SESSION['auth_user']['id'] ?? 0;

        $stmt = $con->prepare("INSERT INTO contact_messages 
        (user_id, name, email, subject, message) 
        VALUES (?, ?, ?, ?, ?)");

        $stmt->bind_param("issss", $user_id, $name, $email, $subject, $message);

        if ($stmt->execute()) {
            $success = true;
        } else {
            $errors[] = 'Failed to send message.';
        }
    }

    echo json_encode([
        "status" => $success,
        "message" => $success ? "Message sent Successfully" : implode(' ', $errors)
    ]);
    exit;
}
include('pages/includes/header.php');

?>

<section class="contact__section">
    <div class="container">
        <div class="section__heading text-center mb-50">
            <h2 class="section__heading--maintitle">Get In Touch</h2>
            <p style="font-family:'Inter',sans-serif;font-size:1.6rem;color:#9a8f8b;max-width:500px;margin:0 auto;">
                Have a question or need help? We'd love to hear from you. Send us a message and we'll respond soon.
            </p>
        </div>

        <div class="row g-4">
            <!-- Contact Info -->
            <div class="col-lg-4">
                <div class="contact__card" style="height:100%;">
                    <h3 style="font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:700;color:#3C3836;margin-bottom:2.5rem;">Contact Information</h3>

                    <div class="contact__info-item">
                        <div class="contact__info-icon">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>

                        <div>
                            <div class="contact__info-label">Our Location</div>
                            <div class="contact__info-value">Faisal Cantonment Karachi, Pakistan</div>
                        </div>
                    </div>

                    <div class="contact__info-item">
                        <div class="contact__info-icon">
                            <i class="fa-solid fa-phone"></i>
                        </div>

                        <div>
                            <div class="contact__info-label">Phone Number</div>
                            <div class="contact__info-value">+92 317 2959985</div>
                        </div>
                    </div>

                    <div class="contact__info-item">
                        <div class="contact__info-icon">
                            <i class="fa-solid fa-envelope"></i>
                        </div>

                        <div>
                            <div class="contact__info-label">Email Address</div>
                            <div class="contact__info-value">info@daniyal-khan.com</div>
                        </div>
                    </div>

                    <div class="contact__info-item">
                        <div class="contact__info-icon">
                            <i class="fa-regular fa-clock"></i>
                        </div>
                        <div>
                            <div class="contact__info-label">Business Hours</div>
                            <div class="contact__info-value">Mon – Fri: 9am – 6pm</div>
                        </div>
                    </div>

                    <!-- Social -->
                    <div style="display:flex;gap:1rem;margin-top:1rem;">
                        <a href="#" style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#C97F5F,#b56a4a);color:#fff;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:1.6rem;transition:transform .2s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform=''"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#C97F5F,#b56a4a);color:#fff;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:1.6rem;transition:transform .2s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform=''"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" style="width:40px;height:40px;border-radius:10px;background:linear-gradient(135deg,#C97F5F,#b56a4a);color:#fff;display:flex;align-items:center;justify-content:center;text-decoration:none;font-size:1.6rem;transition:transform .2s ease;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform=''"><i class="fa-brands fa-twitter"></i></a>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="contact__card">
                    <h3 style="font-family:'Playfair Display',serif;font-size:2.2rem;font-weight:700;color:#3C3836;margin-bottom:2.5rem;">Send Us a Message</h3>

                    <form id="contactForm" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="contact__form-label" for="contact_name">Your Name <span class="text-danger">*</span></label>
                                <input type="text" class="contact__form-input" id="contact_name" oninput="onlyAlphabets(this)" name="contact_name" placeholder="John Doe" required>
                            </div>

                            <div class="col-md-6">
                                <label class="contact__form-label" for="contact_email">Email Address <span class="text-danger">*</span></label>
                                <input type="email" class="contact__form-input" id="contact_email" name="contact_email" oninput="validateEmail(this)" placeholder="john@example.com" required>
                            </div>

                            <div class="col-12">
                                <label class="contact__form-label" for="contact_subject">Subject <span class="text-danger">*</span></label>
                                <input type="text" class="contact__form-input" id="contact_subject" name="contact_subject" placeholder="How can we help you?" required>
                            </div>

                            <div class="col-12">
                                <label class="contact__form-label" for="contact_message">Message <span class="text-danger">*</span></label>
                                <textarea class="contact__form-input" id="contact_message" name="contact_message" rows="5" placeholder="Write your message here..." required></textarea>
                            </div>

                            <div class="col-12">
                                <button type="button" onclick="submitFormValidationCheck()" id="submit-btn"
                                    style="display:inline-flex;align-items:center;gap:.6rem;background:linear-gradient(135deg,#C97F5F,#b56a4a);color:#fff;font-family:'Inter',sans-serif;font-size:1.5rem;font-weight:700;padding:.9rem 3rem;border-radius:50px;border:none;cursor:pointer;box-shadow:0 4px 16px rgba(201,127,95,.3);transition:all .25s ease;"
                                    onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(201,127,95,.4)'"
                                    onmouseout="this.style.transform='';this.style.boxShadow='0 4px 16px rgba(201,127,95,.3)'">
                                    <i class="fa-solid fa-paper-plane"></i> Send Message
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function submitFormValidationCheck() {
        const name = document.getElementById("contact_name");
        const email = document.getElementById("contact_email");
        const subject = document.getElementById("contact_subject");
        const message = document.getElementById("contact_message");
        const btn = document.getElementById("submit-btn");
        const form = document.getElementById("contactForm");

        const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        let isValid = true;

        // Reset messages
        [name, email, subject, message].forEach((field) => {
            field.setCustomValidity("");
            field.classList.remove('is-invalid');
        });

        // ✅ Name Validation
        if (name.value.trim() === "") {
            name.setCustomValidity("Please enter your name.");
            name.classList.add('is-invalid');
            isValid = false;
        } else if (name.value.trim().length < 3) {
            name.setCustomValidity("Name must be at least 3 characters.");
            name.classList.add('is-invalid');
            isValid = false;
        }

        // ✅ Email Validation
        if (email.value.trim() === "") {
            email.setCustomValidity("Please enter your email address.");
            email.classList.add('is-invalid');
            isValid = false;
        } else if (!emailRegex.test(email.value.trim())) {
            email.setCustomValidity("Please enter a valid email address.");
            email.classList.add('is-invalid');
            isValid = false;
        }

        // ✅ Subject Validation
        if (subject.value.trim() === "") {
            subject.setCustomValidity("Please enter subject.");
            subject.classList.add('is-invalid');
            isValid = false;
        } else if (subject.value.trim().length < 5) {
            subject.setCustomValidity("Subject must be at least 5 characters.");
            subject.classList.add('is-invalid');
            isValid = false;
        }

        // ✅ Message Validation
        if (message.value.trim() === "") {
            message.setCustomValidity("Please enter your message.");
            message.classList.add('is-invalid');
            isValid = false;
        } else if (message.value.trim().length < 10) {
            message.setCustomValidity("Message must be at least 10 characters.");
            message.classList.add('is-invalid');
            isValid = false;
        }

        // ❌ Show validation errors
        if (!isValid) {
            form.reportValidity();
            return;
        }

        // ✅ If valid
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Sending Message...';

        submit();
    }

    function submit() {
        const btn = document.getElementById("submit-btn");
        const formData = {
            name: document.getElementById("contact_name").value.trim(),
            email: document.getElementById("contact_email").value.trim(),
            subject: document.getElementById("contact_subject").value.trim(),
            message: document.getElementById("contact_message").value.trim(),
        };

        $.ajax({
            url: window.location.href,
            type: "POST",
            data: formData,
            dataType: "json",
            success: function(res) {
                if (res.status) {
                    showAlert("success", res.message);
                    document.getElementById("contactForm").reset();
                } else {
                    showAlert("error", res.message);
                }

                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-paper-plane"></i> Send Message`;
            },
            error: function(xhr) {
                console.log("RAW RESPONSE:", xhr.responseText); // 👈 MUST CHECK

                let msg = "Something went wrong. Please try again.";

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }

                showAlert("error", msg);

                btn.disabled = false;
                btn.innerHTML = `<i class="fa-solid fa-paper-plane"></i> Send Message`;
            }
        });
    }
</script>

<?php include('Pages/includes/footer.php'); ?>