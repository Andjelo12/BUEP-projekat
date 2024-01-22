<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
if (!isset($_SESSION['username']) OR !isset($_SESSION['id_user']) OR !is_int($_SESSION['id_user'])) {
    redirection('login.php?l=0');
}
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']=='No'){
    redirection('login.php?l=0');
}
if (isset($_GET['ban'])){
    $sql="UPDATE users SET is_banned=:is_banned WHERE id_user=:id_user";
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam("is_banned",$_GET['ban'],PDO::PARAM_INT);
    $stmt->bindParam("id_user",$_GET['id_user'],PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()>0)
        echo "<script>alert('Status changed successfully')</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Korisnički nalozi</title>
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
    <script src="js/scriptDtb.js"></script>
    <script src="js/addEventChk.js"></script>
    <style>
        html,
        body {
            position: relative;
            height: 100%;
        }

        body {
            /*background: #eee;*/
            font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
            /*font-size: 14px;*/
            color: #000;
            margin: 0;
            padding: 0;
        }
        small{
            color: #f00;
        }
    </style>
<body>
<?php
require_once 'header.php';
?>
    <div class="container py-4">
<div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
    <?php
    require_once 'config.php';
    require_once 'functions_def.php';
    $sql="SELECT * FROM users WHERE is_admin='No'";
    $stmt=$pdo->prepare($sql);
    $stmt->execute();
    $results=$stmt->fetchAll(PDO::FETCH_OBJ);
    if ($stmt->rowCount()>0)
        foreach ($results as $result){
            $type=$result->is_banned==0 ? 'success' : 'danger';
            $btn=$result->is_banned==0 ? 'danger' : 'success';
            $text=$result->is_banned==0 ? 'Blokiraj' : 'Deblokiraj';
            $status=$result->is_banned==0 ? 'Nije blokiran' : 'Blokiran';
            $ban=$result->is_banned==0 ? 1 : 0;
    ?>
    <div class="col">
        <div class="card mb-4 rounded-3 shadow-sm border border-<?php echo $type?>">
            <div class="card-header py-3 text-white bg-<?php echo $type?> border-<?php echo $type?>">
                <h4 class="my-0 fw-normal"><?php echo $status?></h4>
            </div>
            <div class="card-body">
                <h5 class="card-title pricing-card-title"><?php echo $result->email?></h5>
                <ul class="list-unstyled mt-3 mb-4" style="text-align: left">
                    <li>Ime: <?php echo $result->firstname?></li>
                    <li></li>
                    <li>Prezime: <?php echo $result->lastname?></li>
                    <li></li>
                </ul>
                <form action="#" method="get">
                    <input type="hidden" name="id_user" value="<?php echo $result->id_user?>">
                    <button type="submit" class="w-100 btn btn-lg btn-<?php echo $btn?>" name="ban" value="<?php echo $ban?>"><?php echo $text?></button>
                </form>
            </div>
        </div>
    </div>
    <?php
        }
    ?>
</div>
    </div>
<?php
require_once 'footer.php';
?>
</body>
</html>
