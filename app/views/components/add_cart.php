<?php

// Check if the form for adding to the cart was submitted
if (isset($_POST['add_to_cart'])) {

   if ($user_id == '') {
      header('location:login.php');
      exit;
   } else {
      // Get and sanitize form inputs
      $pid = $_POST['pid'];
      $pid = filter_var($pid, FILTER_SANITIZE_STRING);
      $name = $_POST['name'];
      $name = filter_var($name, FILTER_SANITIZE_STRING);
      $price = $_POST['price'];
      $price = filter_var($price, FILTER_SANITIZE_STRING);
      $image = $_POST['image'];
      $image = filter_var($image, FILTER_SANITIZE_STRING);
      $qty = $_POST['qty'];
      $qty = filter_var($qty, FILTER_SANITIZE_STRING);

      // Check if the product is already in the user's cart
      $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE name = ? AND user_id = ?");
      $check_cart_numbers->bind_param("si", $name, $user_id); // Bind parameters (string, integer)
      $check_cart_numbers->execute();
      $result = $check_cart_numbers->get_result();

      if ($result->num_rows > 0) {
         $message[] = 'Already added to cart!';
      } else {
         // Insert the product into the cart
         $insert_cart = $conn->prepare("INSERT INTO `cart` (user_id, pid, name, price, quantity, image) VALUES (?, ?, ?, ?, ?, ?)");
         $insert_cart->bind_param("iissis", $user_id, $pid, $name, $price, $qty, $image); // Bind parameters (int, int, string, string, int, string)
         $insert_cart->execute();
         $message[] = 'Added to cart!';
      }
   }
}

?>
