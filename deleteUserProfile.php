<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$email=$_POST['email'];
$sql="SELECT id_user FROM users2 WHERE email=:email";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":email",$email,PDO::PARAM_STR);
$stmt->execute();
$result=$stmt->fetch();
$id_user=$result['id_user'];
$sql="DELETE FROM users2 WHERE id_user=:id_user";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":id_user", $id_user,PDO::PARAM_STR);
$stmt->execute();
$phpmailer = new PHPMailer(true);
$phpmailer->isSMTP();
$phpmailer->Host = 'rsharp.stud.vts.su.ac.rs';
$phpmailer->SMTPAuth = true;
$phpmailer->Port = 587;
$phpmailer->Username = 'rsharp';
$phpmailer->Password = 'HE9TiVYvUs1G9Xl';


$phpmailer->setFrom("rsharp@rsharp.stud.vts.su.ac.rs",'Admin');
$phpmailer->addAddress("$email");

$phpmailer->isHTML(true);
$phpmailer->CharSet = 'UTF-8';
$phpmailer->Subject = "Obaveštenje o brisanju profila";
$phpmailer->Body = "Obaveštavamo vas da je vaš profile uspešno obrisan. Nadamo se da je ovo samo privremeno, i da vam je korišćenjem našeg sajta rešen problem pozivanja ljudi na vaš događaj.";
$phpmailer->AltBody = "Obaveštavamo vas da je vaš profile uspešno obrisan. Nadamo se da je ovo samo privremeno, i da vam je korišćenjem našeg sajta rešen problem pozivanja ljudi na vaš događaj.";

$phpmailer->send();
$_SESSION = [];
$_SESSION['error']='Vaš profil je uspešno obrisan!';