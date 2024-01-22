<?php
require_once 'config.php';
require_once 'functions_def.php';
header("Content-Type: application/json; charset=UTF-8");
$data = json_decode($_POST['data'], true);

$sql="SELECT sender_email FROM messages WHERE id_event=:id_event && sender_email=:sender_email";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":sender_email",$data['email'], PDO::PARAM_STR);
$stmt->bindValue(":id_event",$data['id'], PDO::PARAM_INT);
$stmt->execute();
$check1=$stmt->rowCount() > 0;
$sql2="SELECT email FROM invites WHERE event_id=:event_id && email=:email";
$stmt2=$pdo->prepare($sql2);
$stmt2->bindValue(":email",$data['email'], PDO::PARAM_STR);
$stmt2->bindValue(":event_id",$data['id'], PDO::PARAM_INT);
$stmt2->execute();
$check2=$stmt2->rowCount() > 0;
//$result=$stmt->fetchAll();

/*$conn = new mysqli("localhost", "tim", "Xj3W3WLgQeQxOp6", "tim");
$stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
$stmt->bind_param("s", $obj->email);
$stmt->execute();
$stmt->store_result();*/
if($check2){
    echo json_encode('Već ste pozvani na događaj!');
}else if ($check1){
    echo json_encode('Zahtev sa navedenim email-om je već poslat!');
} else
    echo json_encode("Ok");