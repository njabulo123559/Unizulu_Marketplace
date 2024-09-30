<?php

include __DIR__ . '/../../../config/db.php';

session_start();

// Ensure the admin is logged in
$admin_id = $_SESSION['admin_id'] ?? null;

if (!$admin_id) {
    header('location:admin_login.php');
    exit;
}

// Handle message deletion
if (isset($_GET['delete'])) {
    $delete_id = filter_var($_GET['delete'], FILTER_SANITIZE_NUMBER_INT); // Ensure valid ID
    $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
    $delete_message->bind_param("i", $delete_id);

    if ($delete_message->execute()) {
        header('location:messages.php');
        exit;
    } else {
        $message[] = 'Failed to delete message';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Messages</title>

    <!-- Font Awesome CDN Link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- Custom CSS File Link -->
    <link rel="stylesheet" href="http://localhost/project-root/assets/css/admin_style.css">
</head>
<body>

<?php include __DIR__ . '/../components/admin_header.php'; ?>

<!-- Messages Section Starts -->
<section class="messages">
    <h1 class="heading">Messages</h1>

    <div class="box-container">

        <?php
        // Fetch all messages from the database
        $select_messages = $conn->prepare("SELECT * FROM `messages`");
        $select_messages->execute();
        $result = $select_messages->get_result();

        if ($result->num_rows > 0) {
            while ($fetch_messages = $result->fetch_assoc()) {
        ?>
            <div class="box">
                <p> Name: <span><?= htmlspecialchars($fetch_messages['name']); ?></span> </p>
                <p> Number: <span><?= htmlspecialchars($fetch_messages['number']); ?></span> </p>
                <p> Email: <span><?= htmlspecialchars($fetch_messages['email']); ?></span> </p>
                <p> Message: <span><?= nl2br(htmlspecialchars($fetch_messages['message'])); ?></span> </p>
                <a href="messages.php?delete=<?= htmlspecialchars($fetch_messages['id']); ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this message?');">Delete</a>
            </div>
        <?php
            }
        } else {
            echo '<p class="empty">You have no messages</p>';
        }
        ?>

    </div>
</section>
<!-- Messages Section Ends -->

<!-- Custom JS File Link -->
<script src="http://localhost/project-root/assets/js/admin_script.js"></script>

</body>
</html>
