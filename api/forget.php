<?php
header("Content-Type:application/json");
require_once '../config.php';
require_once '../functions_def.php';

$json = file_get_contents('php://input');
$obj = json_decode($json,true);

$email = trim($obj['email']) ?? null;

if (!empty($email) and getUserData($pdo, 'id_user', 'email', $email)) {
    $token = createToken(20);
    if ($token) {
        setForgottenToken($pdo, $email, $token);
        $id_user = getUserData($pdo, 'id_user', 'email', $email);
        try {
            $body = "Da bi ste započeli proces izmene lozinke posetite <a href=" . SITE . "forget.php?token=$token>sajt</a>.";
            sendEmail($pdo, $email, $emailMessages['forget'], $body, $id_user);
            http_response_code(200);
            echo json_encode("Reset link send successfully");
        } catch (Exception $e) {
            error_log("****************************************");
            error_log($e->getMessage());
            error_log("file:" . $e->getFile() . " line:" . $e->getLine());
            http_response_code(500);
            echo json_encode("Error has occured. Email will be sent later");
        }
    } else {
        http_response_code(400);
        echo json_encode("Error while token creation");
    }
} else {
    http_response_code(400);
    echo json_encode("Fill the required field");
}