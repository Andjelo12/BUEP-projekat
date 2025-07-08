<?php
session_start();
include('config.php');
require_once 'functions_def.php';
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
if (isset($_SESSION['jwt_token']) /*&& $_SESSION['is_admin']=='No'*/) {
    try {
        $decoded = JWT::decode($_SESSION['jwt_token'], new Key($_ENV['jwt_secret_key'], 'HS256'));
    }catch (\Firebase\JWT\ExpiredException){
        redirection('login.php');
    }
}
$email=$decoded->data->username;
$token = 'Bearer ' . createToken(10);
$sql = "SELECT * FROM tokens WHERE email=:email";
$query = $pdo -> prepare($sql);
$query->bindParam(':email',$email, PDO::PARAM_STR);
$query->execute();
if($query->rowCount() > 0){
    header("Location: token.php");
} else {
    $sql = "INSERT INTO tokens(token, email) VALUES (:token, :email)";
    $query = $pdo -> prepare($sql);
    $query->bindParam(':token',$token,PDO::PARAM_STR);
    $query->bindParam(':email',$email, PDO::PARAM_STR);
    $query->execute();
    header("Location: token.php");
}