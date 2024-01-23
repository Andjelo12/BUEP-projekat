<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
if (!isset($_SESSION['username'])) {
    redirection('login.php?l=0');
}
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']=='Yes'){
    redirection('login.php?l=0');
}
$archived=$_GET['archived'];
$id=$_GET['id'];
$sql="SELECT * FROM messages
  INNER JOIN event ON messages.id_event=event.id
       WHERE id_event=:id_event";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":id_event",$id,PDO::PARAM_INT);
$stmt->execute();
$results=$stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Korisnički zahtevi</title>
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
        .error {
            color: #f00;
            display: block;
        }
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
    </style>
</head>
<body class="bg-light ">
<script>
    var btn;
    function addInvite(button,id,email,fname){
        btn=button;
        $('#id').val(id);
        $('#email').val(email);
        $('#fname').val(fname);
        $('#addUserModal').modal('show');
    }
    /*function deleteInvite(id){
        if(confirm("Potvrdite brisanje")===true)
        {
            window.location.href = 'delete_request.php?id=' + id;
        }
    }*/
    $(document).ready(function() {
        $('#addUserModal').on('hidden.bs.modal', function () {
            $('#messageField').val('');
            if ($('#addWishList')) {
                $('#addWishList').prop('checked', false);
            }
        });
        $('#confirm-delete').on('click', function() {
            var idMessage = $('.delete-invite-btn.active').data('id-message');
            if (idMessage) {
                window.location.href = 'delete_request.php?id=' + idMessage;
            }
        });

        $('#deleteConfirmModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            $('.delete-invite-btn').removeClass('active');
            button.addClass('active');
        });
    });
    $(document).on('submit', '#addUser', function(e) {
        e.preventDefault();
        var message = document.querySelector('#messageField');
        var mesg = $('#messageField').val();
        var email = $('#email').val();
        var fname = $('#fname').val();
        var addWishList='no';
        if ($('#addWishList')){
            var checked = $('#addWishList').is(":checked");
            if (checked==true){
                addWishList='yes'
            }
        }
        const isEmpty = value => value === '';
        let validateData = () => {
            let isValid = true;
            if (isEmpty(message.value)) {
                showErrorMessage(message, "Unesite poruku");
                isValid = false;
            } else {
                hideErrorMessage(message);
            }
            return isValid;
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
        if (validateData())
            $.ajax({
                url: "add_user.php",/*add_user.php*/
                type: "post",
                data: {
                    id: <?php echo $_GET['id']?>,
                    message : mesg,
                    email : email,
                    fname : fname,
                    addWishList: addWishList
                },
                success: function(data) {
                    var json = JSON.parse(data);
                    var status = json.status;
                    if (status === 'true') {
                        $('#email').val('');
                        $('#fname').val('');
                        $('#messageField').val('');
                        $('#addWishList').prop('checked',false);
                        hideErrorMessage(message);
                        $(btn).text('Zvanica već dodata').removeClass('btn-outline-success').addClass('btn-outline-danger').attr('disabled',true);
                        $('#addUserModal').modal('hide');
                        $('#notificationModal').modal('show');
                    } else {
                        errorSpan.text(status);
                    }
                }
            });
    });
</script>
<?php
require_once 'header.php';
?>
<div class="container">
    <div class="justify-content-center row" >
<div class="my-3 p-3 bg-body rounded shadow-sm">
    <h6 class="border-bottom pb-2 mb-0">Zahtevi korisnika</h6>
    <?php
    foreach ($results as $result){
    ?>
    <div class="d-flex text-muted pt-3 border-bottom">
<!--        <img class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="70" height="70" src="./images/--><?php //echo $result['foto']?><!--">-->

        <div class="pb-3 mb-0 lh-sm w-100">
            <div id="append" class="d-flex justify-content-between">
                <strong class="text-gray-dark font-monospace">Od(ime | email): <?php echo $result['invite_name']?> | <?php echo $result['sender_email']?> (<?php echo $result['date_time']?>)</strong>
                <div>
                <?php
                $subclass='';
                if ($result['status']=='unreplied'){?>
                    <?php
                    if ($archived!=0) {
                        $subclass='disabled';
                    }?>
                    <button type="button" class="btn btn-sm btn-outline-success <?php echo $subclass;?>" onclick="addInvite(this,<?php echo $result['id_event']?>, '<?php echo $result['sender_email']?>', '<?php echo $result['invite_name']?>')">Dodaj zvanicu</button>

                    <!--                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteInvite(<?php //echo $result['id_message']?>)">Obriši zahtev</button>-->
                <?php }else{?>
                    <button type="button" class="btn btn-sm btn-outline-danger disabled">Zvanica već dodata</button>
                <?php }?>
                <button type="button" class="btn btn-sm btn-outline-danger delete-invite-btn <?php echo $subclass;?>" data-bs-toggle="modal" data-bs-target="#deleteConfirmModal" data-id-message="<?php echo $result['id_message']?>">Obriši zahtev</button>
                </div>
            </div>
            <span class="d-block">Poruka: <?php echo $result['message']?></span>
        </div>
<!--        <p class="pb-0 mb-0 small lh-sm">-->
<!--            <strong class="d-block text-gray-dark">From: --><?php //echo $result['sender_email']?><!--</strong>-->
<!--            --><?php //echo $result['message']?>
<!--            <button type="button" class="btn btn-sm btn-outline-success" onclick="">Dodaj zvanicu</button>-->
<!--        </p>-->

        <!--        <svg class="bd-placeholder-img flex-shrink-0 me-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: 32x32" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#007bff"/><text x="50%" y="50%" fill="#007bff" dy=".3em">32x32</text></svg>-->
    </div>
        <?php
    }
    ?>
<!--    <small class="d-block text-end mt-3">-->
<!--        <a href="#">All updates</a>-->
<!--    </small>-->
</div>
    </div>
</div>
<?php
require_once 'footer.php';
?>
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Dodaj zvanicu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUser">
                    <input type="hidden" name="email" id="email">
                    <input type="hidden" name="fname" id="fname">
                    <input type="hidden" name="id" id="id">
                    <div class="mb-3 row">
                        <label for="messageField" class="col-md-3 form-label">Poruka</label>
                        <div class="col-md-9">
                            <textarea class="form-control" id="messageField" name="message"></textarea>
                            <small></small>
                        </div>
                    </div>
                    <?php
                    $sql="SELECT COUNT(*) AS CNT FROM wish_list WHERE event_id=:event_id";
                    $stmt=$pdo->prepare($sql);
                    $stmt->bindValue(":event_id",$id,PDO::PARAM_INT);
                    $stmt->execute();
                    $result=$stmt->fetch();
                    if ($result['CNT'] > 0){
                    ?>
                    <div class="mb-3 row">
                        <label for="addWishList" class="col-md-3 form-label" style="font-size: 14px">Dodaj listu želja</label>
                        <input type="checkbox" style="margin-left: 3px" class="col-md-1 form-check" id="addWishList" name="wishList" value="yes">
                    </div>
                    <?php } ?>
                    <div class="text-center" style="margin-bottom: 10px">
                        <button type="submit" class="btn btn-primary">Dodaj</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Otkaži</button>
            </div>
        </div>
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
                    <div class="col-md-9">
                        Korisnik dodat u listu zvanica.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteConfirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Potvrda brisanja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Da li ste sigurni da želite da obrišete?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirm-delete">Da</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ne</button>
            </div>
        </div>
    </div>
</div>
</body>
</html>
