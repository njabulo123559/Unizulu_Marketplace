<?php

// Include the database connection
include __DIR__ . '/../../../config/db.php'; // Adjust the path to your config folder

session_start();

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:home.php');
   exit;
}

// Delete a specific item from the cart
if (isset($_POST['delete'])) {
   $cart_id = $_POST['cart_id'];
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
   $delete_cart_item->bind_param("i", $cart_id); // Bind the integer parameter
   $delete_cart_item->execute();
   $message[] = 'Cart item deleted!';
}

// Delete all items from the cart
if (isset($_POST['delete_all'])) {
   $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
   $delete_cart_item->bind_param("i", $user_id); // Bind the integer parameter
   $delete_cart_item->execute();
   $message[] = 'Deleted all items from cart!';
}

// Update the quantity of an item in the cart
if (isset($_POST['update_qty'])) {
   $cart_id = $_POST['cart_id'];
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);
   $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
   $update_qty->bind_param("ii", $qty, $cart_id); // Bind the two integer parameters (quantity and cart ID)
   $update_qty->execute();
   $message[] = 'Cart quantity updated!';
}

// Initialize grand total
$grand_total = 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Shopping Cart</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/style.css"> <!-- Adjust path to your assets folder -->

</head>
<body>

<!-- header section starts -->
<?php include __DIR__ . '/../components/user_header.php'; ?> <!-- Adjust path to your components folder -->
<!-- header section ends -->

<div class="heading">
   <h3>Shopping Cart</h3>
   <p><a href="/project-root/app/views/user/home.php">Home</a> <span> / Cart</span></p>
</div>

<!-- shopping cart section starts -->
<section class="products">

   <h1 class="title">Your Cart</h1>

   <div class="box-container">

      <?php
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->bind_param("i", $user_id); // Bind the integer parameter for user ID
         $select_cart->execute();
         $result = $select_cart->get_result(); // Get the result after execution
         if ($result->num_rows > 0) {
            while ($fetch_cart = $result->fetch_assoc()) {
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
         <a href="/project-root/app/views/user/quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-times" name="delete" onclick="return confirm('Delete this item?');"></button>
         <img src="http://localhost/project-root/uploaded_img/<?= $fetch_cart['image']; ?>" alt=""> <!-- Adjust path to your images folder -->
         <div class="name"><?= $fetch_cart['name']; ?></div>
         <div class="flex">
            <div class="price"><span>$</span><?= $fetch_cart['price']; ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="<?= $fetch_cart['quantity']; ?>" maxlength="2">
            <button type="submit" class="fas fa-edit" name="update_qty"></button>
         </div>
         <div class="sub-total"> Sub total : <span>$<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
      </form>
      <?php
               $grand_total += $sub_total;
            }
         } else {
            echo '<p class="empty">Your cart is empty</p>';
         }
      ?>

   </div>

   <div class="cart-total">
      <p>Cart total : <span>$<?= $grand_total; ?></span></p>
      <a href="/project-root/app/views/user/checkout.php" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>">Proceed to Checkout</a>
   </div>

   <div class="more-btn">
      <form action="" method="post">
         <button type="submit" class="delete-btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" name="delete_all" onclick="return confirm('Delete all items from cart?');">Delete All</button>
      </form>
      <a href="/project-root/app/views/user/menu.php" class="btn">Continue Shopping</a>
   </div>

</section>
<!-- shopping cart section ends -->

<!-- footer section starts -->
<?php include __DIR__  . '/../components/footer.php'; ?> <!-- Adjust path to your components folder -->
<!-- footer section ends -->

<!-- Custom JS -->
<script src="http://localhost/project-root/assets/js/script.js"></script> <!-- Adjust path to your JS folder -->

</body>
</html>
