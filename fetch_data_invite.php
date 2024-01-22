<?php
include('connection.php');
$output= array();
$email=$_POST['email'];
$sql = "SELECT
    e.name AS event_name,
    e.date AS date,
    COALESCE(wl.item, '') AS item,
    COALESCE(wl.link, '') AS link,
    i.are_coming AS are_coming,
    i.invite_code AS invite_code
FROM
    event e
LEFT JOIN
    invites i ON e.id = i.event_id
LEFT JOIN
    wish_list wl ON e.id = wl.event_id AND wl.user_buying_present = '$email'
WHERE
    i.email = '$email'";

$totalQuery = mysqli_query($con,$sql);
$total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
	0 => 'event_name',
    1 => 'event_date',
	2 => 'status',
	3 => 'gift_link',
    4 => 'change_status'
);
if(isset($_POST['search']['value']))
{
	$search_value = $_POST['search']['value'];
	$sql .= " AND (e.name like '%".$search_value."%'";
	$sql .= " OR wl.item like '%".$search_value."%'";
	$sql .= " OR i.are_coming like '%".$search_value."%'";
	$sql .= " OR e.date like '%".$search_value."%')";
}

if(isset($_POST['order']))
{
	$column_name = $_POST['order'][0]['column'];
	$order = $_POST['order'][0]['dir'];
	$sql .= " ORDER BY $columns[$column_name] $order";
}
else
{
	$sql .= " ORDER BY date";
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
while($row = mysqli_fetch_assoc($query))
{
    $sub_array = array();
    $sub_array[] = $row['event_name'];
	$sub_array[] = $row['date'];
    switch ($row['are_coming']){
        case 'Yes':
            $prevod='dolazim';
            break;
        case 'No':
            $prevod='ne dolazim';
            break;
        case 'Maybe':
            $prevod='možda dolazim';
            break;
        case 'Didn\'t decided':
            $prevod='nisam odlučio';
            break;
    }
	$sub_array[] =$prevod;
    $sub_array[] = '<a href="'.$row['link'].'">'.$row['item'].'</a>';
	$sub_array[] = '<a href="inviteResponse.php?code='.$row['invite_code'].'" data-id="'.$row['invite_code'].'"  class="btn btn-info btn-sm editbtn" >Izmeni</a>';//javascript:void();
	$data[] = $sub_array;
}

$output = array(
    'data'=>$data,
	'draw'=> intval($_POST['draw']),
	'recordsTotal' =>$count_rows ,
	'recordsFiltered'=>   $total_all_rows,
);
echo  json_encode($output);
