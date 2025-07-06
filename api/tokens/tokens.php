<?php
header("Content-Type:application/json");
require_once '../../config.php';
require_once '../../functions_def.php';

$methods = ['post'];

$method = strtolower($_SERVER["REQUEST_METHOD"]);

if (!in_array($method, $methods)) {
    http_response_code(405);
    header("Allow: POST");

    echo json_encode([
        "message" => "$method is not allowed!"
    ]);
    exit();
}

$postData = file_get_contents('php://input');
$email=null;
$inviteEmail=null;
$inviteName=null;
$message=null;
if (!empty($postData)) {
    $requestData = json_decode($postData, true);
    // Check if email parameter exists
    if (isset($requestData['email'])) {
        $email = $requestData['email']??null;
    }
}
if ($method === 'post') {
    if (isset($email)) {
        $checkToken = checkTokenExists($email);
        $checkEmail=checkValidEmail($email);
        if (!$checkToken && $checkEmail) {
            createBearerToken($email);
            response(200, "Token created successfully");
        } else {
            response(403, "Invalid email");
        }
        /*        $createToken ? response(200, "Token created") :
            response(404, "Invalid email or token already exists!");*/
        exit();
    }
}