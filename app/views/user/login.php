<?php
// Include the database connection
include __DIR__ . '/../../../config/db.php'; // Adjust the path based on your structure

session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Check if the login form is submitted
if (isset($_POST['submit'])) {

    // Sanitize the input
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $pass = $_POST['pass'];

    // Prepare the SQL statement for MySQLi
    if ($stmt = $conn->prepare("SELECT * FROM `users` WHERE email = ?")) {
        $stmt->bind_param("s", $email); // Bind the email parameter
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Verify the password
            if (password_verify($pass, $row['password'])) {
                // Set the session user ID
                $_SESSION['user_id'] = $row['id'];
                // Redirect to home page
                header('location:home.php');
                exit();
            } else {
                // If password is incorrect
                $message[] = 'Incorrect username or password!';
            }
        } else {
            // If email does not exist
            $message[] = 'Incorrect username or password!';
        }
        $stmt->close();
    } else {
        $message[] = 'Something went wrong with the query.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/style.css"> <!-- Adjust path for CSS -->

</head>
<body>

<!-- header section starts  -->
<?php include __DIR__ . '/../components/user_header.php'; ?> <!-- Adjust path for user header -->
<!-- header section ends -->

<section class="form-container">

   <form action="" method="post">
      <h3>Login Now</h3>
      <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Login Now" name="submit" class="btn">
      <p>Don't have an account? <a href="register.php">Register now</a></p>

      <!-- Show error messages -->
      <?php
      if (isset($message)) {
         foreach ($message as $msg) {
            echo '<p class="error-message">' . htmlspecialchars($msg) . '</p>';
         }
      }
      ?>
   </form>

</section>

<?php include __DIR__  . '/../components/footer.php'; ?> <!-- Adjust path for footer -->

<!-- custom js file link  -->
<script src="http://localhost/project-root/assets/js/script.js"></script> <!-- Adjust path for JS -->

</body>
</html>



