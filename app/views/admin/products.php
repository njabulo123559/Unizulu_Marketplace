<?php

include __DIR__ . '/../../../config/db.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
   exit();
}

if(isset($_POST['add_product'])){

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $category = $_POST['category'];
   $category = filter_var($category, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = '../uploaded_img/'.$image;

   // Check if product name already exists
   $select_products = $conn->prepare("SELECT * FROM `products` WHERE name = ?");
   $select_products->bind_param("s", $name);
   $select_products->execute();
   $result = $select_products->get_result();

   if($result->num_rows > 0){
      $message[] = 'Product name already exists!';
   } else {
      if($image_size > 2000000){
         $message[] = 'Image size is too large';
      } else {
         // Move the uploaded image
         if(move_uploaded_file($image_tmp_name, $image_folder)){
            // Insert new product
            $insert_product = $conn->prepare("INSERT INTO `products`(name, category, price, image) VALUES(?,?,?,?)");
            $insert_product->bind_param("ssis", $name, $category, $price, $image);
            $insert_product->execute();

            $message[] = 'New product added!';
         } else {
            $message[] = 'Failed to upload the image';
         }
      }
   }

}

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];

   // Fetch the product image to delete
   $delete_product_image = $conn->prepare("SELECT * FROM `products` WHERE id = ?");
   $delete_product_image->bind_param("i", $delete_id);
   $delete_product_image->execute();
   $result = $delete_product_image->get_result();
   $fetch_delete_image = $result->fetch_assoc();
   
   if(file_exists('http://localhost/project-root/uploaded_img/'.$fetch_delete_image['image'])){
      unlink('http://localhost/project-root/uploaded_img/'.$fetch_delete_image['image']);
   }

   // Delete the product and related cart entries
   $delete_product = $conn->prepare("DELETE FROM `products` WHERE id = ?");
   $delete_product->bind_param("i", $delete_id);
   $delete_product->execute();

   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE pid = ?");
   $delete_cart->bind_param("i", $delete_id);
   $delete_cart->execute();

   header('location:products.php');
   exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/admin_style.css">

</head>
<body>

<?php include __DIR__ . '/../components/admin_header.php'; ?>

<!-- Add products section starts  -->
<section class="add-products">
   <form action="" method="POST" enctype="multipart/form-data">
      <h3>Add Product</h3>
      <input type="text" required placeholder="Enter product name" name="name" maxlength="100" class="box">
      <input type="number" min="0" max="9999999999" required placeholder="Enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
      <select name="category" class="box" required>
         <option value="" disabled selected>Select category --</option>
         <option value="main dish">Main Dish</option>
         <option value="fast food">Fast Food</option>
         <option value="drinks">Drinks</option>
         <option value="desserts">Desserts</option>
      </select>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
      <input type="submit" value="Add Product" name="add_product" class="btn">
   </form>
</section>
<!-- Add products section ends -->

<!-- Show products section starts -->
<section class="show-products" style="padding-top: 0;">
   <div class="box-container">
      <?php
         $show_products = $conn->prepare("SELECT * FROM `products`");
         $show_products->execute();
         $result = $show_products->get_result();
         if($result->num_rows > 0){
            while($fetch_products = $result->fetch_assoc()){  
      ?>
      <div class="box">
         <img src="http://localhost/project-root/uploaded_img/<?= $fetch_products['image']; ?>" alt="">
         <div class="flex">
            <div class="price"><span>$</span><?= $fetch_products['price']; ?><span>/-</span></div>
            <div class="category"><?= $fetch_products['category']; ?></div>
         </div>
         <div class="name"><?= $fetch_products['name']; ?></div>
         <div class="flex-btn">
            <a href="update_product.php?update=<?= $fetch_products['id']; ?>" class="option-btn">Update</a>
            <a href="products.php?delete=<?= $fetch_products['id']; ?>" class="delete-btn" onclick="return confirm('Delete this product?');">Delete</a>
         </div>
      </div>
      <?php
            }
         }else{
            echo '<p class="empty">No products added yet!</p>';
         }
      ?>
   </div>
</section>
<!-- Show products section ends -->

<!-- custom js file link  -->
<script src="http://localhost/project-root/assets/js/admin_script.js"></script>

</body>
</html>
