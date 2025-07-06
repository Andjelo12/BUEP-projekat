<?php
session_start();
require "config.php";
require "functions_def.php";
require_once 'Mobile-Detect-3.74.0/src/MobileDetect.php';
require __DIR__ . "/vendor/autoload.php";
use \Firebase\JWT\JWT;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
$referer = $_SERVER['HTTP_REFERER'];
$country = "";
$proxy = false;
$detect = new \Detection\MobileDetect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$ipAddress = getIpAddress();
$user_agent = $_SERVER["HTTP_USER_AGENT"];
$urlApi = "http://ip-api.com/json/$ipAddress?fields=$apiFields";
$apiResponse = getCurlData($urlApi);

$apiData = json_decode($apiResponse, true);

if (isset($apiData['country']))
    $country = $apiData['country'];

if (isset($apiData['proxy']))
    $proxy = $apiData['proxy'];
$client = new Google\Client();

$client->setClientId("912256885401-ghr2br1tj0occ6bjnqmtrq07fu3ds8g3.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("https://rsharp.stud.vts.su.ac.rs/redirectRegister.php");

if(!isset($_GET["code"])){
    exit("Login failed");
}

$token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);

$client->setAccessToken($token['access_token']);

$oauth = new Google\Service\Oauth2($client);

$userInfo = $oauth->userinfo->get();
if (emailExists($pdo, $userInfo['email'])) {
    redirection('login.php?l=2');
} else {
    $id_user = registerUserGoogle($pdo, $userInfo['id'], $userInfo['givenName'], $userInfo['familyName'], $userInfo['email']);
    $payload=[
        'iss' => 'https://rsharp.stud.vts.su.ac.rs',
        'aud' => 'https://rsharp.stud.vts.su.ac.rs',
        'iat' => time(),
        'nbf' => time(),
        'exp' => time()+(60*60),
        'data' => [
            'username'=>$userInfo['email'],
            'firstname'=>$userInfo['givenName'],
            'is_admin'=>'No',
            'id_user'=>$id_user
        ]
    ];
    $encoded = JWT::encode($payload, $_ENV['jwt_secret_key'], 'HS256');
    $_SESSION = [];
    $_SESSION['jwt_token'] = $encoded;
    /*
    $_SESSION['username'] = $userInfo['email'];
    $_SESSION['firstname'] = $userInfo['givenName'];
    $_SESSION['is_admin'] = 'No';
    $_SESSION['id_user'] = $id_user;*/
    $lastInsertID = insertIntoUserDetects($userInfo['givenName'], $id_user);
    insertIntoDetects($user_agent, $ipAddress, $deviceType, $country, $lastInsertID, $proxy, 'success','1');
    redirection('index.php');
}
//echo $userInfo['id'];
/*echo $userInfo['givenName'];
echo $userInfo['familyName'];*/