<?php 
include('config.php');
include 'functions_def.php';
$id=$_POST['id'];
$fname = ucfirst(strtolower(trim($_POST['fname'])));
$arriving=$_POST['arriving'];
$sql = "UPDATE invites SET name=:name, are_coming=:are_coming WHERE id=:id";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":name",$fname,PDO::PARAM_STR);
$stmt->bindValue(":are_coming",$arriving,PDO::PARAM_STR);
$stmt->bindValue(":id",$id,PDO::PARAM_INT);
$success=$stmt->execute();
if ($success == true) {
    $data = array(
        'status' => 'true',
    );

    echo json_encode($data);
}else{
    $data = array(
        'status' => 'false',

    );

    echo json_encode($data);
}

?>