<?php
session_start();
header("Content-Security-Policy: 
    default-src 'self'; 
    script-src 'self' https://code.jquery.com https://cdn.jsdelivr.net https://cdn.datatables.net; 
    style-src 'self' https://cdn.jsdelivr.net https://cdn.datatables.net; 
    font-src https://cdn.jsdelivr.net; 
    img-src 'self' data:; 
    object-src 'none'; 
    base-uri 'self'; 
    frame-ancestors 'none';
");
require_once 'config.php';
require_once 'functions_def.php';
require 'vendor/autoload.php';
use \Firebase\JWT\JWT;
use \Firebase\JWT\Key;
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
if (isset($_SESSION['jwt_token']) /*&& $_SESSION['is_admin']=='No'*/) {
    try {
        $decoded = JWT::decode($_SESSION['jwt_token'], new Key($_ENV['jwt_secret_key'], 'HS256'));
    }catch (\Firebase\JWT\ExpiredException){
        redirection('login.php');
    }
}
if (!isset($decoded) /*OR !isset($_SESSION['id_user']) OR !is_int($_SESSION['id_user'])*/) {
    redirection('login.php?l=0');
}
if (isset($decoded->data->is_admin) && $decoded->data->is_admin=='Yes')
    redirection('login.php?l=0');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Kreiraj događaj</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
    <script src="js/addEventChk.js"></script>
    <style>
        small{
            color: #f00;
        }
    </style>
<body class="bg-light ">
<?php
require_once 'header.php';
?><br>
<form method="post" action="add.php" enctype="multipart/form-data" id="addEvent" name="addEvent">
<div class="container">
    <input type="text" name="created_by" value="<?php echo $decoded->data->username; ?>" style="display: none">
    <div class="justify-content-center row" >
        <div style="width: 37rem">
    <div class="mb-3">
        <label for="name" class="form-label">Naziv dogđaja</label>
        <input type="text" class="form-control" name="name" id="name">
        <small></small>
    </div>
    <div class="mb-3">
        <label for="description" class="form-label">Opis događaja</label>
        <textarea class="form-control" name="desc" id="description" rows="3"></textarea>
        <small></small>
    </div>
    <div class="mb-3">
        <label for="date" class="form-label">Datum događaja</label>
        <input type="datetime-local" class="form-control" name="date" id="date">
        <small></small>
    </div>
    <div class="mb-3">
        <label for="location" class="form-label">Lokacija:</label>
        <input type="text" class="form-control" name="location" id="location">
        <small></small>
    </div>
    <div class="mb-3">
        <label for="img" class="form-label">Slika:</label>
        <input type="file" class="form-control" name="img" id="img" accept="image/*">
        <small></small>
    </div>
    <?php
    $sql="SELECT id FROM event WHERE created_by = :created_by ORDER BY id DESC LIMIT 1;";
    $stmt=$pdo->prepare($sql);
    $stmt->bindValue(":created_by",$_SESSION['username']);
    $stmt->execute();
    $result=$stmt->fetch();
    if ($stmt->rowCount()>0) {
        $id = $result['id'];
        /*$sql='SELECT COUNT(invites.event_id) as num
            FROM event
      INNER JOIN invites
             ON event.id=invites.event_id
            WHERE created_by=:created_by';*/
        $sql2="SELECT COUNT(event_id) as num FROM invites WHERE event_id=:event_id";
        $stmt2=$pdo->prepare($sql2);
        $stmt2->bindValue(":event_id",$id);
        $stmt2->execute();
        $result2=$stmt2->fetch();
        if($result2['num']>0) {
    ?>
    <div class="mb-3">
        <input type="hidden" name="">
        <input type="checkbox" class="form-check-inline" name="addOldInvites" id="oldInvites" value="checked">
        <label for="oldInvites" class="form-check-label">Dodaj zvanice sa prethodnog događaja</label>
    </div>
    <?php
        }
    }
        ?>
    <div class="col-auto">
        <input type="submit" class="btn btn-primary mb-3" value="kreiraj događaj">
    </div>
        </div>
        </div>
</div>
</form>
<?php
$e = $_GET['e'] ?? "";

if ($e == 1) {
    echo "<p>Popunite sve vrednosti!</p>";
}

?>
<?php
require_once 'footer.php';
?>
</body>
</html>
