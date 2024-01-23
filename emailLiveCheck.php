<?php
header("Content-Type: application/json; charset=UTF-8");
$obj = json_decode($_POST["x"], false);

$conn = new mysqli("localhost", "first", "ZADcO14NsZMPzeU", "first");
$stmt = $conn->prepare("SELECT email FROM users2 WHERE email = ?");
$stmt->bind_param("s", $obj->email);
$stmt->execute();
$stmt->store_result();
if($stmt->num_rows() > 0){
    $rez=array('E-mail je već u upotrebi');
    echo json_encode($rez);
}else {
    $rez=array('');
    echo json_encode($rez);
}