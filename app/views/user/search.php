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
include __DIR__ . '/../components/add_cart.php'; // Adjust path to components

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search Page</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/style.css"> <!-- Adjust path for CSS -->

</head>
<body>
   
<!-- header section starts -->
<?php include __DIR__ . '/../components/user_header.php'; ?> <!-- Adjust path for header -->
<!-- header section ends -->

<!-- search form section starts -->
<section class="search-form">
   <form method="post" action="">
      <input type="text" name="search_box" placeholder="Search here..." class="box" required>
      <button type="submit" name="search_btn" class="fas fa-search"></button>
   </form>
</section>
<!-- search form section ends -->

<!-- products section starts -->
<section class="products" style="min-height: 100vh; padding-top:0;">

   <div class="box-container">

      <?php
      // Handle the search query
      if (isset($_POST['search_box']) && !empty($_POST['search_box'])) {
         $search_box = filter_var($_POST['search_box'], FILTER_SANITIZE_STRING);

         // Prepare and execute the query
         if ($stmt = $conn->prepare("SELECT * FROM `products` WHERE name LIKE ?")) {
            $search_term = "%$search_box%";
            $stmt->bind_param("s", $search_term);
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
         <a href="/project-root/app/views/user/quick_view.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>" class="fas fa-eye"></a>
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <img src="http://localhost/project-root/uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="<?= htmlspecialchars($fetch_products['name']); ?>"> <!-- Adjust path for images -->
         <a href="/project-root/app/views/user/category.php?category=<?= htmlspecialchars($fetch_products['category']); ?>" class="cat"><?= htmlspecialchars($fetch_products['category']); ?></a>
         <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
         <div class="flex">
            <div class="price"><span>$</span><?= htmlspecialchars($fetch_products['price']); ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
      <?php
               }
            } else {
               echo '<p class="empty">No products found!</p>';
            }
         } else {
            echo '<p class="empty">Search query failed!</p>';
         }
      } else {
         echo '<p class="empty">Please enter a search term.</p>';
      }
      ?>

   </div>

</section>
<!-- products section ends -->

<!-- footer section starts -->
<?php include __DIR__  . '/../components/footer.php'; ?> <!-- Adjust path for footer -->
<!-- footer section ends -->

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/script.js"></script> <!-- Adjust path for JS -->

</body>
</html>
