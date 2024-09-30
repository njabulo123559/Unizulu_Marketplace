<?php

include __DIR__ . '/../../../config/db.php';

session_start();

// Ensure the admin is logged in
$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    header('location:admin_login.php');
    exit;
}

// Fetch current profile data for display
$fetch_profile = null;
$select_profile = $conn->prepare("SELECT * FROM `admin` WHERE id = ?");
$select_profile->bind_param("i", $admin_id);
$select_profile->execute();
$result = $select_profile->get_result();
if ($result->num_rows > 0) {
    $fetch_profile = $result->fetch_assoc();
} else {
    header('location:admin_login.php');
    exit;
}

if (isset($_POST['submit'])) {
    // Updating the admin name
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    
    if (!empty($name)) {
        $select_name = $conn->prepare("SELECT * FROM `admin` WHERE name = ? AND id != ?");
        $select_name->bind_param("si", $name, $admin_id);
        $select_name->execute();
        $result = $select_name->get_result();
        
        if ($result->num_rows > 0) {
            $message[] = 'Username already taken!';
        } else {
            $update_name = $conn->prepare("UPDATE `admin` SET name = ? WHERE id = ?");
            $update_name->bind_param("si", $name, $admin_id);
            if ($update_name->execute()) {
                $message[] = 'Username updated successfully!';
            } else {
                $message[] = 'Failed to update username.';
            }
        }
    }

    // Password update process
    $old_pass = sha1($_POST['old_pass'] ?? '');
    $new_pass = sha1($_POST['new_pass'] ?? '');
    $confirm_pass = sha1($_POST['confirm_pass'] ?? '');

    // Check if the old password is entered
    if (!empty($_POST['old_pass'])) {
        // Fetch the current password from the database
        $select_old_pass = $conn->prepare("SELECT password FROM `admin` WHERE id = ?");
        $select_old_pass->bind_param("i", $admin_id);
        $select_old_pass->execute();
        $result = $select_old_pass->get_result();
        $fetch_prev_pass = $result->fetch_assoc();
        $prev_pass = $fetch_prev_pass['password'];

        if ($old_pass !== $prev_pass) {
            $message[] = 'Old password does not match!';
        } elseif ($new_pass !== $confirm_pass) {
            $message[] = 'New password and confirm password do not match!';
        } else {
            if (!empty($_POST['new_pass'])) {
                // Update password in the database
                $update_pass = $conn->prepare("UPDATE `admin` SET password = ? WHERE id = ?");
                $update_pass->bind_param("si", $confirm_pass, $admin_id);
                if ($update_pass->execute()) {
                    $message[] = 'Password updated successfully!';
                } else {
                    $message[] = 'Failed to update password.';
                }
            } else {
                $message[] = 'Please enter a new password!';
            }
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
    <title>Profile Update</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS File Link -->
    <link rel="stylesheet" href="http://localhost/project-root/assets/css/admin_style.css">
</head>
<body>

<?php include __DIR__ . '/../components/admin_header.php'; ?>

<!-- Admin Profile Update Section Starts -->
<section class="form-container">
    <form action="" method="POST">
        <h3>Update Profile</h3>
        <input type="text" name="name" maxlength="20" class="box" value="<?= htmlspecialchars($fetch_profile['name']); ?>" placeholder="Enter your username">
        <input type="password" name="old_pass" maxlength="20" placeholder="Enter your old password" class="box">
        <input type="password" name="new_pass" maxlength="20" placeholder="Enter your new password" class="box">
        <input type="password" name="confirm_pass" maxlength="20" placeholder="Confirm your new password" class="box">
        <input type="submit" value="Update Now" name="submit" class="btn">
    </form>
</section>
<!-- Admin Profile Update Section Ends -->

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/admin_script.js"></script>

</body>
</html>
