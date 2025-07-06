<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
$sql = "UPDATE tokens SET account_type = :account_type, tokens_no = :tokens_no WHERE email = :email";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':email', $_SESSION['username'], PDO::PARAM_STR);
$stmt->bindParam(':account_type', $_GET['type'], PDO::PARAM_STR);
$stmt->bindParam(':tokens_no', $tokens_no, PDO::PARAM_STR);
switch ($_GET['type']) {
    case 'silver':
        $tokens_no=150;
        break;
    case 'gold':
        $tokens_no=300;
        break;
    case 'platinum':
        $tokens_no=500;
        break;
}
$stmt->execute();
header("Location: token.php");