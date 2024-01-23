<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
if (isset($_GET['ban'])){
    $sql="UPDATE users2 SET is_banned=:is_banned WHERE id_user=:id_user";
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam("is_banned",$_GET['ban'],PDO::PARAM_INT);
    $stmt->bindParam("id_user",$_GET['id_user'],PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()>0){
        if ($_GET['ban']==1){
            /*$data = array(
                'status'=>'Korisnik je blokiran!'
            );
            echo json_encode($data);*/
            $_SESSION['banned']="Korsnik je blokiran!";
            //header("Location: user_profiles.php");
        }
        else {
            /*$data = array(
                'status'=>'Korisnik je odblokiran!'
            );
            echo json_encode($data);*/
            $_SESSION['banned'] = "Korisnik je odblokiran";
            //header("Location: user_profiles.php");
        }
        //header("Location: user_profiles.php");
    }
}
