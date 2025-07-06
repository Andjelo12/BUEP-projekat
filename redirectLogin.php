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

$client->setClientId("912256885401-cv60jhcr69ug9228fasg5gt8ipgcfo5i.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("https://rsharp.stud.vts.su.ac.rs/redirectLogin.php");

if(!isset($_GET["code"])){
    exit("Login failed");
}

$token = $client->fetchAccessTokenWithAuthCode($_GET["code"]);

$client->setAccessToken($token['access_token']);

$oauth = new Google\Service\Oauth2($client);

$userInfo = $oauth->userinfo->get();
if (!emailExists($pdo, $userInfo['email'])) {
    redirection('login.php?l=19');
} else {
    $data = checkGoogleUserLogin($pdo, $userInfo['email'], $userInfo['id']);
    $userData = emailExists($pdo, $userInfo['email']);
    if ($data and is_int($data['id_user']) and $data['active']==1) {
        $payload=[
            'iss' => 'https://rsharp.stud.vts.su.ac.rs',
            'aud' => 'https://rsharp.stud.vts.su.ac.rs',
            'iat' => time(),
            'nbf' => time(),
            'exp' => time()+(60*60),
            'data' => [
                'username'=>$userInfo['email'],
                'firstname'=>$data['firstname'],
                'is_admin'=>$data['is_admin'],
                'id_user'=>$userData
            ]
        ];
        $encoded = JWT::encode($payload, $_ENV['jwt_secret_key'], 'HS256');
        $_SESSION = [];
        $_SESSION['jwt_token'] = $encoded;
        /*
        $_SESSION['username'] = $userInfo['email'];
        $_SESSION['firstname'] = $data['firstname'];
        $_SESSION['is_admin'] = $data['is_admin'];
        $_SESSION['id_user'] = $userData;*/
        $lastInsertID = insertIntoUserDetects($data['firstname'], $userData);
        insertIntoDetects($user_agent, $ipAddress, $deviceType, $country, $lastInsertID, $proxy, 'success','1');
        redirection('index.php');
    }elseif($data and $data['active']==0){
        redirection('login.php?l=18');
    }
}
//echo $userInfo['id'];
/*echo $userInfo['givenName'];
echo $userInfo['familyName'];*/