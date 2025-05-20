<?php
session_start();
require_once '../config/db.php';

// Restrict access to logged-in admins
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'super_admin') {
    header("Location: ../auth/login.php");
    exit();
}

// Optional: Define super admin username or ID
$isSuperAdmin = $_SESSION['username'] === 'admin'; // or check against `role = 'super_admin'` if you prefer
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <p>You are logged in as <strong><?php echo $_SESSION['role']; ?></strong></p>

    <nav>
        <ul>
            <li><a href="inventory.php">Manage Inventory</a></li>
            <li><a href="add_product.php">Add New Product</a></li>

            <?php if ($isSuperAdmin): ?>
                <li><a href="users_management.php">User Management</a></li>
            <?php endif; ?>

            <li><a href="../auth/logout.php">Logout</a></li>
        </ul>
    </nav>
</body>
</html>
