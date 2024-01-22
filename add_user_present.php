<?php 
include('connection.php');
$event_id=$_POST['id'];
$item=strtolower(trim($_POST['present']));
$link=$_POST['link'];
if (!filter_var($link, FILTER_VALIDATE_URL)) {
    $data = array(
        'status'=>'false'
    );
    echo json_encode($data);
    exit();
}
$sql = "INSERT INTO `wish_list` (`item`,`link`,`event_id`) values ('$item', '$link','$event_id')";
$query= mysqli_query($con,$sql);
$lastId = mysqli_insert_id($con);
if($query ==true)
{
    $data = array(
        'status'=>'true',
    );
    echo json_encode($data);
}
else
{
     $data = array(
        'status'=>'false',
    );
    echo json_encode($data);
}