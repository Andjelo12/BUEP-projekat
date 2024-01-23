<?php
session_start();
require_once 'functions_def.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$phpmailer = new PHPMailer(true);
$id=$_GET["id"];
$foto_name=$_GET['foto_name'];
$message=$_GET['message']??null;
include_once 'functions_def.php';
$imageFullPath='images/events/'.$foto_name;
unlink($imageFullPath);
$sql = "SELECT created_by, name FROM event WHERE id=:id";
$query = $pdo -> prepare($sql);
$query->bindParam(':id',$id, PDO::PARAM_INT);
$query->execute();

$result=$query->fetch();
$email=$result['created_by'];
$name=$result['name'];
$sql = "DELETE FROM event WHERE id=:id";
$query = $pdo -> prepare($sql);
$query->bindParam(':id',$id, PDO::PARAM_INT);
$query->execute();
if($query->rowCount() > 0) {
    $_SESSION['message'] = 'Događaj uspešno obrisan!';
}
if (isset($message)){
    try {
        //$phpmailer->SMTPDebug = SMTP::DEBUG_SERVER; // /Enable verbose debug output, you can put this in comment in production
        $phpmailer->isSMTP();
        $phpmailer->Host = 'first.stud.vts.su.ac.rs';
        $phpmailer->SMTPAuth = true;
        $phpmailer->Username   = 'first';
        $phpmailer->Password   = 'ZADcO14NsZMPzeU';
        $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $phpmailer->Port = 587;

        $phpmailer->setFrom("first@first.stud.vts.su.ac.rs", "Admin");
        $phpmailer->addAddress("$email");

        $phpmailer->isHTML(false);
        $phpmailer->CharSet = 'UTF-8';
        $phpmailer->Subject = 'Obaveštenje o brisanju događaja';
        $phpmailer->Body = "Obaveštavamo Vas da je događaj \"$name\" obrisan od strane administratora. Razlog brisanja: \"$message\"";
        $phpmailer->AltBody = "Obaveštavamo Vas da je događaj \"$name\" obrisan od strane administratora. Razlog brisanja: \"$message\"";

        $phpmailer->send();
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$phpmailer->ErrorInfo}";
    }
}
header("Location: all_events.php?archived=1");