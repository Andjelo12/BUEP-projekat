<?php
header("Content-Type:application/json");
require_once '../config.php';
require_once '../functions_def.php';


$country = "";
$proxy = false;
$deviceType = 'phone';
$ipAddress = getIpAddress();
$user_agent = "";
$urlApi = "http://ip-api.com/json/$ipAddress?fields=$apiFields";
$apiResponse = getCurlData($urlApi);

$apiData = json_decode($apiResponse, true);

if (isset($apiData['country']))
    $country = $apiData['country'];

if (isset($apiData['proxy']))
    $proxy = $apiData['proxy'];

$json = file_get_contents('php://input');
$obj = json_decode($json,true);

$firstName = trim($obj['firstName']) ?? null;
$lastName = trim($obj['lastName']) ?? null;
$email = trim($obj['email']) ?? null;
$password = trim($obj['password']) ?? null;

if(!empty($firstName) && !empty($lastName) && !empty($email) && !empty($password)){
    $userData = emailExists($pdo, $email);
    if (!existsUser($pdo,$email)){
        $token = createToken(20);
        if ($token) {
            $id_user = registerUser($pdo, $password, $firstName, $lastName, $email, $token);
            try {
                $lastInsertID=insertIntoUserDetects($email, $id_user);
                insertIntoDetects($user_agent, $ipAddress,$deviceType, $country, $lastInsertID, $proxy, 'register');
                $body = "Da bi ste aktivirali nalog potrebno je da odete na <a href=" . SITE . "active.php?token=$token>link</a>";
                sendEmail($pdo, $email, $emailMessages['register'], $body, $id_user);
                http_response_code(200);
                echo json_encode("Account successfully created");
            } catch (Exception $e) {
                error_log("****************************************");
                error_log($e->getMessage());
                error_log("file:" . $e->getFile() . " line:" . $e->getLine());
                http_response_code(500);
                echo json_encode("Error has occured.");
            }
        }
    } else {
        $lastInsertID=insertIntoUserDetects($email, $userData);
        insertIntoDetects($user_agent, $ipAddress,$deviceType, $country, $lastInsertID, $proxy, 'register with same email');
        http_response_code(400);
        echo json_encode("User already exsists");
    }
    /*$sql="INSERT INTO users2()";
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
    echo json_encode('Send all the requested data!');
}