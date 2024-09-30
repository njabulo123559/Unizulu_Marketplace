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

// Handle form submission
if (isset($_POST['submit'])) {

   // Concatenate and sanitize the address fields
   $address = $_POST['flat'] . ', ' . $_POST['building'] . ', ' . $_POST['area'] . ', ' . $_POST['town'] . ', ' . $_POST['city'] . ', ' . $_POST['state'] . ', ' . $_POST['country'] . ' - ' . $_POST['pin_code'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);

   // Prepare and execute the update query
   if ($stmt = $conn->prepare("UPDATE `users` SET address = ? WHERE id = ?")) {
      $stmt->bind_param("si", $address, $user_id);
      if ($stmt->execute()) {
         $message[] = 'Address saved successfully!';
      } else {
         $message[] = 'Failed to update address!';
      }
      $stmt->close();
   } else {
      $message[] = 'Failed to prepare statement!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Address</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/style.css"> <!-- Adjust path for CSS -->

</head>
<body>

<?php include __DIR__ . '/../components/user_header.php'; ?> <!-- Adjust path for header -->

<section class="form-container">
   <form action="" method="post">
      <h3>Your Address</h3>
      
      <!-- Address Input Fields -->
      <input type="text" class="box" placeholder="Flat No." required maxlength="50" name="flat">
      <input type="text" class="box" placeholder="Building No." required maxlength="50" name="building">
      <input type="text" class="box" placeholder="Area Name" required maxlength="50" name="area">
      <input type="text" class="box" placeholder="Town Name" required maxlength="50" name="town">
      <input type="text" class="box" placeholder="City Name" required maxlength="50" name="city">
      <input type="text" class="box" placeholder="State Name" required maxlength="50" name="state">
      <input type="text" class="box" placeholder="Country Name" required maxlength="50" name="country">
      <input type="number" class="box" placeholder="Pin Code" required max="999999" min="0" maxlength="6" name="pin_code">
      
      <!-- Submit Button -->
      <input type="submit" value="Save Address" name="submit" class="btn">
   </form>

   <!-- Display messages if any -->
   <?php
   if (isset($message)) {
      foreach ($message as $msg) {
         echo '<p class="message">' . htmlspecialchars($msg) . '</p>';
      }
   }
   ?>

</section>

<!-- Footer Section -->
<?php include __DIR__  . '/../components/footer.php'; ?> <!-- Adjust path for footer -->

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/script.js"></script> <!-- Adjust path for JS -->

</body>
</html>
