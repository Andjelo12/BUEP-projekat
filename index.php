<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Početna</title>
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

        swiper-container {
            width: 100%;
            padding-top: 50px;
            padding-bottom: 50px;
        }

        swiper-slide {
            background-position: center;
            background-size: cover;
            width: 300px;
            height: 300px;
        }

        /*swiper-slide div*/
        .images{
            display: block;
            /*object-fit: fill;*/
            width: 100%;
            height: 100%;
        }
    </style>
</head>
<body class="bg-light ">
<?php
$error=$_SESSION['error_invite']??null;
if (isset($error)){
    echo "<script>
            $(window).on('load', function() {
                $('#msg').text('$error');
                $('#exampleModal').modal('show');
            });           
          </script>";
    unset($_SESSION['error_invite']);
}
$error=$_SESSION['error']??null;
if (isset($error)){
    echo "<script>
            $(window).on('load', function() {
                $('#msg').text('{$_SESSION['error']}');
                $('#exampleModal').modal('show');
            });           
          </script>";
    unset($_SESSION['error']);
}
$invite_message=$_SESSION['invite_message']??null;
if (isset($invite_message)) {
    echo "<script>
            $(window).on('load', function() {
                $('#msg').text('Zahtev uspešno poslat.');
                $('#exampleModal').modal('show');
            });           
          </script>";
    unset($_SESSION['invite_message']);
}
require_once 'header.php';
?>
<img src="images/photo-1492684223066-81342ee5ff30.jpg" style="width: 100%;
            height: 90vh;
            object-fit: cover;">
<!--<h1 style="position: absolute; top:40px; color: #54a399" class="display-1">Kreiranje događaja</h1>-->

<!--<nav class="navbar">-->
<!--    <img class='img-fluid w-100' src="images/free-stock-photos-12.jpeg" style="max-height: 100vh;" alt="free-stock-photos-12.jpeg" />-->
<!--    -->
<!---->
<!--</nav>-->
<swiper-container class="mySwiper" pagination="true" effect="coverflow" grab-cursor="true" centered-slides="true"
                  slides-per-view="auto" coverflow-effect-rotate="50" coverflow-effect-stretch="0" coverflow-effect-depth="100"
                  coverflow-effect-modifier="1" coverflow-effect-slide-shadows="true">
    <?php
    require_once 'functions_def.php';
    //$sql = "SELECT estates.id_estate,estates.description,estates.estate_type,estates.location,estates.price,estates.foto,estates.rent_period,estates.status, estates.approved FROM estates INNER JOIN user_estate ON estates.id_estate=user_estate.id_estate WHERE email=:email";
    $sql="SELECT * FROM event WHERE archived='no' AND is_blocked='free'";
    $query = $pdo -> prepare($sql);
    //$query->bindParam(':email',$_SESSION["username"], PDO::PARAM_STR);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    $cnt=1;
    if($query->rowCount() > 0)
    {
    foreach($results as $result)
    {
    ?>
    <swiper-slide>
        <div style="height: 100%;">
            <small style="text-align: center; display: block; max-height: 7%; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;"><?php echo $result->name?></small>
            <a href="event.php?event_no=<?php echo $result->id?>">
                <img src="images/events/<?php echo $result->foto ?>" style="width: 100%; height: 93%; object-fit: cover;" alt="<?php echo $result->foto ?>">
            </a>
        </div>
        <!--<div style="display: flex; flex-direction: column; height: 100%;">
            <span style="height: 20%;"><?php /*echo $result->name*/?></span>
            <a href="event.php?event_no=<?php /*echo $result->id*/?>" style="flex: 1; overflow: hidden;">
                <img src="images/<?php /*echo $result->foto */?>" style="width: 100%; height: 100%; object-fit: fill;" alt="Image" />
            </a>
        </div>-->
    </swiper-slide>
    <?php
    }
    } else {
    ?>
    <swiper-slide>
        <img src="images/no_events.jpg" style="width: 100%; height: 100%; object-fit: cover;" alt="no events">
    </swiper-slide>
    <swiper-slide>
        <img src="images/no_events.jpg" style="width: 100%; height: 100%; object-fit: cover;" alt="no events">
    </swiper-slide>
    <swiper-slide>
        <img src="images/no_events.jpg" style="width: 100%; height: 100%; object-fit: cover;" alt="no events">
    </swiper-slide>
    <?php }?>
</swiper-container>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-element-bundle.min.js"></script>
<?php
require_once 'footer.php';
?>
<!--Modal obaveštenja na index-noj stranici-->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Obaveštenje</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-9">
                        <span id="msg"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
