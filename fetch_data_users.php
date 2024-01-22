<?php
include('connection.php');
$output = array();
$sql = "SELECT * FROM detects 
    INNER JOIN user_detects
            ON detects.user_id=user_detects.id_user_detects";

$totalQuery = mysqli_query($con,$sql);
$total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
	0 => 'id',
	1 => 'ip_address',
	2 => 'email',
	3 => 'user_details',
    4 => 'status',
    5 => 'date_time'
);
if(isset($_POST['search']['value']))
{
	$search_value = $_POST['search']['value'];
	$sql .= " WHERE ip_address like '%".$search_value."%'";
	$sql .= " OR email like '%".$search_value."%'";
	$sql .= " OR user_details like '%".$search_value."%'";
	$sql .= " OR status like '%".$search_value."%'";
	$sql .= " OR date_time like '%".$search_value."%'";
}

if(isset($_POST['order']))
{
	$column_name = $_POST['order'][0]['column'];
	$order = $_POST['order'][0]['dir'];
	$sql .= " ORDER BY $columns[$column_name] $order";
}
else
{
	$sql .= " ORDER BY id_log desc";
}

if($_POST['length'] != -1)
{
	$start = $_POST['start'];
    $length = $_POST['length'];
	$sql .= " LIMIT  ".$start.", ".$length;
}	

$query = mysqli_query($con,$sql);
$count_rows = mysqli_num_rows($query);
$data = array();
$enumerator=1;
while($row = mysqli_fetch_assoc($query))
{
    $sub_array = array();
    $sub_array[] = $enumerator++;
	$sub_array[] = $row['ip_address'];
	$sub_array[] = $row['email'];
    if ($row['user_details']==null){
        $sub_array[] = "ne";
    } else {
        $sub_array[] = "da";
    }
    switch ($row['status']){
        case 'success':
            $sub_array[] = '<span style="color: #0f0">uspešna prijavljen</span>';
            break;
        case 'banned':
            $sub_array[] = '<span style="color: #f00">blokirani korisnik</span>';
            break;
        case 'wrong data':
            $sub_array[] = '<span style="color: #f00">pogrešnan unos</span>';
            break;
        case 'register':
            $sub_array[] = '<span style="color: #00f">registracija novog korisnika</span>';
            break;
        case 'register with same email':
            $sub_array[] = '<span style="color: #f00">pokušaj registracija sa istim email-om</span>';
            break;
    }
    $sub_array[] = $row['date_time'];
	$sub_array[] = '<a href="#" data-id="'.$row['id_log'].'" data-bs-toggle="modal" data-bs-target="#exampleModal" class="btn btn-info btn-sm detailsbtn" >Detalji</a>';
	$data[] = $sub_array;
}

$output = array(
    'data'=>$data,
	'draw'=> intval($_POST['draw']),
	'recordsTotal' =>$count_rows ,
	'recordsFiltered'=>   $total_all_rows,

);
echo  json_encode($output);
