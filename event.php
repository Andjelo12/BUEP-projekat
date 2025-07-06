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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Info. o događaju</title>
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
    <!--<script src="js/scriptDtb.js"></script>-->
    <style>
        html,
        body {
            position: relative;
            height: 100%;
        }

        body {
            background: #eee;
            font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
            font-size: 14px;
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

        swiper-slide img {
            display: block;
            width: 100%;
        }
        .error {
            padding-left: 10px;
            color: #f00;
            display: block;
        }
    </style>
</head>
<body class="bg-light ">
<?php
require_once 'header.php';
?>
<br><br>
<?php
$error=$_GET['error']??null;
if (isset($error))
    echo "<script>
            $(window).on('load', function() {
                $('#errorModal').modal('show');
            });           
          </script>";
$event_no=$_GET['event_no']??null;
if (!isset($event_no))
    header("Location:index.php");
$sql_query="SELECT * FROM event WHERE id = :event_no";
$query= $pdo -> prepare($sql_query);
$query->bindParam(':event_no',$_GET['event_no']);
$query->execute();
$result=$query->fetch(PDO::FETCH_OBJ);
if ($query->rowCount()==0)
    header("Location: index.php");
if($query->rowCount() > 0) {
    $archived=$result->archived;
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-5">
                <img src="images/events/<?php echo $result->foto?>" class="img-fluid" width="100%" style="max-height: 500px" alt="Image"/>
            </div>
            <div class="gx-5 col-md-5">
                <div style="font-size: 12pt">
                    <h2><?php echo $result->name; ?></h2>
                    <hr>
                    Detalji događaja:<br><?php echo $result->description; ?>
                    <br><br>Datum održavanja:<br><?php echo $result->date; ?>
                    <br><br>Lokacija:<br><?php echo $result->location; ?>
                <br><br>
                <?php
                $name=$result->name;
                if(isset($decoded->data->username)){
                if ($result->created_by != $decoded->data->username && $decoded->data->is_admin=='No'){
                    $sql2="SELECT * FROM invites WHERE event_id=:event_id";
                    $stmt2=$pdo->prepare($sql2);
                    $stmt2->bindValue(":event_id", $event_no,PDO::PARAM_INT);
                    $stmt2->execute();
                    $results2=$stmt2->fetchAll();
                    $found=false;
                    foreach ($results2 as $result2){
                        if ($result2['email'] == $decoded->data->username) {
                            echo "<a href='' style='color: #0f0'>Već ste pozvani na događaj!</a>";
                            $found = true;
                            break;
                        }
                    }
                    if (!$found){
                        $sql3="SELECT * FROM messages WHERE id_event=:id_event";
                        $stmt3=$pdo->prepare($sql3);
                        $stmt3->bindValue(":id_event", $event_no,PDO::PARAM_INT);
                        $stmt3->execute();
                        $results3=$stmt3->fetchAll();
                        foreach ($results3 as $result3){
                            if ($result3['sender_email'] == $decoded->data->username) {
                                echo "<a href='' style='color: #0f0'>Vaš zahtev se trenutno obrađuje.</a>";
                                $found = true;
                                break;
                            }
                        }
                    }
                    if (!$found && $archived=='no'){
                ?>
                <a href="#" id="fl">Želeli bi ste da dođete na događaj?</a>
                <form action="requestForInvite.php" method="get" name="invite" id="inviteForm">
                    <div class="pt-3">
                        <input type="hidden" name="organiser" value="<?php echo $result->created_by ?>">
                        <input type="hidden" name="event_no" value="<?php echo $_GET['event_no'] ?>">
                        <input type="hidden" name="name" value="<?php echo $name ?>">
                        <input type="text" style="display: none" name="inviteName" id="inviteName" value="<?php echo $decoded->data->firstname ?>">
                        <input type="text" style="display: none" id="inviteEmail" name="inviteEmail" value="<?php echo $decoded->data->username?>">
                        <textarea class="form-control" style="margin-top: 5px" name="message" id="message" rows="3" placeholder="Unesite poruku organizatoru događaja"></textarea>
                        <small></small>
                        <button type="submit" class="btn btn-primary" style="margin-top: 5px">Pošaljite</button>
                    </div>
                </form>
                <?php }}} elseif (false/*isset($_SESSION['username']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin']=='No' && $archived=='no'*/) {?>
                    <a href="#" id="fl">Želeli bi ste da dođete na događaj?</a>
                    <form action="requestForInvite.php" method="get" name="invite" id="inviteForm">
                        <div class="pt-3">
                            <input type="hidden" name="organiser" value="<?php echo $result->created_by ?>">
                            <input type="hidden" name="event_no" value="<?php echo $_GET['event_no'] ?>">
                            <input type="hidden" name="name" value="<?php echo $name ?>">
                            <input type="text" class="form-control" id="inviteName" placeholder="Unesite vaše ime"
                                   name="inviteName">
                            <small></small>
                            <input type="text" class="form-control" style="margin-top: 5px" id="inviteEmail" placeholder="Unesite vašu e-mail adresu"
                                   name="inviteEmail">
                            <small></small>
                            <textarea class="form-control" style="margin-top: 5px" name="message" id="message" rows="3" placeholder="Unesite poruku organizatoru događaja"></textarea>
                            <small></small>
                            <button type="submit" class="btn btn-primary" style="margin-top: 5px">Pošaljite</button>
                        </div>
                    </form>
                <?php } else {
                    if ($archived=='no') {
                        require_once 'phpqrcode/qrlib.php';
//                        require_once "vendor/autoload.php";

                        //require_once 'vendor/nmiles/phpqrcode/src';
                        echo "Zainteresovani za događaj? Skenirajte kod!<br>";
                        QRcode::png(SITE . "QRinvite.php?event_no=" . $_GET['event_no'], 'images/temp/temp.png', QR_ECLEVEL_L, 5, 2);
//                    QRcode::png('https://www.google.com/', 'images/temp/temp.png', QR_ECLEVEL_L, 5, 2);
                        echo '<img style="margin-top: 15px" src="images/temp/temp.png" alt="qr_code">';
                    }
                }
                ?>
                </div>
            </div>
        </div>
    </div>
    <?php
}
?>
<?php
require_once 'footer.php';
?>
<div class="modal fade" id="errorModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Greška</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 row">
                    <div class="col-md-9">
                        Vaša e-mail adresa se već nalazi na listi zvanica.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
            </div>
        </div>
    </div>
</div>
<script>
    const fl = document.querySelector('#fl');
    let inviteForm = document.querySelector('#inviteForm');
    const isValidEmail = (email) => {
        let rex = /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/;
        return rex.test(email);
    }
    const showErrorMessage = (field, message) => {
        const error = field.nextElementSibling;
        error.classList.add('error');
        error.innerText = message;
    };

    const hideErrorMessage = (field) => {
        const error = field.nextElementSibling;
        error.classList.remove('error');
        error.innerText = '';
    }
    const isEmpty = value => value === '';
    inviteForm.style.display = 'none';
    if (fl !== null) {
        fl.addEventListener('click', function (e) {

            if (inviteForm.style.display !== "block") {
                inviteForm.style.display = "block";
                this.textContent = 'Sakrij formu.';
            } else {
                inviteForm.style.display = "none";
                this.textContent = 'Želeli bi ste da dođete na događaj?';
            }

            e.preventDefault();
        });
    }
    let validEmail=true;
    let respTxt;
    const inviteEmail = document.querySelector('#inviteEmail');
    inviteForm.addEventListener('submit', function (e) {
        e.preventDefault();

        let isValid = true;


        const message= document.querySelector('#message');
        const name = document.querySelector('#inviteName');

        if (isEmpty(inviteEmail.value.trim())) {
            showErrorMessage(inviteEmail, 'Polje za e-mail ne može biti prazno.');
            isValid = false;
        } else if (!isValidEmail(inviteEmail.value.trim())) {
            showErrorMessage(inviteEmail, 'E-mail je u pogrešnom formatu!');
            isValid = false;
        } else {
            hideErrorMessage(inviteEmail);
        }

        if (isEmpty(message.value)){
            showErrorMessage(message, 'Poruka ne može biti prazna');
            isValid=false
        } else {
            hideErrorMessage(message);
        }

        if (isEmpty(name.value)){
            showErrorMessage(name, 'Morate navest ime');
            isValid=false
        } else {
            hideErrorMessage(name);
        }

        if (!validEmail)
            showErrorMessage(inviteEmail, respTxt/*'Zahtev sa navedenim email-om je već poslat!'*/);

        if (isValid && validEmail) this.submit();
    });
</script>
<script>
    document.getElementById("inviteEmail").addEventListener("focusout", exist);
    const regEmail=document.getElementById('inviteEmail');
    function exist(){
        let dbParam = JSON.stringify({"email":regEmail.value,"id":<?php echo $event_no?>});
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onload = function() {
            respTxt = JSON.parse(this.responseText);
            if(respTxt!=='Ok') {
                showErrorMessage(regEmail, respTxt);
                validEmail=false;
            } else  {
                hideErrorMessage(regEmail);
                validEmail=true;
            }
        }
        xmlhttp.open("POST", "invite_check.php");
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("data=" + dbParam);
    }
</script>
</body>
</html>