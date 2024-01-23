<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
$id=$_GET['id'];
$code=$_GET['code'];
$action=$_GET['action'];
$wishListItem=$_GET['wishListItem']??null;
$name=$_GET['name'];
$changes=false;
if (isset($action) ){
    $sql = "UPDATE invites SET are_coming=:are_coming WHERE id=:id";
    $stmt = $GLOBALS['pdo']->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_STR);
    $stmt->bindParam(":are_coming", $action, PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0)
        $changes = true;
}
if(isset($wishListItem)){
    $sql="SELECT id FROM wish_list WHERE user_buying_present=:user_buying_present AND is_selected='yes'";
    $stmt=$GLOBALS['pdo']->prepare($sql);
    $stmt->bindParam(":user_buying_present",$name,PDO::PARAM_STR);
    $stmt->execute();
    $result=$stmt->fetch();
    if ($stmt->rowCount()>0){
        $id_remove=$result['id'];
        $sql="UPDATE wish_list SET user_buying_present=null, is_selected='no' WHERE id=:id";
        $stmt=$GLOBALS['pdo']->prepare($sql);
        $stmt->bindParam(":id",$id_remove,PDO::PARAM_STR);
        $stmt->execute();
    }

    $sql="UPDATE wish_list SET user_buying_present=:user_buying_present, is_selected='yes' WHERE id=:id";
    $stmt=$GLOBALS['pdo']->prepare($sql);
    $stmt->bindParam(":user_buying_present",$name,PDO::PARAM_STR);
    $stmt->bindParam(":id",$wishListItem,PDO::PARAM_STR);
    $stmt->execute();
    if ($stmt->rowCount() > 0)
        $changes=true;
}
if ($changes)
    $_SESSION['invite_status']='Vaš izbor je uspešno sačuvan!';
header("Location:invite_dashboard.php?code=$code");
exit();