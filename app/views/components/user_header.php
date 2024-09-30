<?php
if (isset($message)) {
   foreach ($message as $msg) {
      echo '
      <div class="message">
         <span>' . htmlspecialchars($msg) . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<header class="header">
   <section class="flex">

      <a href="/project-root/app/views/user/home.php" class="logo">DocShed 😋</a>

      <nav class="navbar">
         <a href="/project-root/app/views/user/home.php">home</a>
         <a href="/project-root/app/views/user/about.php">about</a>
         <a href="/project-root/app/views/user/menu.php">menu</a>
         <a href="/project-root/app/views/user/orders.php">orders</a>
         <a href="/project-root/app/views/user/contact.php">contact</a>
      </nav>

      <div class="icons">
         <?php
            // Check if $conn (MySQLi connection) and $user_id are set
            if (isset($conn) && isset($user_id)) {
               // Prepare statement for MySQLi
               $count_cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
               $count_cart_items->bind_param("i", $user_id); // 'i' specifies the integer data type for $user_id
               $count_cart_items->execute();
               $result = $count_cart_items->get_result();  // Fetch the result set
               $total_cart_items = $result->num_rows;
            } else {
               $total_cart_items = 0; // Default to 0 if no user is logged in
            }
         ?>
         <a href="/project-root/app/views/user/search.php"><i class="fas fa-search"></i></a>
         <a href="/project-root/app/views/user/cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $total_cart_items; ?>)</span></a>
         <div id="user-btn" class="fas fa-user"></div>
         <div id="menu-btn" class="fas fa-bars"></div>
      </div>

      <div class="profile">
         <?php
            // Only fetch profile if user is logged in and $user_id is available
            if (isset($conn) && isset($user_id)) {
               // Prepare statement for fetching profile data using MySQLi
               $select_profile = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_profile->bind_param("i", $user_id);  // 'i' specifies the integer data type for $user_id
               $select_profile->execute();
               $result_profile = $select_profile->get_result();  // Fetch the result set

               if ($result_profile->num_rows > 0) {
                  $fetch_profile = $result_profile->fetch_assoc();  // Fetch as associative array
         ?>
         <p class="name"><?= htmlspecialchars($fetch_profile['name']); ?></p>
         <div class="flex">
            <a href="profile.php" class="btn">profile</a>
            <a href="/project-root/app/views/user/home.php" onclick="return confirm('Logout from this website?');" class="delete-btn">logout</a>
         </div>
         <?php
               } else {
         ?>
         <p class="account">
            <a href="login.php">login</a> or
            <a href="register.php">register</a>
         </p>
         <?php
               }
            } else {
         ?>
         <p class="name">Please login first!</p>
         <a href="login.php" class="btn">login</a>
         <?php
            }
         ?>
      </div>

   </section>
</header>
