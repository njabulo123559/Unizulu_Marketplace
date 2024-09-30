<?php

include __DIR__ . '/../../../config/db.php'; // Adjust path to your config file

session_start();

// Check if user is logged in
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:home.php');
   exit;
}

// Retrieve user information
$fetch_profile = null;
if ($user_id != '') {
   $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
   $select_profile->bind_param("i", $user_id);
   $select_profile->execute();
   $result = $select_profile->get_result();
   
   if ($result->num_rows > 0) {
      $fetch_profile = $result->fetch_assoc();
      $name = $fetch_profile['name'];
      $number = $fetch_profile['number'];
      $email = $fetch_profile['email'];
      $address = $fetch_profile['address'];
   } else {
      $name = '';
      $number = '';
      $email = '';
      $address = '';
   }
} else {
   $name = '';
   $number = '';
   $email = '';
   $address = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>

   <!-- Font Awesome CDN -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/style.css"> <!-- Adjust path to your assets folder -->

   <!-- Stripe.js -->
   <script src="https://js.stripe.com/v3/"></script>

</head>
<body>
   
<!-- header section starts -->
<?php include __DIR__ . '/../components/user_header.php'; ?>
<!-- header section ends -->

<div class="heading">
   <h3>Checkout</h3>
   <p><a href="/project-root/app/views/user/home.php">Home</a> <span> / Checkout</span></p>
</div>

<section class="checkout">

   <h1 class="title">Order Summary</h1>

   <form action="process_payment.php" method="post" id="payment-form">

      <div class="cart-items">
         <h3>Cart Items</h3>
         <?php
            $grand_total = 0;
            $cart_items = [];
            $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
            $select_cart->bind_param("i", $user_id);
            $select_cart->execute();
            $result = $select_cart->get_result();
            if ($result->num_rows > 0) {
               while ($fetch_cart = $result->fetch_assoc()) {
                  $cart_items[] = $fetch_cart['name'].' ('.$fetch_cart['price'].' x '. $fetch_cart['quantity'].')';
                  $total_products = implode(', ', $cart_items);
                  $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
         ?>
         <p><span class="name"><?= $fetch_cart['name']; ?></span><span class="price">$<?= $fetch_cart['price']; ?> x <?= $fetch_cart['quantity']; ?></span></p>
         <?php
               }
            } else {
               echo '<p class="empty">Your cart is empty!</p>';
            }
         ?>
         <p class="grand-total"><span class="name">Grand total :</span><span class="price">$<?= $grand_total; ?></span></p>
         <a href="/project-root/app/views/user/cart.php" class="btn">View Cart</a>
      </div>

      <input type="hidden" name="total_products" value="<?= $total_products; ?>">
      <input type="hidden" name="total_price" value="<?= $grand_total; ?>">

      <div class="user-info">
         <h3>Your Info</h3>
         <p><i class="fas fa-user"></i><span><?= $name ?></span></p>
         <p><i class="fas fa-phone"></i><span><?= $number ?></span></p>
         <p><i class="fas fa-envelope"></i><span><?= $email ?></span></p>
         <a href="/project-root/app/views/user/update_profile.php" class="btn">Update Info</a>

         <h3>Delivery Address</h3>
         <p><i class="fas fa-map-marker-alt"></i><span><?php if (empty($address)) { echo 'Please enter your address'; } else { echo $address; } ?></span></p>
         <a href="/project-root/app/views/user/update_address.php" class="btn">Update Address</a>

         <select name="method" class="box" required>
            <option value="" disabled selected>Select payment method --</option>
            <option value="cash on delivery">Cash on Delivery</option>
            <option value="credit card">Credit Card</option>
            <option value="paytm">Paytm</option>
            <option value="paypal">PayPal</option>
         </select>

         <!-- Stripe Elements for Credit Card -->
         <div class="form-row">
            <label for="card-element">Credit or Debit Card</label>
            <div id="card-element">
                <!-- Stripe Elements will be inserted here -->
            </div>
            <div id="card-errors" role="alert"></div>
         </div>

         <input type="submit" value="Place Order" class="btn" name="submit">
      </div>

   </form>

</section>

<!-- footer section starts -->
<?php include __DIR__  . '/../components/footer.php'; ?>
<!-- footer section ends -->

<!-- Custom JS -->
<script src="http://localhost/project-root/assets/js/script.js"></script>

<script>
   // Initialize Stripe
   var stripe = Stripe('your-stripe-public-key'); // Replace with your Stripe public key
   var elements = stripe.elements();
   var card = elements.create('card');
   card.mount('#card-element');

   // Handle real-time validation errors from the card Element.
   card.on('change', function(event) {
       var displayError = document.getElementById('card-errors');
       if (event.error) {
           displayError.textContent = event.error.message;
       } else {
           displayError.textContent = '';
       }
   });

   // Handle form submission
   var form = document.getElementById('payment-form');
   form.addEventListener('submit', function(event) {
       event.preventDefault();

       stripe.createToken(card).then(function(result) {
           if (result.error) {
               var errorElement = document.getElementById('card-errors');
               errorElement.textContent = result.error.message;
           } else {
               stripeTokenHandler(result.token);
           }
       });
   });

   // Submit the form with the Stripe token
   function stripeTokenHandler(token) {
       var form = document.getElementById('payment-form');
       var hiddenInput = document.createElement('input');
       hiddenInput.setAttribute('type', 'hidden');
       hiddenInput.setAttribute('name', 'stripeToken');
       hiddenInput.setAttribute('value', token.id);
       form.appendChild(hiddenInput);

       form.submit();
   }
</script>

</body>
</html>
