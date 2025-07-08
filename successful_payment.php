<?php
require_once 'config.php';
require_once 'functions_def.php';
require __DIR__ . "/vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

\Stripe\Stripe::setApiKey($_ENV['stripe_secret_key']);
$session = \Stripe\Checkout\Session::retrieve($_GET['session_id']);
$email = $session->metadata['email'];
$plan = $session->metadata['plan']; // ako si ga postavio dole
$sql = "UPDATE tokens SET account_type = :account_type, tokens_no = :tokens_no WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $email, PDO::PARAM_STR);
$stmt->bindParam(':account_type', $plan, PDO::PARAM_STR);
switch ($plan) {
    case 'silver':
        $tokens_no=200;
        break;
    case 'gold':
        $tokens_no=350;
        break;
    case 'platinum':
        $tokens_no=550;
        break;
}
$stmt->bindParam(':tokens_no', $tokens_no, PDO::PARAM_STR);
$stmt->execute();
header("Location: token.php");