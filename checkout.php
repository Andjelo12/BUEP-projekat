<?php
require __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

\Stripe\Stripe::setApiKey($_ENV['stripe_secret_key']);

$checkout_session = \Stripe\Checkout\Session::create([
    "mode" => "payment",
    "success_url" => "https://rsharp.stud.vts.su.ac.rs/successful_payment.php?type=".$_POST['plan'],
    "cancel_url" => "https://rsharp.stud.vts.su.ac.rs/index.php",
    "line_items" => [
        [
            "quantity" => 1,
            "price_data" => [
                "currency" => "eur",
                "unit_amount" => (int)$_POST['price'],
                "product_data" => [
                    "name" => 'API '.$_POST['plan'].' account'
                ]
            ]
        ]
    ]
]);

http_response_code(303);
header("Location: " . $checkout_session->url);