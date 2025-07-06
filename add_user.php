<?php
require_once 'config.php';
require_once 'functions_def.php';
require_once 'vendor/autoload.php';
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
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
$phpmailer = new PHPMailer(true);
include('connection.php');
$event_id=$_POST['id'];
$message=$_POST['message']??null;
$email = trim($_POST['email']);
$fname = ucfirst(strtolower(trim($_POST['fname'])));
$addWishList = $_POST['addWishList'];
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $data = array(
        'status'=>'invalid email'
    );
    echo json_encode($data);
    exit();
}
if($decoded->data->username===$email){
    $data = array(
        'status'=>'same email'
    );
    echo json_encode($data);
    exit();
}
if (empty($addWishList))
    $addWishList='no';
$sql='SELECT id_event FROM messages WHERE id_event=:id_event';
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":id_event",$event_id,PDO::PARAM_STR);
$stmt->execute();
if ($stmt->rowCount()>0){
    $sql="UPDATE messages SET status='replied' where id_event=:id_event";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(":id_event",$event_id,PDO::PARAM_STR);
    $stmt->execute();
}
$sql='SELECT email FROM invites WHERE email=:email AND event_id=:event_id';
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":email",$email,PDO::PARAM_STR);
$stmt->bindValue(":event_id",$event_id,PDO::PARAM_STR);
$stmt->execute();
if ($stmt->rowCount()>0){
    $data = array(
        'status'=>"Korisnik je već dodat u listu zvanica!",
    );
    echo json_encode($data);
    exit();
}
$token=createToken(10);
$sql = "INSERT INTO `invites` (`email`,`name`,`event_id`,`invite_code`, `wish_list`) values ('$email', '$fname','$event_id','$token','$addWishList')";
$query= mysqli_query($con,$sql);
$lastId = mysqli_insert_id($con);
if($query ==true)
{
    $data = array(
        'status'=>'true',
    );
    try {
        //$phpmailer->SMTPDebug = SMTP::DEBUG_SERVER; // /Enable verbose debug output, you can put this in comment in production
        $phpmailer->isSMTP();
        $phpmailer->Host = 'rsharp.stud.vts.su.ac.rs';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username   = 'rsharp';
        $phpmailer->Password   = 'HE9TiVYvUs1G9Xl';
        $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $phpmailer->Port = 587;

        $phpmailer->setFrom($decoded->data->username, $decoded->data->firstname);
        $phpmailer->addAddress("$email");

        $phpmailer->isHTML(false);
        $phpmailer->Subject = 'Pozivnica za događaj';

        $sql="SELECT name, DATE(date) as date2, TIME(date) as time2 FROM event WHERE id=:id";
        $stmt=$GLOBALS['pdo']->prepare($sql);
        $stmt->bindValue(":id",$event_id,PDO::PARAM_INT);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_ASSOC);
        $phpmailer->CharSet = 'UTF-8';
        $time=date("H:i",strtotime($results['time2']));
        if (isset($message)){
            $phpmailer->Body=$message."<br>Da bi ste potvrdili vaš dolazak potrebno je da odete na <a href=". SITE ."inviteResponse.php?code=$token>link</a>.";
        } else {
            $phpmailer->Body = "Pozdrav. Pozvani ste da dođete na \"{$results['name']}\" događaj koji će se održati {$results['date2']} u {$time}h. Da bi ste potvrdili vaš dolazak potrebno je da odete na <a href=" . SITE . "inviteResponse.php?code=$token>link</a>";
        }
        $phpmailer->AltBody = "Pozdrav. Pozvani ste da dođete na \"{$results['name']}\" događaj koji će se održati {$results['date2']} u {$results['time2']}h. Da bi ste potvrdili vaš dolazak potrebno je da odete na ".SITE."inviteResponse.php?code=$token";

        $phpmailer->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
    }
    echo json_encode($data);
}
else
{
     $data = array(
        'status'=>'false',
     );
    echo json_encode($data);
}