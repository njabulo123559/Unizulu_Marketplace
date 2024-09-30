<?php
// Ensure that session is only started once
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Start the session if it hasn't started yet
}

if (isset($message)) {
    foreach ($message as $message) {
        echo '
        <div class="message">
            <span>' . $message . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}

// Check if admin is logged in
if (isset($_SESSION['admin_id'])) {
    $admin_id = $_SESSION['admin_id'];

    // Prepare and execute the query using MySQLi
    if ($stmt = $conn->prepare("SELECT name FROM admin WHERE id = ?")) {
        $stmt->bind_param('i', $admin_id); // Bind the admin_id parameter
        $stmt->execute();
        $stmt->bind_result($name);
        $stmt->fetch();
        $stmt->close();
    } else {
        // If no admin found, redirect to login page
        header('location:admin_login.php');
        exit();
    }
} else {
    // Redirect to login if not logged in
    header('location:admin_login.php');
    exit();
}
?>

<header class="header">
    <section class="flex">
        <a href="dashboard.php" class="logo">Admin<span>Panel</span></a>
        <nav class="navbar">
            <a href="dashboard.php">Home</a>
            <a href="products.php">Products</a>
            <a href="placed_orders.php">Orders</a>
            <a href="admin_accounts.php">Admins</a>
            <a href="users_accounts.php">Users</a>
            <a href="messages.php">Messages</a>
        </nav>

        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <div id="user-btn" class="fas fa-user"></div>
        </div>

        <div class="profile">
            <p><?= isset($name) ? $name : 'Admin'; ?></p>
            <a href="update_profile.php" class="btn">Update Profile</a>
            <div class="flex-btn">
                <a href="admin_login.php" class="option-btn">Login</a>
                <a href="register_admin.php" class="option-btn">Register</a>
            </div>
            <a href="../components/admin_logout.php" onclick="return confirm('logout from this website?');" class="delete-btn">Logout</a>
        </div>
    </section>
</header>
