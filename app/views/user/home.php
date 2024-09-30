<?php
// Ensure the correct database connection
include __DIR__ . '/../../../config/db.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Include any necessary components, such as adding products to the cart
include __DIR__ . '/../components/add_cart.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">           
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Home</title>

   <!-- External CSS (from assets) -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/style.css">

   <!-- Font Awesome and Swiper.js -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />
</head>
<body>

<!-- Include the user header component -->
<?php include __DIR__ . '/../components/user_header.php'; ?>

<section class="hero">
   <div class="swiper hero-slider">
      <div class="swiper-wrapper">
         <div class="swiper-slide slide">
            <div class="content">
               <span>Order Online</span>
               <h3>Delicious Pizza</h3>
               <a href="/project-root/app/views/user/menu.php" class="btn">See Menus</a>
            </div>
            <div class="image">
               <img src="http://localhost/project-root/assets/images/home-img-1.png" alt="">
            </div>
         </div>
         <div class="swiper-slide slide">
            <div class="content">
               <span>Order Online</span>
               <h3>Cheesy Hamburger</h3>
               <a href="/project-root/app/views/user/menu.php" class="btn">See Menus</a>
            </div>
            <div class="image">
               <img src="http://localhost/project-root/assets/images/home-img-2.png" alt="">
            </div>
         </div>
         <div class="swiper-slide slide">
            <div class="content">
               <span>Order Online</span>
               <h3>Roasted Chicken</h3>
               <a href="/project-root/app/views/user/menu.php" class="btn">See Menus</a>
            </div>
            <div class="image">
               <img src="http://localhost/project-root/assets/images/home-img-3.png" alt="">
            </div>
         </div>
      </div>
      <div class="swiper-pagination"></div>
   </div>
</section>

<section class="category">
   <h1 class="title">Food Categories</h1>
   <div class="box-container">
      <a href="category.php?category=fast food" class="box">
         <img src="http://localhost/project-root/assets/images/cat-1.png" alt="">
         <h3>Fast Food</h3>
      </a>
      <a href="category.php?category=main dish" class="box">
         <img src="http://localhost/project-root/assets/images/cat-2.png" alt="">
         <h3>Main Dishes</h3>
      </a>
      <a href="category.php?category=drinks" class="box">
         <img src="http://localhost/project-root/assets/images/cat-3.png" alt="">
         <h3>Drinks</h3>
      </a>
      <a href="category.php?category=desserts" class="box">
         <img src="http://localhost/project-root/assets/images/cat-4.png" alt="">
         <h3>Desserts</h3>
      </a>
   </div>
</section>

<section class="products">
   <h1 class="title">Latest Dishes</h1>

   <div class="box-container">
      <?php
         $select_products = $conn->prepare("SELECT * FROM `products` LIMIT 6");
         $select_products->execute();
         if ($select_products->num_rows() > 0) {
            while ($fetch_products = $select_products->fetch(PDO::FETCH_ASSOC)) {
               if ($fetch_products) {  // Check if the product data is valid
      ?>
      <form action="" method="post" class="box">
         <input type="hidden" name="pid" value="<?= htmlspecialchars($fetch_products['id']); ?>">
         <input type="hidden" name="name" value="<?= htmlspecialchars($fetch_products['name']); ?>">
         <input type="hidden" name="price" value="<?= htmlspecialchars($fetch_products['price']); ?>">
         <input type="hidden" name="image" value="<?= htmlspecialchars($fetch_products['image']); ?>">
         <a href="quick_view.php?pid=<?= htmlspecialchars($fetch_products['id']); ?>" class="fas,      \
         
         
            n  fa-eye"></a>
         <button type="submit" class="fas fa-shopping-cart" name="add_to_cart"></button>
         <img src="/assets/uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="">
         <a href="category.php?category=<?= htmlspecialchars($fetch_products['category']); ?>" class="cat"><?= htmlspecialchars($fetch_products['category']); ?></a>
         <div class="name"><?= htmlspecialchars($fetch_products['name']); ?></div>
         <div class="flex">
            <div class="price"><span>$</span><?= htmlspecialchars($fetch_products['price']); ?></div>
            <input type="number" name="qty" class="qty" min="1" max="99" value="1" maxlength="2">
         </div>
      </form>
      <?php
               }  // End of product data check
            }
         } else {
            echo '<p class="empty">No products added yet!</p>';
         }
      ?>
   </div>

   <div class="more-btn">
      <a href="menu.php" class="btn">View All</a>
   </div>

</section>

<?php include __DIR__  . '/../components/footer.php'; ?>

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>
<script src="http://localhost/project-root/assets/js/script.js"></script>

<script>
var swiper = new Swiper(".hero-slider", {
   loop: true,
   grabCursor: true,
   effect: "flip",
   pagination: {
      el: ".swiper-pagination",
      clickable: true,
   },
});
</script>

</body>
</html>
