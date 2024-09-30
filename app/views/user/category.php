<?php

// Include the database connection
include __DIR__ . '/../../../config/db.php'; // Adjust path according to your folder structure

session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Include the add to cart component
include __DIR__ . '/../components/add_cart.php'; // Adjust path based on your structure

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Category</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="../assets/css/style.css"> <!-- Adjust path for CSS -->

</head>
<body>
   
<!-- header section starts  -->
<?php include __DIR__ . '/../components/user_header.php'; ?> <!-- Adjust path for header -->
<!-- header section ends -->

<section class="products">

   <h1 class="title">Food Category</h1>

   <div class="box-container">

      <?php
         // Sanitize category input from the URL
         if (isset($_GET['category'])) {
             $category = filter_var($_GET['category'], FILTER_SANITIZE_STRING);

             // Prepare the statement using MySQLi
             if ($select_products = $conn->prepare("SELECT * FROM `products` WHERE category = ?")) {
                 $select_products->bind_param("s", $category); // Bind the category parameter
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
         <img src="../uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="">
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
                 $select_products->close();
             }
         } else {
             echo '<p class="empty">Category not specified.</p>';
         }
      ?>

   </div>

</section>

<!-- footer section starts -->
<?php include __DIR__  . '/../components/footer.php'; ?> <!-- Adjust path for footer -->
<!-- footer section ends -->

<!-- Swiper.js CDN Link -->
<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- Custom JS File Link -->
<script src="../assets/js/script.js"></script> <!-- Adjust path for JS -->

</body>
</html>
