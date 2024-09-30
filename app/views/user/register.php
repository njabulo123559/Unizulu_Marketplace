<?php

// Include the database connection
include __DIR__ . '/../../../config/db.php'; // Adjust the path based on your folder structure

session_start();

// Check if the user is already logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
}

// Process the form when submitted
if (isset($_POST['submit'])) {

    // Sanitize the input
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $number = filter_var($_POST['number'], FILTER_SANITIZE_STRING);
    $address = filter_var($_POST['address'], FILTER_SANITIZE_STRING);
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];

    // Check if the email or phone number already exists
    if ($stmt = $conn->prepare("SELECT * FROM `users` WHERE email = ? OR number = ?")) {
        $stmt->bind_param("ss", $email, $number); // Bind email and number
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $message[] = 'Email or number already exists!';
        } else {
            // Check if passwords match
            if ($pass != $cpass) {
                $message[] = 'Passwords do not match!';
            } else {
                // Hash the password
                $hashed_pass = password_hash($pass, PASSWORD_DEFAULT);

                // Insert the new user with the address field
                if ($insert_stmt = $conn->prepare("INSERT INTO `users` (name, email, number, password, address) VALUES (?, ?, ?, ?, ?)")) {
                    $insert_stmt->bind_param("sssss", $name, $email, $number, $hashed_pass, $address);
                    $insert_stmt->execute();

                    // Fetch the newly inserted user to set the session
                    if ($select_stmt = $conn->prepare("SELECT * FROM `users` WHERE email = ?")) {
                        $select_stmt->bind_param("s", $email);
                        $select_stmt->execute();
                        $user_result = $select_stmt->get_result();

                        if ($user_result->num_rows > 0) {
                            $row = $user_result->fetch_assoc();
                            $_SESSION['user_id'] = $row['id'];
                            header('location:home.php');
                            exit();
                        }
                    }
                } else {
                    $message[] = 'Failed to register user!';
                }
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
   <title>Register</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/style.css"> <!-- Adjust path for CSS -->

</head>
<body>
   
<!-- header section starts  -->
<?php include __DIR__ . '/../components/user_header.php'; ?> <!-- Adjust path for header -->
<!-- header section ends -->

<section class="form-container">

   <form action="" method="post">
      <h3>Register Now</h3>
      <input type="text" name="name" required placeholder="Enter your name" class="box" maxlength="50">
      <input type="email" name="email" required placeholder="Enter your email" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="number" name="number" required placeholder="Enter your number" class="box" min="0" max="9999999999" maxlength="10">
      <input type="text" name="address" required placeholder="Enter your address" class="box" maxlength="255">
      <input type="password" name="pass" required placeholder="Enter your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="cpass" required placeholder="Confirm your password" class="box" maxlength="50" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Register Now" name="submit" class="btn">
      <p>Already have an account? <a href="/project-root/app/views/user/login.php">Login now</a></p>

      <!-- Display error messages -->
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
