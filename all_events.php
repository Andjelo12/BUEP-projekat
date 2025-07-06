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
$archived=$_GET['archived']??null;
if ($archived==0){
    $sql3 = "SELECT * FROM event WHERE archived='No'";
    $query3 = $pdo -> prepare($sql3);
    $query3->execute();
    $results3=$query3->fetchAll(PDO::FETCH_OBJ);
    if($query3->rowCount() > 0) {
        foreach($results3 as $result3) {
            $time1 = strtotime($result3->date);
            $id = $result3->id;
            if ($time1 < time()) {
                $sql2 = "UPDATE event SET archived='yes' WHERE id=:id";
                $query2 = $pdo->prepare($sql2);
                $query2->bindParam(':id', $id, PDO::PARAM_INT);
                $query2->execute();
            }
        }
    }
}
/*if ($archived!=0){
    $sql3 = "SELECT * FROM event WHERE archived='Yes'";
    $query3 = $pdo -> prepare($sql3);
    $query3->execute();
    $results3=$query3->fetchAll(PDO::FETCH_OBJ);
    if($query3->rowCount() > 0) {
        foreach($results3 as $result3) {
            $time1 = strtotime($result3->date);
            $id = $result3->id;
            if ($time1 > time()) {
                $sql2 = "UPDATE event SET archived='no' WHERE id=:id";
                $query2 = $pdo->prepare($sql2);
                $query2->bindParam(':id', $id, PDO::PARAM_INT);
                $query2->execute();
            }
        }
    }
}*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Događaji</title>
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
    <style>
        html,
        body {
            position: relative;
            height: 100%;
        }

        body {
            background: #eee;
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
<body class="bg-light ">

<script>
    function deleteEvent(id,foto_name){
        $('#deleteEventConfirmModal').modal('show');

        $('#confirm-delete').off('click').on('click', function () {
            window.location.href = 'delete_event.php?id=' + id + '&foto_name=' + foto_name;
        });
        /*if (confirm("Sigurno želite da izbrišete ovaj događaj"))
            window.location.href='delete_event.php?id='+id+'&foto_name='+foto_name;*/
    }
    function deleteEventAdmin(id,foto_name){
        $('#deleteEventConfirmModalAdmin').modal('show');

        // Set the event details in the modal
        $('#confirm-delete-admin').off('click').on('click', function () {
            // Get additional data
            var additionalData = $('#deletingReason').val();

            // Check if additionalData is empty
            if (additionalData.trim() === '') {
                $('#deletingReasonError').text('Navedite povratnu informaciju');
            } else {
                // Clear previous error message
                $('#deletingReasonError').text('');

                // Proceed with deletion
                window.location.href = 'delete_event.php?id=' + id + '&foto_name=' + foto_name + '&message=' + additionalData;
            }
        });
        /*if (confirm("Sigurno želite da izbrišete ovaj događaj"))
            window.location.href='delete_event.php?id='+id+'&foto_name='+foto_name;*/
    }
    function showArchiveConfirmation(id) {
        $('#archiveEventConfirmModal').modal('show');

        $('#confirm-archive').off('click').on('click', function () {
            // Handle the confirmation logic here
            window.location.href='archive_event.php?id='+id;
        });
    }
    function blockEvent(id,archived){
        window.location.href='block_event.php?id='+id+"&archived="+archived;
    }
    function unblockEvent(id,archived){
        window.location.href='unblock_event.php?id='+id+"&archived="+archived;
    }
    function checkMessage(id){
        window.location.href='user_requests.php?id='+id+'&archived=<?php echo $_GET['archived']?>';
    }
</script>
<?php
require_once 'header.php';
?>
<br>
<?php
if ($archived==0){
?>
    <h1 style="text-align: center">Događaji</h1>
<?php
} else {
?>
    <h1 style="text-align: center">Arhivirani događaji</h1>
<?php
}
?>
<div class="album py-5 bg-light">
    <div class="container">
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php
            if (isset($_SESSION['message_added'])){
                echo "<script>
            $(window).on('load', function() {
                $('#message').text('".$_SESSION['message_added']."');
                $('#notificationModal').modal('show');
            });           
          </script>";
                unset($_SESSION['message_added']);
            }
            if (isset($_SESSION['message_change'])){
                echo "<script>
            $(window).on('load', function() {
                $('#message').text('".$_SESSION['message_change']."');
                $('#notificationModal').modal('show');
            });           
          </script>";
                unset($_SESSION['message_change']);
            }
            if(isset($_SESSION['message'])) {
                echo "<script>
            $(window).on('load', function() {
                $('#message').text('".$_SESSION['message']."');
                $('#notificationModal').modal('show');
            });           
          </script>";
                unset($_SESSION['message']);
            }
            if(isset($_SESSION['delete_invite'])) {
                echo "<script>
            $(window).on('load', function() {
                $('#message').text('{$_SESSION['delete_invite']}');
                $('#notificationModal').modal('show');
            });           
          </script>";
                unset($_SESSION['delete_invite']);
            }
            require_once 'functions_def.php';
            //$sql = "SELECT estates.id_estate,estates.description,estates.estate_type,estates.location,estates.price,estates.foto,estates.rent_period,estates.status, estates.approved FROM estates INNER JOIN user_estate ON estates.id_estate=user_estate.id_estate WHERE email=:email";
            if($decoded->data->is_admin=='Yes') {
                if ($archived==0)
                    $sql = "SELECT * FROM event WHERE archived='No'";
                else
                    $sql = "SELECT * FROM event WHERE archived='Yes'";
                $query = $pdo -> prepare($sql);
            } else {
                if($archived==0) {
                    $sql = "SELECT * FROM event WHERE created_by=:created_by and archived='No'";
                    $query = $pdo->prepare($sql);
                    $query->bindParam(':created_by', $decoded->data->username, PDO::PARAM_STR);
                } else {
                    $sql = "SELECT * FROM event WHERE created_by=:created_by and archived='Yes'";
                    $query = $pdo->prepare($sql);
                    $query->bindParam(':created_by', $decoded->data->username, PDO::PARAM_STR);
                }
            }
            $query->execute();
            $results=$query->fetchAll(PDO::FETCH_OBJ);
            if($query->rowCount() > 0)
            {
                foreach($results as $result)
                {
                    /*$time1=strtotime($result->date);
                    $id=$result->id;
                    if ($time1<time()){
                        $sql2 = "UPDATE event SET archived='yes' WHERE id=:id";
                        $query2 = $pdo->prepare($sql2);
                        $query2->bindParam(':id', $id, PDO::PARAM_INT);
                        $query2->execute();
                    }*/
            ?>
            <div class="col">
                <div class="card shadow-sm">
                    <?php
                    $sql3="SELECT count(id_event) as cnt FROM messages WHERE id_event=:id_event AND status='unreplied'";
                    $stmt3 = $pdo->prepare($sql3);
                    $stmt3->bindValue(":id_event",$result->id,PDO::PARAM_INT);
                    $stmt3->execute();
                    $result3=$stmt3->fetch();
                    if ($result3['cnt']>0 && $decoded->data->is_admin=='No'){
                    ?>
                    <div style="position: relative;text-align: center;">
                        <div style="position: absolute; top: 2px; right: 2px;"><button class="btn btn-dark" type="button" onclick="checkMessage(<?php echo $result->id?>)"><i class="bi bi-chat-square-text"></i> <sup><?php echo $result3['cnt']?></sup></button></div>
                    </div>
                    <?php }?>
                    <img height="320px" src="images/events/<?php echo $result->foto?>"  alt="Image" class="card-img-top rounded-top"/>
                    <div class="card-body">
                        <p class="card-text"><?php echo $result->description ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-success <?php if ($archived!=0) echo 'disabled';?>" onclick="window.location.href='edit_event.php?id=<?php echo $result->id; ?>&archived=<?php echo $archived; ?>'">Izmeni</button>
                                <?php if ($result->archived=='yes'){
                                        if (isset($decoded->data->is_admin) && $decoded->data->is_admin=='No'){
                                    ?>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteEvent(<?php echo $result->id; ?>,'<?php echo $result->foto; ?>')">Obriši</button>
                                <?php } else if (isset($decoded->data->is_admin) && $decoded->data->is_admin=='Yes') { ?>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteEventAdmin(<?php echo $result->id; ?>,'<?php echo $result->foto; ?>')">Obriši</button>
                                <?php } } if ($result->archived=='no'){?>
                                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="showArchiveConfirmation(<?php echo $result->id; ?>)">Arhiviraj</button>
                                <?php }?>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-dark" onclick="window.location.href='invites.php?id=<?php echo $result->id;?>&archived=<?php echo $result->archived;?>'">Pozivnice</button>
                                <button type="button" class="btn btn-sm btn-outline-dark" onclick="window.location.href='wish_list.php?id=<?php echo $result->id; ?>&archived=<?php echo $result->archived;?>'">Pokloni</button>
                            </div>
                        </div>
                        <?php
                        if ($decoded->data->is_admin=='Yes'){
                        ?>
                        <div class="d-flex justify-content-between align-items-center" style="margin-top: 10px">
                            <div class="btn-group">
                                <?php
                                $subclass='';
                                if ($result->archived=='yes')
                                    $subclass='disabled';
                                if ($result->is_blocked=='free'){
                                ?>
                                    <button type="button" class="btn btn-sm btn-outline-danger <?php echo $subclass;?>" onclick="blockEvent(<?php echo $result->id; ?>,<?php echo $archived;?>)">Blokiraj događaj</button>
                                <?php
                                } else {
                                ?>
                                    <button type="button" class="btn btn-sm btn-outline-success <?php echo $subclass;?>" onclick="unblockEvent(<?php echo $result->id; ?>, <?php echo $archived;?>)">Odblokiraj događaj</button>
                                <?php
                                }
                                ?>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-outline-dark" onclick="window.location.href='event.php?event_no=<?php echo $result->id; ?>'">Detlji događaja</button>
                            </div>
                        </div>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<?php
require_once 'footer.php';
?>
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
<div class="modal fade" id="deleteEventConfirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Potvrda brisanja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Da li ste sigurni da želite da izbrišete ovaj događaj?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirm-delete">Da</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ne</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteEventConfirmModalAdmin" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Potvrda brisanja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <textarea class="form-control" id="deletingReason" placeholder="Navedite razlog brisanja"></textarea>
                <small id="deletingReasonError" class="form-text text-danger" style="margin-left: 10px"></small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirm-delete-admin">Obriši</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Otkaži</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="archiveEventConfirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Potvrda arhiviranja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Da li ste sigurni da želite da arhivirate ovaj događaj?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirm-archive">Da</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ne</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
