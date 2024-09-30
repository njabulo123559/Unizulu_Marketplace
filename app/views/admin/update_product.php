<?php

include __DIR__ . '/../../../config/db.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
   header('location:admin_login.php');
   exit;
}

if (isset($_POST['update'])) {

   $pid = filter_var($_POST['pid'], FILTER_SANITIZE_STRING);
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $price = filter_var($_POST['price'], FILTER_SANITIZE_STRING);
   $category = filter_var($_POST['category'], FILTER_SANITIZE_STRING);

   // Update product details
   $update_product = $conn->prepare("UPDATE `products` SET name = ?, category = ?, price = ? WHERE id = ?");
   $update_product->bind_param("ssdi", $name, $category, $price, $pid);
   $update_product->execute();

   $message[] = 'Product updated!';

   $old_image = filter_var($_POST['old_image'], FILTER_SANITIZE_STRING);
   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'http://localhost/project-root/uploaded_img/'.$image;

   // Check for image update
   if (!empty($image)) {
      // Ensure the image file is not too large
      if ($image_size > 2000000) {
         $message[] = 'Image size is too large!';
      } else {
         // Update image in the database
         $update_image = $conn->prepare("UPDATE `products` SET image = ? WHERE id = ?");
         $update_image->bind_param("si", $image, $pid);
         $update_image->execute();

         // Move the new image and delete the old one
         move_uploaded_file($image_tmp_name, $image_folder);
         if (!empty($old_image)) {
            unlink('http://localhost/project-root/uploaded_img/'.$old_image);
         }
         $message[] = 'Image updated!';
      }
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/admin_style.css">

</head>
<body>

<?php include __DIR__ . '/../components/admin_header.php'; ?>

<!-- Update product section starts -->

<section class="update-product">

   <h1 class="heading">Update Product</h1>

   <?php
      $update_id = filter_var($_GET['update'], FILTER_SANITIZE_STRING);
      $show_products = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
      $show_products->bind_param("i", $update_id);
      $show_products->execute();
      $result = $show_products->get_result();

      if ($result->num_rows > 0) {
         while ($fetch_products = $result->fetch_assoc()) {  
   ?>
   <form action="" method="POST" enctype="multipart/form-data">
      <input type="hidden" name="pid" value="<?= $fetch_products['id']; ?>">
      <input type="hidden" name="old_image" value="<?= $fetch_products['image']; ?>">
      <img src="http://localhost/project-root/uploaded_img/<?= htmlspecialchars($fetch_products['image']); ?>" alt="Product Image">
      
      <span>Update Name</span>
      <input type="text" required placeholder="Enter product name" name="name" maxlength="100" class="box" value="<?= htmlspecialchars($fetch_products['name']); ?>">

      <span>Update Price</span>
      <input type="number" min="0" max="9999999999" required placeholder="Enter product price" name="price" class="box" value="<?= $fetch_products['price']; ?>">

      <span>Update Category</span>
      <select name="category" class="box" required>
         <option selected value="<?= htmlspecialchars($fetch_products['category']); ?>"><?= htmlspecialchars($fetch_products['category']); ?></option>
         <option value="main dish">Main Dish</option>
         <option value="fast food">Fast Food</option>
         <option value="drinks">Drinks</option>
         <option value="desserts">Desserts</option>
      </select>

      <span>Update Image</span>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp">
      
      <div class="flex-btn">
         <input type="submit" value="Update" class="btn" name="update">
         <a href="products.php" class="option-btn">Go Back</a>
      </div>
   </form>
   <?php
         }
      } else {
         echo '<p class="empty">No products found!</p>';
      }
   ?>

</section>

<!-- Update product section ends -->

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/admin_script.js"></script>

</body>
</html>
