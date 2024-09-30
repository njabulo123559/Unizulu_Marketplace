<?php

// Include the database connection
include __DIR__ . '/../../../config/db.php'; // Adjust path based on your folder structure

session_start();

// Check if the user is logged in, otherwise redirect to the home page
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   header('location:home.php');
   exit();
}

// Fetch user details from the database
if ($stmt = $conn->prepare("SELECT * FROM `users` WHERE id = ?")) {
   $stmt->bind_param("i", $user_id);
   $stmt->execute();
   $result = $stmt->get_result();
   
   if ($result->num_rows > 0) {
      $fetch_profile = $result->fetch_assoc();
   } else {
      $message[] = "User not found.";
   }
   $stmt->close();
} else {
   $message[] = "Failed to fetch user profile.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Profile</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/style.css"> <!-- Adjust path for CSS -->

</head>
<body>
   
<!-- header section starts  -->
<?php include __DIR__ . '/../components/user_header.php'; ?> <!-- Adjust path for header -->
<!-- header section ends -->

<section class="user-details">

   <div class="user">
      <img src="http://localhost/project-root/assets/images/user-icon.png" alt="User Icon"> <!-- Adjust path for image -->
      <p><i class="fas fa-user"></i><span><?= htmlspecialchars($fetch_profile['name']); ?></span></p>
      <p><i class="fas fa-phone"></i><span><?= htmlspecialchars($fetch_profile['number']); ?></span></p>
      <p><i class="fas fa-envelope"></i><span><?= htmlspecialchars($fetch_profile['email']); ?></span></p>
      <a href="/project-root/app/views/user/update_profile.php" class="btn">Update Info</a>
      <p class="address"><i class="fas fa-map-marker-alt"></i><span><?php if (empty($fetch_profile['address'])) { echo 'Please enter your address'; } else { echo htmlspecialchars($fetch_profile['address']); } ?></span></p>
      <a href="/project-root/app/views/user/update_address.php" class="btn">Update Address</a>
   </div>

</section>

<!-- footer section starts -->
<?php include __DIR__  . '/../components/footer.php'; ?> <!-- Adjust path for footer -->
<!-- footer section ends -->

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/script.js"></script> <!-- Adjust path for JS -->

</body>
</html>
