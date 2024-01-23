<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
$event_no=$_GET['event_no']??null;
if (!isset($event_no)){
    header("Location: index.php");
    exit;
}
$sql="SELECT * FROM event WHERE id = :event_no AND archived='no'";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":event_no",$event_no,PDO::PARAM_INT);
$stmt->execute();
$result=$stmt->fetch();
if ($stmt->rowCount()>0){
    $organiser=$result['created_by'];
    $event_name=$result['name'];
}else{
    $_SESSION['error_invite']='Događaj više nije aktuelan!';
    header("Location: index.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Upit</title>
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
            background: #eee;
            font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
            /*font-size: 14px;*/
            color: #000;
            margin: 0;
            padding: 0;
        }

        .form-label{
            align-content: ;
        }
    </style>
</head>
<body class="bg-light ">
<script>
    $(document).ready(function () {
        $("#inviteForm").submit(function (e) {
            e.preventDefault();
            var organiser = $("#organiser").val();
            var eventNo = $("#event_no").val();
            var eventName = $("#eventName").val();
            var inviteName = $("#inviteName").val().trim();
            var inviteEmail = $("#inviteEmail").val().trim();
            var inviteMessage = $("#message").val().trim();
            if (inviteName!='' && inviteEmail!='' && inviteMessage!='') {
                $('#nameError').text('');
                $('#emailError').text('');
                $('#messageError').text('');
                $.ajax({
                    type: "GET",
                    url: "QRinvitedata.php",
                    data: {
                        inviteName:inviteName,
                        inviteEmail:inviteEmail,
                        message:inviteMessage,
                        organiser:organiser,
                        event_no:eventNo,
                        eventName:eventName
                    },
                    success: function (response) {
                        var json = JSON.parse(response);
                        var status = json.status;
                        if (status === 'true') {
                            $('#nameError').text('');
                            $('#emailError').text('');
                            $('#messageError').text('');
                            $("#inviteName").val('');
                            $("#inviteEmail").val('');
                            $("#message").val('');
                            $('#msg').text('Vaš zahtev je uspešno poslat');
                            $('#exampleModal').modal('show');
                        } else if (status==='E-mail adres nije validna!'){
                            $('#emailError').text(status);
                        } else if (status==='Već ste pozvani na događaj!'){
                            $('#emailError').text(status);
                        } else if (status==='Zahtev za ovaj događaj je već poslat!') {
                            $('#emailError').text(status);
                        }
                    }
                });
            } else {
                if (inviteName == '') {
                    $('#nameError').text('Unesite vaše ime!');
                } else {
                    $('#nameError').text('');
                }

                if (inviteEmail == '') {
                    $('#emailError').text('Unesite vaš e-mail!');
                } else {
                    $('#emailError').text('');
                }

                if (inviteMessage == '') {
                    $('#messageError').text('Unesite poruku!');
                } else {
                    $('#messageError').text('');
                }
            }
        });
    });
</script>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Home</a>
    </div>
</nav>
<br>
<form action="" method="get" name="invite" id="inviteForm">
    <input type="hidden" name="organiser" id="organiser" value="<?php echo $organiser ?>">
    <input type="hidden" name="event_no" id="event_no" value="<?php echo $event_no ?>">
    <input type="hidden" name="name" id="eventName" value="<?php echo $event_name ?>">
    <div class="container">
        <div class="justify-content-center row" >
            <div style="width: 37rem">
                <div class="text-center" style="margin-bottom: 20px">
                <h2>Želeli bi ste da dođete na događaj?</h2>
                </div>
            <div class="mb-3">
                <label for="inviteName" class="form-label">Vaše ime</label>
                <input type="text" class="form-control" id="inviteName" name="inviteName">
                <small id="nameError" class="form-text text-danger"></small>
            </div>
            <div class="mb-3">
                <label for="inviteEmail" class="form-label">Vaša e-mail adresa</label>
                <input type="text" class="form-control" id="inviteEmail" name="inviteEmail">
                <small id="emailError" class="form-text text-danger"></small>
            </div>
            <div class="mb-3">
                <label for="message" class="form-label">Poruka organizatoru događaja</label>
                <textarea class="form-control" name="message" id="message" rows="3"></textarea>
                <small id="messageError" class="form-text text-danger"></small>
            </div>
            <div class="text-center">
                <button type="submit" class="btn btn-primary" style="width: 100%">Pošaljite zahtev</button>
            </div>
        </div>
        </div>
    </div>
</form>
<div class="container">
    <footer class="py-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">

        </ul>
        <p class="text-center text-muted">&copy; 2023 Jevanđel Đurić<br>Napredno veb programiranje projekat</p>
    </footer>
</div>
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