<?php

// Include the database connection
include __DIR__ . '/../../../config/db.php';// Adjust the path based on your structure

session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Include the add to cart component
include __DIR__ . '/../components/add_cart.php'; // Adjust the path

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Menu</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/style.css">

 <!-- Adjust the path for your CSS -->

</head>
<body>
   
<!-- header section starts  -->
<?php include __DIR__ . '/../components/user_header.php'; ?> <!-- Adjust the path for your header -->
<!-- header section ends -->

<div class="heading">
   <h3>Our Menu</h3>
   <p><a href="home.php">Home</a> <span> / Menu</span></p>
</div>

<!-- Menu Section Starts -->
<section class="products">

   <h1 class="title">Latest Dishes</h1>

   <div class="box-container">

      <?php
         // Prepare the statement for MySQLi
         $select_products = $conn->prepare("SELECT * FROM `products`");
         $select_products->execute();
         $result = $select_products->get_result();

         if ($result->num_rows > 0) {
            while ($fetch_products = $result->fetch_assoc()) {
      ?>   
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>">
         <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_products['name']); ?>">
         <input type="hidden" name="price" value="<?= htmlspecialchars($fetch_products['price']); ?>">
         <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_products['image']); ?>">
         <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <img src="http://localhost/project-root/uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="">
         <a href="category.php?category=<?= htmlspecialchars($fetch_products['category']); ?>" class="cat"><?= htmlspecialchars($fetch_products['category']); ?></a>
         <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
         <div class="flex">
            <div class="price"><span>$</span><?= htmlspecialchars($fetch_products['price']); ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
      <?php
            }
         } else {
            echo '<p class="empty">No products added yet!</p>';
         }
      ?>

   </div>

</section>
<!-- Menu Section Ends -->

<!-- footer section starts -->
<?php include __DIR__  . '/../components/footer.php'; ?> <!-- Adjust the path for your footer -->
<!-- footer section ends -->

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/script.js"></script> <!-- Adjust the path for JS -->

</body>
</html>
