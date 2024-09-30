<?php

include __DIR__ . '/../../../config/db.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit;
}

if (isset($_POST['update_payment'])) {

   $order_id = $_POST['order_id'];
   $payment_status = $_POST['payment_status'];

   // Update payment status
   $update_status = $conn->prepare("UPDATE `orders` SET payment_status = ? WHERE id = ?");
   $update_status->bind_param("si", $payment_status, $order_id);

   if ($update_status->execute()) {
      $message[] = 'Payment status updated!';
   } else {
      $message[] = 'Failed to update payment status!';
   }
}

if (isset($_GET['delete'])) {
   $delete_id = $_GET['delete'];

   // Delete the order
   $delete_order = $conn->prepare("DELETE FROM `orders` WHERE id = ?");
   $delete_order->bind_param("i", $delete_id);

   if ($delete_order->execute()) {
      header('location:placed_orders.php');
   } else {
      $message[] = 'Failed to delete order!';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Placed Orders</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/admin_style.css">

</head>
<body>

<?php include __DIR__ . '/../components/admin_header.php'; ?>

<!-- Placed Orders Section Starts -->

<section class="placed-orders">

   <h1 class="heading">Placed Orders</h1>

   <div class="box-container">

   <?php
      $select_orders = $conn->prepare("SELECT * FROM `orders`");
      $select_orders->execute();
      $result = $select_orders->get_result();

      if ($result->num_rows > 0) {
         while ($fetch_orders = $result->fetch_assoc()) {
   ?>
   <div class="box">
      <p> User ID : <span><?= $fetch_orders['user_id']; ?></span> </p>
      <p> Placed on : <span><?= $fetch_orders['placed_on']; ?></span> </p>
      <p> Name : <span><?= $fetch_orders['name']; ?></span> </p>
      <p> Email : <span><?= $fetch_orders['email']; ?></span> </p>
      <p> Number : <span><?= $fetch_orders['number']; ?></span> </p>
      <p> Address : <span><?= $fetch_orders['address']; ?></span> </p>
      <p> Total Products : <span><?= $fetch_orders['total_products']; ?></span> </p>
      <p> Total Price : <span>$<?= $fetch_orders['total_price']; ?>/-</span> </p>
      <p> Payment Method : <span><?= $fetch_orders['method']; ?></span> </p>
      <form action="" method="POST">
         <input type="hidden" name="order_id" value="<?= $fetch_orders['id']; ?>">
         <select name="payment_status" class="drop-down">
            <option value="" selected disabled><?= $fetch_orders['payment_status']; ?></option>
            <option value="pending">Pending</option>
            <option value="completed">Completed</option>
         </select>
         <div class="flex-btn">
            <input type="submit" value="Update" class="btn" name="update_payment">
            <a href="placed_orders.php?delete=<?= $fetch_orders['id']; ?>" class="delete-btn" onclick="return confirm('Delete this order?');">Delete</a>
         </div>
      </form>
   </div>
   <?php
         }
      } else {
         echo '<p class="empty">No orders placed yet!</p>';
      }
   ?>

   </div>

</section>

<!-- Placed Orders Section Ends -->

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/admin_script.js"></script>

</body>
</html>
