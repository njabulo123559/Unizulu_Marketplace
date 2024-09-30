<?php

// Include the database connection
include __DIR__ . '/../../../config/db.php'; // Adjust the path based on your folder structure

session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:home.php');
    exit(); // Always exit after a redirect
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Orders</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/style.css"> <!-- Adjust path for CSS -->

</head>
<body>
   
<!-- header section starts  -->
<?php include __DIR__ . '/../components/user_header.php'; ?> <!-- Adjust path for header -->
<!-- header section ends -->

<div class="heading">
   <h3>Your Orders</h3>
   <p><a href="home.php">Home</a> <span> / Orders</span></p> <!-- Corrected the home link -->
</div>

<section class="orders">

   <h1 class="title">Your Orders</h1>

   <div class="box-container">

   <?php
   // Check if the user is logged in
   if ($user_id == '') {
      echo '<p class="empty">Please login to see your orders.</p>';
   } else {
      // Prepare the MySQLi query
      if ($select_orders = $conn->prepare("SELECT * FROM `orders` WHERE user_id = ?")) {
         $select_orders->bind_param("i", $user_id); // Bind the user ID
         $select_orders->execute();
         $result = $select_orders->get_result();

         // Check if there are any orders
         if ($result->num_rows > 0) {
            while ($fetch_orders = $result->fetch_assoc()) {
   ?>
   <div class="box">
      <p>Placed on: <span><?= htmlspecialchars($fetch_orders['placed_on']); ?></span></p>
      <p>Name: <span><?= htmlspecialchars($fetch_orders['name']); ?></span></p>
      <p>Email: <span><?= htmlspecialchars($fetch_orders['email']); ?></span></p>
      <p>Number: <span><?= htmlspecialchars($fetch_orders['number']); ?></span></p>
      <p>Address: <span><?= htmlspecialchars($fetch_orders['address']); ?></span></p>
      <p>Payment Method: <span><?= htmlspecialchars($fetch_orders['method']); ?></span></p>
      <p>Your Orders: <span><?= htmlspecialchars($fetch_orders['total_products']); ?></span></p>
      <p>Total Price: <span>$<?= htmlspecialchars($fetch_orders['total_price']); ?>/-</span></p>
      <p>Payment Status: <span style="color:<?php if ($fetch_orders['payment_status'] == 'pending') { echo 'red'; } else { echo 'green'; }; ?>"><?= htmlspecialchars($fetch_orders['payment_status']); ?></span></p>
   </div>
   <?php
            }
         } else {
            echo '<p class="empty">No orders placed yet!</p>';
         }
         $select_orders->close();
      }
   }
   ?>

   </div>

</section>

<!-- footer section starts -->
<?php include __DIR__  . '/../components/footer.php'; ?> <!-- Adjust path for footer -->
<!-- footer section ends -->

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/script.js"></script> <!-- Adjust path for JS -->

</body>
</html>
