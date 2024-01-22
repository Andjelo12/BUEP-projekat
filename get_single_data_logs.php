<?php include('connection.php');
$id_log = $_POST['id'];
$sql = "SELECT * FROM detects WHERE id_log='$id_log' LIMIT 1";
$query = mysqli_query($con,$sql);
$row = mysqli_fetch_assoc($query);
echo json_encode($row);
?>
