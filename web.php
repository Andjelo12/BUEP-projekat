<?php
session_start();
require_once "config.php";
require_once "functions_def.php";
require_once 'Mobile-Detect-3.74.0/src/MobileDetect.php';

$password = "";
$passwordConfirm = "";
$firstname = "";
$lastname = "";
$email = "";
$action = "";

$referer = $_SERVER['HTTP_REFERER'];


$action = $_POST["action"];

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

if ($action != "" and in_array($action, $actions)/* and strpos($referer, SITE) !== false*/ ) {


    switch ($action) {
        case "login":

            $username = trim($_POST["username"]);
            $password = trim($_POST["password"]);

            if (!empty($username) and !empty($password)) {
                $data = checkUserLogin($pdo, $username, $password);
                $userData = emailExists($pdo, $username);
                if ($data and is_int($data['id_user']) and $data['active']==1) {
                    if ($data['is_banned']==0){
                        $_SESSION = [];
                        $_SESSION['username'] = $username;
                        $_SESSION['firstname'] = $data['firstname'];
                        $_SESSION['is_admin']=$data['is_admin'];
                        $_SESSION['id_user'] = $data['id_user'];
                        $lastInsertID=insertIntoUserDetects($username, $userData);
                        insertIntoDetects($user_agent, $ipAddress,$deviceType, $country, $lastInsertID, $proxy, 'success');
                        redirection('index.php');
                    }elseif($data['is_banned']==1){
                        $lastInsertID=insertIntoUserDetects($username, $userData);
                        insertIntoDetects($user_agent, $ipAddress,$deviceType, $country, $lastInsertID, $proxy, 'banned');
                        redirection('login.php?l=17');
                    }
                }elseif($data and $data['active']==0){
                    redirection('login.php?l=18');
                } else {
                    $lastInsertID=insertIntoUserDetects($username, $userData);
                    insertIntoDetects($user_agent, $ipAddress,$deviceType, $country, $lastInsertID,$proxy, 'wrong data');
                    redirection('login.php?l=1');
                }

            } else {
                redirection('login.php?l=1');
            }
            break;


        case "register" :

            if (isset($_POST['firstname'])) {
                $firstname = ucfirst(strtolower(trim($_POST["firstname"])));
            }

            if (isset($_POST['lastname'])) {
                $lastname = ucfirst(strtolower(trim($_POST["lastname"])));
            }

            if (isset($_POST['password'])) {
                $password = trim($_POST["password"]);
            }

            if (isset($_POST['passwordConfirm'])) {
                $passwordConfirm = trim($_POST["passwordConfirm"]);
            }

            if (isset($_POST['email'])) {
                $email = trim($_POST["email"]);
            }

            if (empty($firstname)) {
                redirection('register.php?r=4');
            }

            if (empty($lastname)) {
                redirection('register.php?r=4');
            }

            if (empty($password)) {
                redirection('register.php?r=9');
            }

            if (!preg_match("#.*^(?=.{8,20})(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W).*$#", $password)) {
                redirection('register.php?r=10');
            }

            if (empty($passwordConfirm)) {
                redirection('register.php?r=9');
            }

            if ($password !== $passwordConfirm) {
                redirection('register.php?r=7');
            }

            if (empty($email) or !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                redirection('register.php?r=8');
            }
            $userData = emailExists($pdo, $email);
            if (!existsUser($pdo, $email)) {
                $token = createToken(20);
                if ($token) {
                    $id_user = registerUser($pdo, $password, $firstname, $lastname, $email, $token);
                    try {
                        $lastInsertID=insertIntoUserDetects($email, $id_user);
                        insertIntoDetects($user_agent, $ipAddress,$deviceType, $country, $lastInsertID, $proxy, 'register');
                        $body = "Da bi ste aktivirali nalog potrebno je da odete na <a href=" . SITE . "active.php?token=$token>link</a>";
                        sendEmail($pdo, $email, $emailMessages['register'], $body, $id_user);
                        redirection("login.php?l=3");
                    } catch (Exception $e) {
                        error_log("****************************************");
                        error_log($e->getMessage());
                        error_log("file:" . $e->getFile() . " line:" . $e->getLine());
                        redirection("register.php?r=11");
                    }
                }
            } else {
                $lastInsertID=insertIntoUserDetects($email, $userData);
                insertIntoDetects($user_agent, $ipAddress,$deviceType, $country, $lastInsertID, $proxy, 'register with same email');
                redirection('register.php?r=2');
            }

            break;

        case "forget" :
            $email = trim($_POST["email"]);
            if (!empty($email) and getUserData($pdo, 'id_user', 'email', $email)) {
                $token = createToken(20);
                if ($token) {
                    setForgottenToken($pdo, $email, $token);
                    $id_user = getUserData($pdo, 'id_user', 'email', $email);
                    try {
                        $body = "Da bi ste započeli proces izmene lozinke posetite <a href=" . SITE . "forget.php?token=$token>sajt</a>.";
                        sendEmail($pdo, $email, $emailMessages['forget'], $body, $id_user);
                        redirection('login.php?f=13');
                    } catch (Exception $e) {
                        error_log("****************************************");
                        error_log($e->getMessage());
                        error_log("file:" . $e->getFile() . " line:" . $e->getLine());
                        redirection("login.php?f=11");
                    }
                } else {
                    redirection('login.php?f=14');
                }
            } else {
                redirection('login.php?f=13');
            }
            break;

        default:
            redirection('login.php?l=0');
            break;
    }

} else {
    redirection('login.php?l=0');
}
