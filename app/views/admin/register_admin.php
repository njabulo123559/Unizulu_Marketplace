<?php

include __DIR__ . '/../../../config/db.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if (!isset($admin_id)) {
    header('location:admin_login.php');
    exit;
}

if (isset($_POST['submit'])) {

    // Sanitize user input
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
    $cpass = filter_var($_POST['cpass'], FILTER_SANITIZE_STRING);

    // Check if username already exists
    $select_admin = $conn->prepare("SELECT * FROM `admin` WHERE name = ?");
    $select_admin->bind_param("s", $name);
    $select_admin->execute();
    $result = $select_admin->get_result();
    
    if ($result->num_rows > 0) {
        $message[] = 'Username already exists!';
    } else {
        // Check if passwords match
        if ($pass != $cpass) {
            $message[] = 'Confirm password does not match!';
        } else {
            // Hash the password before storing
            $hashed_pass = password_hash($pass, PASSWORD_BCRYPT);

            // Insert new admin into the database
            $insert_admin = $conn->prepare("INSERT INTO `admin`(name, password) VALUES(?, ?)");
            $insert_admin->bind_param("ss", $name, $hashed_pass);
            
            if ($insert_admin->execute()) {
                $message[] = 'New admin registered successfully!';
            } else {
                $message[] = 'Failed to register new admin!';
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
   <title>Register Admin</title>

   <!-- Font Awesome CDN Link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- Custom CSS File Link -->
   <link rel="stylesheet" href="http://localhost/project-root/assets/css/admin_style.css">

</head>
<body>

<?php include __DIR__ . '/../components/admin_header.php'; ?>

<!-- Register Admin Section Starts -->

<section class="form-container">
    <form action="" method="POST">
        <h3>Register New Admin</h3>

        <!-- Display Messages -->
        <?php
        if (isset($message)) {
            foreach ($message as $msg) {
                echo '<div class="message"><span>' . $msg . '</span></div>';
            }
        }
        ?>

        <input type="text" name="name" maxlength="20" required placeholder="Enter your username" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="pass" maxlength="20" required placeholder="Enter your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="password" name="cpass" maxlength="20" required placeholder="Confirm your password" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
        <input type="submit" value="Register Now" name="submit" class="btn">
    </form>
</section>

<!-- Register Admin Section Ends -->

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/admin_script.js"></script>

</body>
</html>
