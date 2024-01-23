<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
if (!isset($_SESSION['username'])) {
    redirection('login.php?l=0');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Izmenite profil</title>
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
        .error-message{
            color: #f00;
            /*display: block;*/
        }
    </style>
</head>
<script>
    function showBlockedModal() {
        // $(window).on('load', function() {
            $('#message').text('Zahtev za resetovanje lozinke je već poslat.');
            $('#notificationModal').modal('show');
        // });
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        // Handle form submission
        $('#changeProfileData').submit(function (event) {
            event.preventDefault();
            var id_user=$('#id_user').val();
            var fname = $('#firstname').val();
            var lname = $('#lastname').val();
            // Reset previous error messages
            //$('.error-message').text('');

            // Get form data
            //var formData = $(this).serialize();

            // Make the AJAX request
            if (fname!='' && lname!='') {
                $.ajax({
                    type: 'GET',
                    url: 'change_profile_data.php',
                    data: {
                        id_user:id_user,
                        firstname:fname,
                        lastname:lname
                    },
                    success: function (response) {
                        location.reload();
                    }
                });
            } else {
                if (fname==''){
                    $('#firstname-error').text('Unesite ime!');
                }else {
                    $('#firstname-error').text('');
                }
                if (lname==''){
                    $('#lastname-error').text('Unesite prezime!');
                }else {
                    $('#lastname-error').text('');
                }
            }
        });

        // Handle click on the confirmDelete button inside the modal
        $('#confirmDelete').click(function () {
            // Perform the deletion action here (call deleteProfile.php)
            var id_user = $('#id_user').val();

            $.ajax({
                type: 'POST',
                url: 'deleteUserProfile.php',
                data: {
                    email: '<?php echo $_SESSION['username']?>'
                },
                success: function (response) {
                    // Handle success response if needed
                    //location.reload(); // Reload the page or handle as required
                    window.location.href='index.php';
                }
            });

            // Close the modal after confirming
            //$('#confirmDeleteModal').modal('hide');
        });
    });
</script>
<body class="bg-light ">
<?php
require_once 'header.php';
?>
<br>
<form method="post" action="" id="changeProfileData">
    <?php
    if (isset($_SESSION['change_data'])){
        echo "<script>
            $(window).on('load', function() {
                $('#exampleModal').modal('show');
                  });    
          </script>";
        unset($_SESSION['change_data']);
    }
    if(isset($_SESSION['change_pass'])) {
        echo "<script>
            $(window).on('load', function() {
                $('#message').text('".$_SESSION['change_pass']."');
                $('#notificationModal').modal('show');
            });           
          </script>";
        unset($_SESSION['change_pass']);
    }
    $sql = "SELECT * FROM users2 WHERE id_user=:id";
    $query = $pdo -> prepare($sql);
    $query->bindParam(':id', $_SESSION['id_user'], PDO::PARAM_INT);
    $query->execute();
    $results=$query->fetch(PDO::FETCH_OBJ);
    if($query->rowCount() > 0)
    {
        $requestSend=$results->forgotten_password_token;
        $firstname=$results->firstname;
        $lastname=$results->lastname;
    }
    ?>
    <div class="container">
        <input type="text" name="id_user" id="id_user" value="<?php echo $_SESSION['id_user']; ?>" style="display: none">
        <div class="justify-content-center row" >
        <div style="width: 22rem">
        <div class="mb-3">
            <label for="firstname" class="form-label">Ime</label>
            <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo $firstname ?>">
            <small class="error-message" id="firstname-error"></small>
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Prezime</label>
            <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo $lastname?>">
            <small class="error-message" id="lastname-error"></small>
        </div>
        <div class="mb-3">
            <div class="d-flex justify-content-between">
            <?php if (empty($requestSend)){?>
                <a href="change_pass.php" style="display: initial">Resetuj lozinku</a><a href="#!" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" id="deletProfile" class="link-danger">Obriši profil</a>
            <?php } else {?>
                <a href="#" onclick="showBlockedModal()" style="color: #f00">Resetuj lozinku</a><a href="#!" data-bs-toggle="modal" data-bs-target="#confirmDeleteModal" id="deletProfile" class="link-danger">Obriši profil</a>
            <?php }?>
            </div>
        </div>
        <div class="col-auto">
            <input type="submit" class="btn btn-primary mb-3" value="Sačuvaj">
        </div>
        </div>
        </div>
    </div>
</form>
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
                    <div class="col-md-9">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
            </div>
        </div>
    </div>
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
                        Podaci uspešno izmenjeni.
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Potvrda brisanja profila</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Da li ste sigurni da želite da obrišete svoj profil?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirmDelete">Potvrdi</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Odustani</button>
            </div>
        </div>
    </div>
</div>
<?php
require_once 'footer.php';
?>
</body>
</html>
