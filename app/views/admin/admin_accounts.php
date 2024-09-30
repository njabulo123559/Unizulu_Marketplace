<?php

include __DIR__ . '/../../../config/db.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

// Deleting admin account logic
if (isset($_GET['delete'])) {
    $delete_id = $_GET['delete'];
    
    // Prevent deleting the logged-in admin account
    if ($delete_id == $admin_id) {
        $message[] = "You cannot delete your own account!";
    } else {
        $delete_admin = $conn->prepare("DELETE FROM `admin` WHERE id = ?");
        $delete_admin->bind_param("i", $delete_id);

        if ($delete_admin->execute()) {
            $message[] = "Admin account deleted!";
            header('location:admin_accounts.php');
        } else {
            $message[] = "Failed to delete account!";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Admin Accounts</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/admin_style.css">

</head>
<body>

<?php include __DIR__ . '/../components/admin_header.php'; ?>

<!-- Admins Accounts Section Starts -->

<section class="accounts">

   <h1 class="heading">Admins Account</h1>

   <div class="box-container">

   <!-- Option to register a new admin -->
   <div class="box">
      <p>Register new admin</p>
      <a href="register_admin.php" class="option-btn">Register</a>
   </div>

   <?php
      // Fetch all admin accounts
      $select_account = $conn->prepare("SELECT * FROM `admin`");
      $select_account->execute();
      $result = $select_account->get_result();
      
      if ($result->num_rows > 0) {
         while ($fetch_accounts = $result->fetch_assoc()) {
   ?>
   <div class="box">
      <p>Admin ID : <span><?= $fetch_accounts['id']; ?></span></p>
      <p>Username : <span><?= $fetch_accounts['name']; ?></span></p>

      <div class="flex-btn">
         <!-- Delete admin account -->
         <a href="admin_accounts.php?delete=<?= $fetch_accounts['id']; ?>" class="delete-btn" onclick="return confirm('Delete this account?');">Delete</a>

         <?php
            // Only allow the logged-in admin to update their own account
            if ($fetch_accounts['id'] == $admin_id) {
               echo '<a href="update_profile.php" class="option-btn">Update</a>';
            }
         ?>
      </div>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">No accounts available</p>';
      }
   ?>

   </div>

</section>

<!-- Admins Accounts Section Ends -->

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/admin_script.js"></script>

</body>
</html>
