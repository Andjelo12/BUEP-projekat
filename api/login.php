<?php
header("Content-Type:application/json");
require_once '../config.php';
require_once '../functions_def.php';

$country = "";
$proxy = false;
$deviceType = 'phone';
$ipAddress = getIpAddress();
$user_agent = '';
$urlApi = "http://ip-api.com/json/$ipAddress?fields=$apiFields";
$apiResponse = getCurlData($urlApi);

$apiData = json_decode($apiResponse, true);

if (isset($apiData['country']))
    $country = $apiData['country'];

if (isset($apiData['proxy']))
    $proxy = $apiData['proxy'];

$json = file_get_contents('php://input');
$obj = json_decode($json,true);

$email = $obj['email'] ?? null;
$password = $obj['password'] ?? null;

if(!empty($email) && !empty($password)){

    $data = checkUserLogin($pdo, $email, $password);
    $userData = emailExists($pdo, $email);
    if ($data and is_int($data['id_user']) and $data['active']==1) {
        if ($data['is_banned']==0){
            $lastInsertID=insertIntoUserDetects($email, $userData);
            insertIntoDetects($user_agent, $ipAddress,$deviceType, $country, $lastInsertID, $proxy, 'success');
            http_response_code(200);
            echo json_encode('Successfuly loged in');
        }elseif($data['is_banned']==1){
            $lastInsertID=insertIntoUserDetects($email, $userData);
            insertIntoDetects($user_agent, $ipAddress,$deviceType, $country, $lastInsertID, $proxy, 'banned');
            http_response_code(403);
            echo json_encode('Account is blocked');
        }
    }elseif($data and $data['active']==0){
        http_response_code(403);
        echo json_encode('Account is not activated');
    } else {
        $lastInsertID=insertIntoUserDetects($email, $userData);
        insertIntoDetects($user_agent, $ipAddress,$deviceType, $country, $lastInsertID,$proxy, 'wrong data');
        http_response_code(401);
        echo json_encode('Wrong Details');
    }
    /*$sql="SELECT password FROM users2 where email=:email";
    $query = $pdo -> prepare($sql);
    $query->bindValue("email",$email);
    $query->execute();
    $result=$query->fetch();
    if (password_verify($password,$result['password']))
        echo json_encode('ok');
    else {
        echo json_encode('Wrong Details');
    }*/
} else {
    http_response_code(400);
    echo json_encode('Send required data!');
}