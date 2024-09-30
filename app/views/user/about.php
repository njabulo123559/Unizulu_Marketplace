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

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>About Us</title>

   <!-- Swiper.js CDN Link -->
   <link rel="stylesheet" href="https://unpkg.com/swiper@8/swiper-bundle.min.css" />

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/style.css"> <!-- Adjust the path for CSS -->

</head>
<body>
   
<!-- header section starts  -->
<?php include __DIR__ . '/../components/user_header.php'; ?> <!-- Adjust path for header -->
<!-- header section ends -->

<div class="heading">
   <h3>About Us</h3>
   <p><a href="/project-root/app/views/user/home.php">Home</a> <span> / About</span></p>
</div>

<!-- about section starts  -->
<section class="about">

   <div class="row">

      <div class="image">
         <img src="http://localhost/project-root/assets/images/about-img.svg" alt="About Us"> <!-- Adjust path for the image -->
      </div>

      <div class="content">
         <h3>Why Choose Us?</h3>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Deserunt, neque debitis incidunt qui ipsum sed doloremque a molestiae in veritatis ullam similique sunt aliquam dolores dolore? Quasi atque debitis nobis!</p>
         <a href="/project-root/app/views/user/menu.php" class="btn">Our Menu</a>
      </div>

   </div>

</section>
<!-- about section ends -->

<!-- steps section starts  -->
<section class="steps">

   <h1 class="title">Simple Steps</h1>

   <div class="box-container">

      <div class="box">
         <img src="http://localhost/project-root/assets/images/step-1.png" alt="Step 1"> <!-- Adjust path for the image -->
         <h3>Choose Order</h3>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt, dolorem.</p>
      </div>

      <div class="box">
         <img src="http://localhost/project-root/assets/images/step-2.png" alt="Step 2"> <!-- Adjust path for the image -->
         <h3>Fast Delivery</h3>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt, dolorem.</p>
      </div>

      <div class="box">
         <img src="http://localhost/project-root/assets/images/step-3.png" alt="Step 3"> <!-- Adjust path for the image -->
         <h3>Enjoy Food</h3>
         <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Nesciunt, dolorem.</p>
      </div>

   </div>

</section>
<!-- steps section ends -->

<!-- reviews section starts  -->
<section class="reviews">

   <h1 class="title">Customer Reviews</h1>

   <div class="swiper reviews-slider">

      <div class="swiper-wrapper">

         <!-- Review 1 -->
         <div class="swiper-slide slide">
            <img src="http://localhost/project-root/assets/images/pic-1.png" alt="John Deo"> <!-- Adjust path for the image -->
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quos voluptate eligendi laborum molestias ut earum nulla sint voluptatum labore nemo.</p>
            <div class="stars">
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star"></i>
               <i class="fas fa-star-half-alt"></i>
            </div>
            <h3>John Deo</h3>
         </div>

         <!-- Additional reviews omitted for brevity -->
         <!-- Add more reviews similarly -->

      </div>

      <div class="swiper-pagination"></div>

   </div>

</section>
<!-- reviews section ends -->

<!-- footer section starts -->
<?php include __DIR__  . '/../components/footer.php'; ?> <!-- Adjust path for footer -->
<!-- footer section ends -->

<script src="https://unpkg.com/swiper@8/swiper-bundle.min.js"></script>

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/script.js"></script> <!-- Adjust path for JS -->

<script>
var swiper = new Swiper(".reviews-slider", {
   loop: true,
   grabCursor: true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable: true,
   },
   breakpoints: {
      0: {
         slidesPerView: 1,
      },
      700: {
         slidesPerView: 2,
      },
      1024: {
         slidesPerView: 3,
      },
   },
});
</script>

</body>
</html>
