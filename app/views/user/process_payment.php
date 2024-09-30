<?php
require_once('vendor/autoload.php'); // Load Stripe PHP library

\Stripe\Stripe::setApiKey('your-stripe-secret-key'); // Replace with your Stripe secret key

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['stripeToken'];
    $amount = $_POST['total_price'] * 100; // The amount should be in cents

    try {
        $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => 'usd',
            'description' => 'Order description',
            'source' => $token,
        ]);

        // Save the transaction and order details to your database
        // Insert order logic here

        header('Location: success.php');
        exit;
    } catch (\Stripe\Exception\CardException $e) {
        echo 'Error: ' . $e->getError()->message;
    }
}
?>
