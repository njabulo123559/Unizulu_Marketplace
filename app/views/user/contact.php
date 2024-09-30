<?php

// Include the database connection
include __DIR__ . '/../../../config/db.php'; // Adjust the path based on your folder structure

session_start();

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

if (isset($_POST['send'])) {
   // Sanitize the input
   $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
   $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
   $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
   $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);

   // Check if the message has already been sent
   if ($stmt = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?")) {
      $stmt->bind_param("ssss", $name, $email, $number, $msg);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
         $message[] = 'Message already sent!';
      } else {
         // Insert the message into the database
         if ($insert_stmt = $conn->prepare("INSERT INTO `messages` (user_id, name, email, number, message) VALUES (?, ?, ?, ?, ?)")) {
            $insert_stmt->bind_param("issss", $user_id, $name, $email, $number, $msg);
            $insert_stmt->execute();

            $message[] = 'Message sent successfully!';
         }
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
   <title>Contact Us</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/style.css"> <!-- Adjust path for CSS -->

</head>
<body>
   
<!-- header section starts  -->
<?php include __DIR__ . '/../components/user_header.php'; ?> <!-- Adjust path for header -->
<!-- header section ends -->

<div class="heading">
   <h3>Contact Us</h3>
   <p><a href="home.php">Home</a> <span> / Contact</span></p>
</div>

<!-- contact section starts  -->
<section class="contact">

   <div class="row">

      <div class="image">
         <img src="http://localhost/project-root/assets/images/contact-img.svg" alt="Contact Us"> <!-- Adjust path for image -->
      </div>

      <form action="" method="post">
         <h3>Tell us something!</h3>
         <input type="text" name="name" maxlength="50" class="box" placeholder="Enter your name" required>
         <input type="number" name="number" min="0" max="9999999999" class="box" placeholder="Enter your number" required maxlength="10">
         <input type="email" name="email" maxlength="50" class="box" placeholder="Enter your email" required>
         <textarea name="msg" class="box" required placeholder="Enter your message" maxlength="500" cols="30" rows="10"></textarea>
         <input type="submit" value="Send Message" name="send" class="btn">

         <!-- Display success or error messages -->
         <?php
         if (isset($message)) {
            foreach ($message as $msg) {
               echo '<p class="message">' . htmlspecialchars($msg) . '</p>';
            }
         }
         ?>
      </form>

   </div>

</section>
<!-- contact section ends -->

<!-- footer section starts -->
<?php include __DIR__  . '/../components/footer.php'; ?> <!-- Adjust path for footer -->
<!-- footer section ends -->

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/script.js"></script> <!-- Adjust path for JS -->

</body>
</html>
