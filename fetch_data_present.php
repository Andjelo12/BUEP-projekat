<?php
include('connection.php');
$output= array();
$event_id=$_POST['id'];
$archived=$_POST['archived'];
$subclass='';
if ($archived=='yes')
    $subclass='disabled';
$sql = "SELECT * FROM wish_list WHERE `event_id`='$event_id'";

$totalQuery = mysqli_query($con,$sql);
$total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
    0 => 'id',
    1 => 'item',
    2 => 'user_buying_present',
    3 => 'event_id'
);

if(isset($_POST['search']['value']))
{
    $search_value = $_POST['search']['value'];
    $sql .= " AND (item like '%".$search_value."%'";
    $sql .= " OR link like '%".$search_value."%'";
    $sql .= " OR user_buying_present like '%".$search_value."%')";
}

if(isset($_POST['order']))
{
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY $columns[$column_name] $order";
}
else
{
    $sql .= " ORDER BY id asc";
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
    $sub_array[] = '<a href="'.$row['link'].'">'.$row['item'].'</a>';
    $sub_array[] = $row['user_buying_present'];
    $sub_array[] = '<a href="#" data-id="'.$row['id'].'"  class="btn btn-info btn-sm editbtn '.$subclass.'">Izmeni</a>  <a href="javascript:void();" data-id="'.$row['id'].'"  class="btn btn-danger btn-sm deleteBtn '.$subclass.'">Obriši</a>';
    $data[] = $sub_array;
}

$output = array(
    'data'=>$data,
    'draw'=> intval($_POST['draw']),
    'recordsTotal' =>$count_rows ,
    'recordsFiltered'=>   $total_all_rows,

);
echo  json_encode($output);
