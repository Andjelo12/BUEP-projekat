<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
$id_message=$_GET['id'];
$sql="DELETE FROM messages WHERE id_message=:id_message";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":id_message",$id_message,PDO::PARAM_INT);
$stmt->execute();
if ($stmt->rowCount() > 0) {
    header("Location:all_events.php?archived=0");
    $_SESSION['delete_invite']='Korisnik je uklonjen iz zahteva za poziv na događaj.';
}