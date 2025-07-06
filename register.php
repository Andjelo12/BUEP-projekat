<?php
require_once 'config.php';
require __DIR__ . "/vendor/autoload.php";
$client = new Google\Client();

$client->setClientId("912256885401-ghr2br1tj0occ6bjnqmtrq07fu3ds8g3.apps.googleusercontent.com");
$client->setClientSecret("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");
$client->setRedirectUri("https://rsharp.stud.vts.su.ac.rs/redirectRegister.php");

$client->addScope("email");
$client->addScope("profile");

$url = $client->createAuthUrl();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
            crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="js/script.js"></script>

    <link href="css/style.css" rel="stylesheet">
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
    </style>
    <title>Registeracija</title>
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Home</a>
    </div>
</nav>
<div class="container">
    <div class="justify-content-center row" >
        <div style="width: 22rem">
        <h1 style="text-align: center">Registracija</h1>
        <form action="web.php" method="post" id="registerForm">
    <div class="pt-3 field">
        <label for="registerFirstname" class="form-label">Ime</label>
        <input type="text" class="form-control" id="registerFirstname"
               placeholder="Enter firstname" name="firstname">
        <small></small>
    </div>

    <div class="pt-3 field">
        <label for="registerLastname" class="form-label">Prezime</label>
        <input type="text" class="form-control" id="registerLastname"
               placeholder="Enter lastname" name="lastname">
        <small></small>
    </div>

    <div class="pt-3 field">
        <label for="registerEmail" class="form-label">E-mail adresa</label>
        <input type="text" class="form-control" id="registerEmail"
               placeholder="Enter valid e-mail address" name="email">
        <small></small>
    </div>

    <div class="pt-3 field">
        <label for="registerPassword" class="form-label">Lozinka <i class="bi bi-eye-slash-fill"
                                                                    id="passwordEye"></i></label>
        <input type="password" class="form-control passwordVisibiliy" name="password" id="registerPassword"
               placeholder="Password (min 8 characters)">
        <small></small>
        <span id="strengthDisp" class="badge displayBadge">Slaba</span>
    </div>

    <div class="pt-3 field">
        <label for="registerPasswordConfirm" class="form-label">Potvrdite lozinku <i class="bi bi-eye-slash-fill"
                                                                                     id="passwordEye2"></i></label>
        <input type="password" class="form-control passwordVisibiliy2" name="passwordConfirm" id="registerPasswordConfirm"
               placeholder="Password again">
        <small></small>
    </div>

    <div class="pt-3 text-center">
        <input type="hidden" name="action" value="register">
        <button type="submit" class="btn btn-primary">Registruj se</button>
        <button type="reset" class="btn btn-primary resetButton" >Otkaži</button>
    </div>
</form>
<div class="pt-3 text-center">
    <a href="<?php echo $url ?>" class="btn btn-outline-danger w-100">
        <img src="https://developers.google.com/identity/images/g-logo.png" style="width:20px; margin-right:8px;">
        Registruj se sa Google nalogom
    </a>
</div>

<?php
$r = 0;

if (isset($_GET["r"]) and is_numeric($_GET['r'])) {
    $r = (int)$_GET["r"];

    if (array_key_exists($r, $messages)) {
        echo '
                    <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
                        ' . $messages[$r] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                    ';
    }
}
?><br>
            <p style="text-align: center">Imate nalog? <a href="login.php">Uloguj se</a></p>
</div>
</div>
</div>
<?php
require_once 'footer.php';
?>
<script>
    document.getElementById("registerEmail").addEventListener("focusout", validation);
    const regEmail=document.getElementById('registerEmail');
    function validation() {
        if(regEmail.value.trim().length==0){
            hideErrorMessage(regEmail);
        } else if (!isValidEmail(regEmail.value.trim())) {
            showErrorMessage(regEmail, 'E-mail je u netačnom formatu!');
        } else {
            hideErrorMessage(regEmail);
            exist();
        }
    }
    function exist(){
        let dbParam = JSON.stringify({"email":regEmail.value});
        const xmlhttp = new XMLHttpRequest();
        xmlhttp.onload = function() {
            let respTxt = JSON.parse(this.responseText);
            if(respTxt[0].length!=0) {
                showErrorMessage(regEmail, respTxt[0]);
            }
            else {
                hideErrorMessage(regEmail);
            }
        }
        xmlhttp.open("POST", "emailLiveCheck.php");
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("x=" + dbParam);
    }
</script>
</body>
</html>