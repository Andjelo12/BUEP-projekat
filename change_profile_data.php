<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
if (isset($_GET['id_user'])){
    $fName=ucfirst(strtolower(trim($_GET["firstname"])));
    $lName=ucfirst(strtolower(trim($_GET["lastname"])));
    $sql = "UPDATE users2 SET firstname=:firstname, lastname=:lastname WHERE id_user=:id_user";
    $query = $pdo -> prepare($sql);
    $query->bindParam(':id_user',$_GET["id_user"], PDO::PARAM_INT);
    $query->bindParam(':firstname',$fName, PDO::PARAM_STR);
    $query->bindParam(':lastname',$lName, PDO::PARAM_STR);
    $query->execute();
    if($query->rowCount() > 0) {
        $_SESSION['firstname']=$fName;
        $_SESSION['change_data'] = 'Podaci izmenjeni!';
    }
    //header("Location: edit_profile.php");
}