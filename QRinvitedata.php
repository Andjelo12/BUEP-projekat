<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$email=strtolower($_GET['inviteEmail']);
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $data = array(
        'status'=>'E-mail adres nije validna!'
    );
    echo json_encode($data);
    exit();
}
$name=ucfirst(strtolower($_GET['inviteName']));
$message=$_GET['message'];
$organiser=$_GET['organiser'];
$event_no=$_GET['event_no'];
$eventName=$_GET['eventName'];
$sql="SELECT * FROM invites WHERE event_id=:event_id";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":event_id", $event_no, PDO::PARAM_INT);
$stmt->execute();
$results=$stmt->fetchAll();
foreach ($results as $result){
    if ($result['email']==$email){
        $data = array(
            'status'=>'Već ste pozvani na događaj!'
        );
        echo json_encode($data);
        exit();
    }
}
$sql="SELECT sender_email FROM messages WHERE id_event=:id_event";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":id_event", $event_no, PDO::PARAM_INT);
$stmt->execute();
$results=$stmt->fetchAll();
foreach ($results as $result){
    if ($result['sender_email']==$email){
        $data = array(
            'status'=>'Zahtev za ovaj događaj je već poslat!'
        );
        echo json_encode($data);
        exit();
    }
}
$phpmailer = new PHPMailer(true);

$phpmailer->isSMTP();
$phpmailer->Host = 'rsharp.stud.vts.su.ac.rs';
$phpmailer->SMTPAuth = true;
$phpmailer->Port = 587;
$phpmailer->Username = 'rsharp';
$phpmailer->Password = 'HE9TiVYvUs1G9Xl';


$phpmailer->setFrom("rsharp@rsharp.stud.vts.su.ac.rs");
$phpmailer->addAddress("$organiser");
$phpmailer->addReplyTo($email);

$phpmailer->isHTML(true);
$phpmailer->CharSet = 'UTF-8';
$phpmailer->Subject = "Zainteresovani za događaj '$eventName'";
$phpmailer->Body = $message;
$phpmailer->AltBody = $message;

$phpmailer->send();
insertIntoMessages($pdo, $message, $event_no, $email, $name);
$data = array(
    'status'=>'true'
);
echo json_encode($data);