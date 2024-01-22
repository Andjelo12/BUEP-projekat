<?php 
include('connection.php');
$item=strtolower(trim($_POST['item']));
$link=$_POST['link'];
$id = $_POST['id'];
if (!filter_var($link, FILTER_VALIDATE_URL)) {
    $data = array(
        'status'=>'false'
    );
    echo json_encode($data);
    exit();
}
$sql = "UPDATE wish_list SET `item`='$item' , `link`= '$link' WHERE id='$id'";
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
