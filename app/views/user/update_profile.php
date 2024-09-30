<?php

// Include the database connection
include __DIR__ . '/../../../config/db.php'; // Adjust path to your config folder

session_start();

// Check if user is logged in, otherwise redirect to home page
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   header('location:home.php');
   exit();
}

// Fetch the user's current profile information
$select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
$select_profile->bind_param("i", $user_id);
$select_profile->execute();
$fetch_profile = $select_profile->get_result()->fetch_assoc();

if (isset($_POST['submit'])) {

   // Sanitize user inputs
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);

   // Update name
   if (!empty($name)) {
      $update_name = $conn->prepare("UPDATE `users` SET name = ? WHERE id = ?");
      $update_name->bind_param("si", $name, $user_id);
      $update_name->execute();
   }

   // Update email
   if (!empty($email)) {
      $select_email = $conn->prepare("SELECT * FROM `users` WHERE email = ?");
      $select_email->bind_param("s", $email);
      $select_email->execute();
      if ($select_email->get_result()->num_rows > 0) {
         $message[] = 'Email already taken!';
      } else {
         $update_email = $conn->prepare("UPDATE `users` SET email = ? WHERE id = ?");
         $update_email->bind_param("si", $email, $user_id);
         $update_email->execute();
      }
   }

   // Update phone number
   if (!empty($number)) {
      $select_number = $conn->prepare("SELECT * FROM `users` WHERE number = ?");
      $select_number->bind_param("s", $number);
      $select_number->execute();
      if ($select_number->get_result()->num_rows > 0) {
         $message[] = 'Phone number already taken!';
      } else {
         $update_number = $conn->prepare("UPDATE `users` SET number = ? WHERE id = ?");
         $update_number->bind_param("si", $number, $user_id);
         $update_number->execute();
      }
   }

   // Handle password change
   $empty_pass = sha1('');
   $old_pass = sha1($_POST['old_pass']);
   $new_pass = sha1($_POST['new_pass']);
   $confirm_pass = sha1($_POST['confirm_pass']);

   // Fetch the current password from the database
   $select_prev_pass = $conn->prepare("SELECT password FROM `users` WHERE id = ?");
   $select_prev_pass->bind_param("i", $user_id);
   $select_prev_pass->execute();
   $fetch_prev_pass = $select_prev_pass->get_result()->fetch_assoc();
   $prev_pass = $fetch_prev_pass['password'];

   // Check if old password matches
   if (!empty($_POST['old_pass'])) {
      if ($old_pass != $prev_pass) {
         $message[] = 'Old password does not match!';
      } elseif ($new_pass != $confirm_pass) {
         $message[] = 'New password and confirmation do not match!';
      } else {
         if ($new_pass != $empty_pass) {
            $update_pass = $conn->prepare("UPDATE `users` SET password = ? WHERE id = ?");
            $update_pass->bind_param("si", $confirm_pass, $user_id);
            $update_pass->execute();
            $message[] = 'Password updated successfully!';
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
   <title>Update Profile</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/style.css"> <!-- Adjust path for CSS -->

</head>
<body>

<!-- Header Section -->
<?php include __DIR__ . '/../components/user_header.php'; ?> <!-- Adjust path for header -->

<!-- Profile Update Form -->
<section class="form-container update-form">
   <form action="" method="post">
      <h3>Update Profile</h3>
      
      <!-- Name, Email, and Phone Number -->
      <input type="text" name="name" placeholder="<?= htmlspecialchars($fetch_profile['name']); ?>" class="box" maxlength="50">
      <input type="email" name="email" placeholder="<?= htmlspecialchars($fetch_profile['email']); ?>" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" placeholder="<?= htmlspecialchars($fetch_profile['number']); ?>" class="box" min="0" max="9999999999" maxlength="10">

      <!-- Password Update Section -->
      <input type="password" name="old_pass" placeholder="Enter your old password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="new_pass" placeholder="Enter your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="confirm_pass" placeholder="Confirm your new password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      
      <!-- Submit Button -->
      <input type="submit" value="Update Now" name="submit" class="btn">

      <!-- Display messages if any -->
      <?php
      if (isset($message)) {
         foreach ($message as $msg) {
            echo '<p class="message">' . htmlspecialchars($msg) . '</p>';
         }
      }
      ?>
   </form>
</section>

<!-- Footer Section -->
<?php include __DIR__  . '/../components/footer.php'; ?> <!-- Adjust path for footer -->

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/script.js"></script> <!-- Adjust path for JS -->

</body>
</html>
