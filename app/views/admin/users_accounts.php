<?php

include __DIR__ . '/../../../config/db.php';

session_start();

// Ensure admin is logged in
$admin_id = $_SESSION['admin_id'];
if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

// Handle user account deletion
if (isset($_GET['delete'])) {
    $delete_id = filter_var($_GET['delete'], FILTER_SANITIZE_NUMBER_INT);

    // Delete user account
    $delete_users = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $delete_users->bind_param("i", $delete_id);
    $delete_users->execute();

    // Delete user's related orders
    $delete_order = $conn->prepare("DELETE FROM `orders` WHERE user_id = ?");
    $delete_order->bind_param("i", $delete_id);
    $delete_order->execute();

    // Delete user's related cart entries
    $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart->bind_param("i", $delete_id);
    $delete_cart->execute();

    // Redirect to the same page after deletion
    header('location:users_accounts.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Accounts</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="http://localhost/project-root/assets/css/admin_style.css">

</head>
<body>

<?php include __DIR__ . '/../components/admin_header.php'; ?>

<!-- User accounts section starts -->
<section class="accounts">

    <h1 class="heading">Users' Accounts</h1>

    <div class="box-container">
        <?php
        // Fetch all user accounts
        $select_account = $conn->prepare("SELECT * FROM `users`");
        $select_account->execute();
        $result = $select_account->get_result();

        if ($result->num_rows > 0) {
            // Display each user account in a box
            while ($fetch_accounts = $result->fetch_assoc()) {
        ?>
        <div class="box">
            <p> User ID : <span><?= htmlspecialchars($fetch_accounts['id']); ?></span> </p>
            <p> Username : <span><?= htmlspecialchars($fetch_accounts['name']); ?></span> </p>
            <a href="users_accounts.php?delete=<?= htmlspecialchars($fetch_accounts['id']); ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this account?');">Delete</a>
        </div>
        <?php
            }
        } else {
            echo '<p class="empty">No accounts available</p>';
        }
        ?>
    </div>

</section>
<!-- User accounts section ends -->

<!-- Custom JS File -->
<script src="http://localhost/project-root/assets/js/admin_script.js"></script>

</body>
</html>
