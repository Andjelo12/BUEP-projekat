<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require_once "config.php";

$pdo = connectDatabase($dsn, $pdoOptions);
$GLOBALS['pdoConn']=connectDatabase($dsn, $pdoOptions);
/** Function tries to connect to database using PDO
 * @param string $dsn
 * @param array $pdoOptions
 * @return PDO
 */
function connectDatabase(string $dsn, array $pdoOptions): PDO
{

    try {
        $pdo = new PDO($dsn, PARAMS['USER'], PARAMS['PASS'], $pdoOptions);

    } catch (\PDOException $e) {
        var_dump($e->getCode());
        throw new \PDOException($e->getMessage());
    }

    return $pdo;
}

/**
 * Function redirects user to given url
 *
 * @param string $url
 */
function redirection($url)
{
    header("Location:$url");
    exit();
}


/**
 * Function checks that login parameters exists in users_web table
 *
 * @param PDO $pdo
 * @param string $email
 * @param string $enteredPassword
 * @return array
 */
function checkUserLogin(PDO $pdo, string $email, string $enteredPassword): array
{
    $sql = "SELECT id_user, firstname,password, is_admin, is_banned, active FROM users WHERE email=:email LIMIT 0,1";
    $stmt= $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    $data = [];
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {

        $registeredPassword = $result['password'];

        if (password_verify($enteredPassword, $registeredPassword)) {
            $data['id_user'] = $result['id_user'];
            $data['is_admin']=$result['is_admin'];
            $data['is_banned']=$result['is_banned'];
            $data['active']=$result['active'];
            $data['firstname']=$result['firstname'];
        }
    }

    return $data;
}

function getIpAddress()
{

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }

    if (!filter_var($ip, FILTER_VALIDATE_IP)) {
        $ip = "unknown";
    }

    return $ip;
}

/**Function inserts data into log table.
 * @param string $userAgent
 * @param string $ipAddress
 * @param string $deviceType
 * @param string $country
 * @param bool $proxy
 * @return void
 */
function insertIntoDetects(string $userAgent, string $ipAddress, string $deviceType, string $country, string $lastInsertID,bool $proxy, string $status): void
{
    $sql = "INSERT INTO detects(user_agent, ip_address, country, proxy, device_type, user_id, status) VALUES(:userAgent, :ipAddress, :country, :proxy, :deviceType, :user_id,:status)";
    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->bindValue(':userAgent', $userAgent, PDO::PARAM_STR);
    $stmt->bindValue(':ipAddress', $ipAddress, PDO::PARAM_STR);
    $stmt->bindValue(':country', $country, PDO::PARAM_STR);
    $stmt->bindValue(':proxy', $proxy, PDO::PARAM_INT);
    $stmt->bindValue(':deviceType', $deviceType, PDO::PARAM_STR);
    $stmt->bindValue(':user_id', $lastInsertID, PDO::PARAM_STR);
    $stmt->bindValue(':status',$status, PDO::PARAM_STR);

    $stmt->execute();
}

function emailExists(PDO $pdo, string $email){
    $sql = "SELECT id_user FROM users WHERE email=:email LIMIT 0,1";
    $stmt= $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
        return $result['id_user'];
    }
    return null;
}

function insertIntoUserDetects(string $email, ?string $userDetails){
    if (empty($userDetails))
        $sql = "INSERT INTO user_detects(email) VALUES(:email)";
    else
        $sql = "INSERT INTO user_detects(email, user_details) VALUES(:email, :user_details)";
    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    if (!empty($userDetails))
        $stmt->bindValue(':user_details', $userDetails, PDO::PARAM_STR);
    $stmt->execute();
    return $GLOBALS['pdo']->lastInsertId();
}

function getCurlData($url): string
{
    // https://www.php.net/manual/en/book.curl.php

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($ch);
    curl_close($ch);

    return $result;
}

function getEmail(PDO $pdo, string $token){
    $sql = "SELECT email FROM users WHERE binary forgotten_password_token = :token AND forgotten_password_expires>now()";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();
    $result=$stmt->fetch(PDO::FETCH_ASSOC);
    return $result['email'];
}


/**
 * Function checks that user exists in users table
 * @param PDO $pdo
 * @param string $email
 * @return bool
 */
function existsUser(PDO $pdo, string $email): bool
{

    $sql = "SELECT id_user FROM users WHERE email=:email AND (registration_expires>now() OR active ='1') LIMIT 0,1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $stmt->fetch(PDO::FETCH_ASSOC);

    if ($stmt->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
}


/**Function registers user and returns id of created user
 * @param PDO $pdo
 * @param string $password
 * @param string $firstname
 * @param string $lastname
 * @param string $email
 * @param string $token
 * @return int
 */
function registerUser(PDO $pdo, string $password, string $firstname, string $lastname, string $email, string $token): int
{

    $passwordHashed = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users(password,firstname,lastname,email,registration_token, registration_expires,active)
            VALUES (:passwordHashed,:firstname,:lastname,:email,:token,DATE_ADD(now(),INTERVAL 1 DAY),0)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':passwordHashed', $passwordHashed, PDO::PARAM_STR);
    $stmt->bindParam(':firstname', $firstname, PDO::PARAM_STR);
    $stmt->bindParam(':lastname', $lastname, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->execute();

    // http://dev.mysql.com/doc/refman/5.6/en/date-and-time-functions.html

    return $pdo->lastInsertId();

}


/** Function creates random token for given length in bytes
 * @param int $length
 * @return string|null
 */
function createToken(int $length): ?string
{
    try {
        return bin2hex(random_bytes($length));
    } catch (\Exception $e) {
        // c:xampp/apache/logs/
        error_log("****************************************");
        error_log($e->getMessage());
        error_log("file:" . $e->getFile() . " line:" . $e->getLine());
        return null;
    }
}

/**
 * Function creates code with given length and returns it
 *
 * @param $length
 * @return string
 */
function createCode($length): string
{
    $down = 97;
    $up = 122;
    $i = 0;
    $code = "";

    /*    
      48-57  = 0 - 9
      65-90  = A - Z
      97-122 = a - z        
    */

    $div = mt_rand(3, 9); // 3

    while ($i < $length) {
        if ($i % $div == 0)
            $character = strtoupper(chr(mt_rand($down, $up)));
        else
            $character = chr(mt_rand($down, $up)); // mt_rand(97,122) chr(98)
        $code .= $character; // $code = $code.$character; //
        $i++;
    }
    return $code;
}


/** Function tries to send email with activation code
 * @param PDO $pdo
 * @param string $email
 * @param array $emailData
 * @param string $body
 * @param int $id_user
 * @return void
 */
function sendEmail(PDO $pdo, string $email, array $emailData, string $body, int $id_user): void
{

    $phpmailer = new PHPMailer(true);

    try {

        $phpmailer->isSMTP();
        $phpmailer->Host = 'first.stud.vts.su.ac.rs';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Port = 587;
        $phpmailer->Username = 'first';
        $phpmailer->Password = 'ZADcO14NsZMPzeU';


        $phpmailer->setFrom('first@first.stud.vts.su.ac.rs', 'Admin');
        $phpmailer->addAddress("$email");

        $phpmailer->isHTML(true);
        $phpmailer->CharSet = 'UTF-8';
        $phpmailer->Subject = $emailData['subject'];
        $phpmailer->Body = $body;
        $phpmailer->AltBody = $emailData['altBody'];

        $phpmailer->send();
    } catch (Exception $e) {
        $message = "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
        addEmailFailure($pdo, $id_user, $message);
    }

}

function insertIntoMessages(PDO $pdo, string $message,int $event_no,string $email, string $name){
    $sql="INSERT INTO messages(invite_name, message, id_event, sender_email)VALUE(:name, :message, :id_event, :sender_email)";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(":name",$name,PDO::PARAM_STR);
    $stmt->bindValue(":message",$message,PDO::PARAM_STR);
    $stmt->bindValue(":id_event",$event_no,PDO::PARAM_INT);
    $stmt->bindValue(":sender_email",$email,PDO::PARAM_STR);
    $stmt->execute();
}

/** Function inserts data in database for e-mail sending failure
 * @param PDO $pdo
 * @param int $id_user
 * @param string $message
 * @return void
 */
function addEmailFailure(PDO $pdo, int $id_user, string $message): void
{
    $sql = "INSERT INTO user_email_failures (id_user, message, date_time_added)
            VALUES (:id_user,:message, now())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $stmt->bindParam(':message', $message, PDO::PARAM_STR);
    $stmt->execute();

}


/**
 * Function returns user data for given field and given value
 * @param PDO $pdo
 * @param string $data
 * @param string $field
 * @param mixed $value
 * @return mixed
 */
function getUserData(PDO $pdo, string $data, string $field, string $value): string
{
    $sql = "SELECT $data as data FROM users WHERE $field=:value LIMIT 0,1";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':value', $value, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $data = '';

    if ($stmt->rowCount() > 0) {
        $data = $result['data'];
    }

    return $data;
}

/**
 * Function sets the forgotten token
 * @param PDO $pdo
 * @param string $email
 * @param string $token
 * @return void
 */
function setForgottenToken(PDO $pdo, string $email, string $token): void
{
    $sql = "UPDATE users SET forgotten_password_token = :token, forgotten_password_expires = DATE_ADD(now(),INTERVAL 6 HOUR) WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':token', $token, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
}