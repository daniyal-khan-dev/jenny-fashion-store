<?php
// Check if user is logged in
if (isset($_SESSION['auth'])) {
    if ($_SESSION['user_role'] != 0) {
        header('Location: /jenny/admin/');
        exit();
    }
}

?>