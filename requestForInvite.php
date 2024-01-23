<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$email=$_GET['inviteEmail'];
$name=$_GET['inviteName'];
$message=$_GET['message'];
$organiser=$_GET['organiser'];
$event_no=$_GET['event_no'];
$eventName=$_GET['name'];
$sql="SELECT * FROM invites WHERE event_id=:event_id";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":event_id", $event_no, PDO::PARAM_INT);
$stmt->execute();
$results=$stmt->fetchAll();
foreach ($results as $result){
    if ($result['email']==$email){
        header("Location:event.php?event_no=$event_no&error=1");
        exit();
    }
}
if (!empty($email) && !empty($message)){
    $phpmailer = new PHPMailer(true);

    $phpmailer->isSMTP();
    $phpmailer->Host = 'first.stud.vts.su.ac.rs';
    $phpmailer->SMTPAuth = true;
    $phpmailer->Port = 587;
    $phpmailer->Username = 'first';
    $phpmailer->Password = 'ZADcO14NsZMPzeU';


    $phpmailer->setFrom("first@first.stud.vts.su.ac.rs");
    $phpmailer->addAddress("$organiser");
    $phpmailer->addReplyTo($email);

    $phpmailer->isHTML(true);
    $phpmailer->CharSet = 'UTF-8';
    $phpmailer->Subject = "Zainteresovani za događaj '$eventName'";
    $phpmailer->Body = $message;
    $phpmailer->AltBody = $message;

    $phpmailer->send();
    insertIntoMessages($pdo, $message, $event_no, $email, $name);
    $_SESSION['invite_message']='Vaš zahtev se trenutno obrađuje';
    header("Location: index.php");
} else {
    echo "Please send all the required data";
}