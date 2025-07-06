<?php
session_start();
require_once 'functions_def.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$phpmailer = new PHPMailer(true);
$id=$_GET["id"];
include_once 'functions_def.php';
$sql = "UPDATE event SET archived='yes' WHERE id=:id";
$query = $pdo -> prepare($sql);
$query->bindParam(':id',$id, PDO::PARAM_INT);
$query->execute();
if($query->rowCount() > 0)
{
    $_SESSION['message'] = 'Događaj uspešno arhiviran!';
}
if ($_SESSION['is_admin']){
    try {
        $sql = "SELECT name,created_by FROM event WHERE id=:id";
        $query = $pdo -> prepare($sql);
        $query->bindParam(':id',$id, PDO::PARAM_INT);
        $query->execute();

        $result=$query->fetch();
        //$phpmailer->SMTPDebug = SMTP::DEBUG_SERVER; // /Enable verbose debug output, you can put this in comment in production
        $phpmailer->isSMTP();
        $phpmailer->Host = 'rsharp.stud.vts.su.ac.rs';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username   = 'rsharp';
        $phpmailer->Password   = 'HE9TiVYvUs1G9Xl';
        $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $phpmailer->Port = 587;

        $phpmailer->setFrom("rsharp@rsharp.stud.vts.su.ac.rs", "Admin");
        $phpmailer->addAddress("{$result['created_by']}");

        $phpmailer->isHTML(false);
        $phpmailer->CharSet = 'UTF-8';
        $phpmailer->Subject = 'Obaveštenje o arhiviranju događaja';
        $phpmailer->Body = "Obaveštavamo Vas da je događaj \"{$result['name']}\" arhiviran od strane administratora.";
        $phpmailer->AltBody = "Obaveštavamo Vas da je događaj \"{$result['name']}\" arhiviran od strane administratora.";

        $phpmailer->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
    }
}
header("Location: all_events.php?archived=1");