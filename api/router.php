<?php
header("Content-Type:application/json");
/*require_once '../config.php';
require_once '../functions_def.php';
*/
$methods = ['get', 'post', 'put', 'patch', 'delete'];
$resources=['account','events','presentsInvites','tokens'];

$method = strtolower($_SERVER["REQUEST_METHOD"]);

if (!in_array($method,$methods)){
    http_response_code(405);
    header("Allow: GET, POST, PUT, PATCH, DELETE");
    echo json_encode([
        "message" => "$method is not allowed!"
    ]);
    exit();
}

$resource=$_GET['resource']??'';

if (!in_array($resource,$resources)){
    http_response_code(404);
    header("Valid-Resources: account, events, presentsInvites");
    echo json_encode([
        "message" => "Resource '$resource' is not accessible!"
    ]);
    exit();
}
/*
$headers = getallheaders();
$authHeader = $headers['Authorization'] ?? '';

if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
    http_response_code(401);
    echo json_encode(["message" => "Authorization token missing or invalid"]);
    exit();
}

$token = $matches[1];

    $sql = "SELECT * FROM tokens WHERE token=:token";
    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->bindValue(":token",$token,PDO::PARAM_STR);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        http_response_code(401);
        echo json_encode(["message" => "Invalid token or daily limit exceeded"]);
        exit();
    }
*/
/*if ($resource==='account')
    require_once './account';
if ($resource==='events')
    require_once './events';
if ($resource==='presentsInvites')
    require_once './presentsInvites';*/