<?php

// Include the database connection
include __DIR__ . '/../../../config/db.php'; // Adjust path based on your folder structure

session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

// Include add to cart functionality
include __DIR__ . '/../components/add_cart.php';// Adjust path to components

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Quick View</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">


   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/style.css"> <!-- Adjust the path for CSS -->

</head>
<body>
   
<?php include __DIR__ . '/../components/user_header.php'; ?> <!-- Adjust path for header -->

<section class="quick-view">

   <h1 class="title">Quick View</h1>

   <?php
      // Sanitize and get product ID from URL
      if (isset($_GET['pid']) && is_numeric($_GET['pid'])) {
         $pid = filter_var($_GET['pid'], FILTER_SANITIZE_NUMBER_INT);

         // Prepare and execute the query to fetch product details
         if ($stmt = $conn->prepare("SELECT * FROM `products` WHERE id = ?")) {
            $stmt->bind_param("i", $pid);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
               while ($fetch_products = $result->fetch_assoc()) {
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>">
      <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_products['name']); ?>">
      <input type="hidden" name="price" value="<?= htmlspecialchars($fetch_products['price']); ?>">
      <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_products['image']); ?>">
      <img src="http://localhost/project-root/uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="<?= htmlspecialchars($fetch_products['name']); ?>"> <!-- Adjust image path -->
      <a href="/project-root/app/views/user/category.php?category=<?= htmlspecialchars($fetch_products['category']); ?>" class="cat"><?= htmlspecialchars($fetch_products['category']); ?></a>
      <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
      <div class="flex">
         <div class="price"><span>$</span><?= htmlspecialchars($fetch_products['price']); ?></div>
         <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
      </div>
      <button type="submit" name="add_to_cart" class="cart-btn">Add to Cart</button>
   </form>
   <?php
               }
            } else {
               echo '<p class="empty">No products found!</p>';
            }
         } else {
            echo '<p class="empty">Failed to fetch product details.</p>';
         }
      } else {
         echo '<p class="empty">Invalid product ID.</p>';
      }
   ?>

</section>

<!-- footer section starts -->
<?php include __DIR__  . '/../components/footer.php'; ?> <!-- Adjust path for footer -->
<!-- footer section ends -->

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/script.js"></script> <!-- Adjust path for JS -->

</body>
</html>


