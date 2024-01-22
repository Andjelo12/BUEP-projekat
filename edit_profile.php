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
<body class="bg-light ">
<?php
if (isset($_POST['id_user'])){
    $fName=ucfirst(strtolower(trim($_POST["firstname"])));
    $lName=ucfirst(strtolower(trim($_POST["lastname"])));
    $sql = "UPDATE users SET firstname=:firstname, lastname=:lastname WHERE id_user=:id_user";
    $query = $pdo -> prepare($sql);
    $query->bindParam(':id_user',$_POST["id_user"], PDO::PARAM_INT);
    $query->bindParam(':firstname',$fName, PDO::PARAM_STR);
    $query->bindParam(':lastname',$lName, PDO::PARAM_STR);
    $query->execute();
    if($query->rowCount() > 0) {
        $_SESSION['firstname']=$fName;
        $_SESSION['change_data'] = 'Data successfully changed';
    }
    header("Location: edit_profile.php");
    exit();
}
require_once 'header.php';
?>
<br>
<form method="post" action="" id="addEvent" name="addEvent">
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
    $sql = "SELECT * FROM users WHERE id_user=:id";
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
        <input type="text" name="id_user" value="<?php echo $_SESSION['id_user']; ?>" style="display: none">
        <div class="justify-content-center row" >
        <div style="width: 22rem">
        <div class="mb-3">
            <label for="firstname" class="form-label">Ime</label>
            <input type="text" class="form-control" name="firstname" id="firstname" value="<?php echo $firstname ?>">
            <small></small>
        </div>
        <div class="mb-3">
            <label for="lastname" class="form-label">Prezime</label>
            <input type="text" class="form-control" name="lastname" id="lastname" value="<?php echo $lastname?>">
            <small></small>
        </div>
        <div class="mb-3">
            <?php if (empty($requestSend)){?>
                <a href="change_pass.php">Resetuj lozinku</a>
            <?php } else {?>
                <a href="#" onclick="showBlockedModal()" style="color: #f00">Resetuj lozinku</a>
            <?php }?>
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
<?php
require_once 'footer.php';
?>
</body>
</html>
