<?php
session_start();
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
if (isset($decoded->data->is_admin) && $decoded->data->is_admin=='No'){
    redirection('login.php?l=0');
}
/*if (isset($_GET['ban'])){
    $sql="UPDATE users2 SET is_banned=:is_banned WHERE id_user=:id_user";
    $stmt=$pdo->prepare($sql);
    $stmt->bindParam("is_banned",$_GET['ban'],PDO::PARAM_INT);
    $stmt->bindParam("id_user",$_GET['id_user'],PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount()>0){
        if ($_GET['ban']==1){
            $_SESSION['banned']="Korsnik je blokiran!";
            //header("Location: user_profiles.php");
        }
        else {
            $_SESSION['banned'] = "Korisnik je odblokiran";
            //header("Location: user_profiles.php");
        }
        //header("Location: user_profiles.php");
    }
}*/
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
<script type="text/javascript">
    $(document).ready(function () {
        $('button[name="ban"]').click(function () {
            var idUser = $(this).data('id');
            var banValue = $(this).val();
            $.ajax({
                type: 'GET',
                url: 'user_profiles_change.php',
                data: { id_user: idUser, ban: banValue },
                success: function (response) {
                    location.reload();
                    /*var json = JSON.parse(response);
                    var status = json.status;
                    $('#message').text(status);
                    $('#notificationModal').modal('show');*/
                }
            });
        });
    });
</script>
<?php
if (isset($_SESSION['banned'])) {
    echo "<script>
            $(window).on('load', function() {
                $('#message').text('" . $_SESSION['banned'] . "');
                $('#notificationModal').modal('show');
            });           
          </script>";
    unset($_SESSION['banned']);
}
require_once 'header.php';
?>
    <div class="container py-4">
<div class="row row-cols-1 row-cols-md-3 mb-3 text-center">
    <?php
    $sql="SELECT * FROM users2 WHERE is_admin='No'";
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
<!--                <form action="" method="get">-->
<!--                    <input type="hidden" name="id_user" value="--><?php //echo $result->id_user?><!--">-->
                    <button type="button" class="w-100 btn btn-lg btn-<?php echo $btn?>" name="ban" data-id="<?php echo $result->id_user;?>" value="<?php echo $ban;?>"><?php echo $text;?></button>
<!--                </form>-->
            </div>
        </div>
    </div>
    <?php
        }
    ?>
</div>
    </div>
<div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Obaveštenje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <span id="message"></span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
            </div>
        </div>
    </div>
</div>
<?php
require_once 'footer.php';
?>
</body>
</html>
