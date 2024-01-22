<?php
require_once 'functions_def.php';
$id=$_GET['id']??null;
$sql="UPDATE event SET is_blocked='free' WHERE id=:id";
$stmt=$GLOBALS['pdo']->prepare($sql);
$stmt->bindParam(":id",$id,PDO::PARAM_STR);
$stmt->execute();
header("Location: all_events.php?archived={$_GET['archived']}");