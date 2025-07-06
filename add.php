<?php
session_start();
include('config.php');
require_once 'functions_def.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
$name=$_POST["name"]??null;
$description=$_POST["desc"]??null;
$date=$_POST["date"]??null;
$location=$_POST["location"]??null;
$created_by=$_POST["created_by"]??null;
$addOldInvites=$_POST['addOldInvites']??null;
if (!isset($name) || !isset($description) || !isset($date) || !isset($location) || !isset($created_by)){
    header("Location: add_event.php?e=1");
    exit();
}
$file_temp = $_FILES["img"]["tmp_name"];
$random=mt_rand(1,10000);
$file_name = "$random-".$_FILES['img']["name"];
$upload = "images/events/$file_name";
move_uploaded_file($file_temp, $upload);
if ($addOldInvites=='checked') {
    $sql = "SELECT id FROM event WHERE created_by = :created_by ORDER BY id DESC LIMIT 1;";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(":created_by", $_SESSION['username']);
    $stmt->execute();
    $result = $stmt->fetch();
    $lastInsertId = $result['id'];
    $sql="SELECT email, name FROM invites WHERE event_id=:event_id";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(":event_id",$lastInsertId,PDO::PARAM_INT);
    $stmt->execute();
    $results=$stmt->fetchAll();
}
$sql = "INSERT INTO event(name, description, date,location,foto,created_by) VALUES (:name, :description, :date, :location,:foto,:created_by)";
$query = $pdo -> prepare($sql);
$query->bindParam(':foto',$file_name,PDO::PARAM_STR);
$query->bindParam(':name',$name, PDO::PARAM_STR);
$query->bindParam(':description',$description, PDO::PARAM_STR);
$query->bindParam(':date',$date, PDO::PARAM_STR);
$query->bindParam(':location',$location, PDO::PARAM_STR);
$query->bindParam(':created_by',$created_by, PDO::PARAM_STR);
$query->execute();
$even_no=$pdo->lastInsertId();
if($query->rowCount() > 0)
{
    $_SESSION['message_added'] = 'Događaj uspešno kreiran!';
    if ($addOldInvites=='checked'){
        foreach ($results as $result){
            $token=createToken(10);
            $sql = "INSERT INTO invites(email, name, event_id, invite_code) values (:email, :name, :event_id, :invite_code)";
            $stmt = $pdo -> prepare($sql);
            $stmt->bindValue(":email",$result['email'],PDO::PARAM_STR);
            $stmt->bindValue(":name",$result['name'],PDO::PARAM_STR);
            $stmt->bindValue(":event_id",$even_no,PDO::PARAM_STR);
            $stmt->bindValue(":invite_code",$token,PDO::PARAM_STR);
            $stmt->execute();
            $phpmailer = new PHPMailer(true);
            $phpmailer->isSMTP();
            //$phpmailer->SMTPDebug=1;
            $phpmailer->Host = 'rsharp.stud.vts.su.ac.rs';
            $phpmailer->SMTPAuth = true;
            $phpmailer->Username   = 'rsharp';
            $phpmailer->Password   = 'HE9TiVYvUs1G9Xl';
            $phpmailer->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $phpmailer->Port = 587;

            $phpmailer->setFrom($_SESSION['username'], $_SESSION['firstname']);
            $phpmailer->addAddress("{$result['email']}");

            $phpmailer->isHTML(false);
            $phpmailer->Subject = 'Pozivnica za događaj';

            $sql="SELECT name,DATE(date) as date2, TIME(date) as time2 FROM event WHERE id=:id";
            $stmt=$GLOBALS['pdo']->prepare($sql);
            $stmt->bindValue(":id", $even_no, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $phpmailer->CharSet = 'UTF-8';
            $time=date("H:i",strtotime($result['time2']));
            $phpmailer->Body = "Pozdrav. Pravim novi događaj \"{$result['name']}\". Događaj će se održati {$result['date2']} u {$time}h. Da bi ste potvrdili vaš dolazak potrebno je da odete na <a href=". SITE ."inviteResponse.php?code=$token>link</a>";
            $phpmailer->AltBody = "Pozdrav. Pravim novi događaj \"{$result['name']}\". Događaj će se održati {$result['date2']} u {$result['time2']}h. Da bi ste potvrdili vaš dolazak potrebno je da odete na ".SITE."inviteResponse.php?code=$token";

            $phpmailer->send();
            $phpmailer=null;
        }
    }
    header("Location: all_events.php?archived=0");
}