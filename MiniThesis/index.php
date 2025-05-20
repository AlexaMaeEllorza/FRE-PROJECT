<?php
session_start();

// If the user is already logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'admin') {
        header("Location: admin/dashboard.php");
        exit();
    } else {
        header("Location: user/home.php");
        exit();
    }
} else {
    // Not logged in yet
    header("Location: auth/login.php");
    exit();
}

?>