<?php
include('connection.php');
$output= array();
$sql = "SELECT * FROM tokens WHERE 1";

$totalQuery = mysqli_query($con,$sql);
$total_all_rows = mysqli_num_rows($totalQuery);

$columns = array(
    0 => 'id_token',
    1 => 'token',
    2 => 'email',
    3 => 'account_type',
    4 => 'blocked',
    5 => 'calls_no',
    6 => 'tokens_no'
);
if(isset($_POST['search']['value']))
{
    $search_value = $_POST['search']['value'];
    $sql .= " AND (email like '%".$search_value."%'";
    $sql .= " OR token like '%".$search_value."%'";
    $sql .= " OR account_type like '%".$search_value."%')";
}

if(isset($_POST['order']))
{
    $column_name = $_POST['order'][0]['column'];
    $order = $_POST['order'][0]['dir'];
    $sql .= " ORDER BY $columns[$column_name] $order";
}
else
{
    $sql .= " ORDER BY id_token asc";
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
    preg_match('/Bearer\s(\S+)/', $row['token'], $matches);
    $sub_array[] = $matches[1];
    $sub_array[] = $row['email'];
    switch ($row['account_type']) {
        case 'bronze':
            $prevod='bronzani';
            break;
        case 'silver':
            $prevod='srebrni';
            break;
        case 'gold':
            $prevod='zlatni';
            break;
        case 'platinum':
            $prevod='platinasti';
            break;
    }
    $sub_array[] =$prevod;
    $actionText = $row['blocked'] == 0 ? 'Blokiraj' : 'Odblokiraj';
    $actionClass = $row['blocked'] == 0 ? 'block' : 'unblock';
    $btn_type = $row['blocked'] == 0 ? 'btn-danger' : 'btn-success';
    $sub_array[] = $row['tokens_no']-$row['calls_no'];
    $sub_array[] = '<a href="javascript:void(0);" data-email="'.$row['email'].'" data-action="'.$actionClass.'" class="btn '.$btn_type.' btn-sm toggleBlockBtn">'.$actionText.'</a>';
    $data[] = $sub_array;
}

$output = array(
    'data'=>$data,
    'draw'=> intval($_POST['draw']),
    'recordsTotal' =>$count_rows ,
    'recordsFiltered'=>   $total_all_rows,

);
echo  json_encode($output);
