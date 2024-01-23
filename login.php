<?php
require_once 'config.php';
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
    <title>Login</title>
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
<form action="web.php" method="post" id="loginForm"><br>
    <h1 style="text-align: center">Login</h1>
    <div class="pt-3">

        <label for="loginUsername" class="form-label">Email</label>
        <input type="text" class="form-control" id="loginUsername"
               placeholder="Enter username" name="username">
        <small></small>
    </div>
    <div class="pt-3">
        <label for="loginPassword" class="form-label">Lozinka <i class="bi bi-eye-slash-fill"
                                                                 id="passwordEye"></i></label>
        <input type="password" class="form-control passwordVisibiliy" id="loginPassword" placeholder="Password" name="password">
        <small></small>
    </div>
    <div class="pt-3">
        <input type="hidden" name="action" value="login">
        <button type="submit" class="btn btn-primary w-100">Login</button>
    </div>
</form>


<?php

$l = 0;

if (isset($_GET["l"]) and is_numeric($_GET['l'])) {
    $l = (int)$_GET["l"];

    if (array_key_exists($l, $messages)) {
        echo '
                    <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
                        ' . $messages[$l] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                    ';
    }
}
?>
    <div style="margin-top: 10px">
<a href="#" id="fl">Zaboravili ste lozinku?</a>
<form action="web.php" method="post" name="forget" id="forgetForm">
    <div class="pt-3">
        <label for="forgetEmail" class="form-label">E-mail</label>
        <input type="text" class="form-control" id="forgetEmail" placeholder="Unesite vašu e-mail adresu"
               name="email">
        <small></small>
    </div>
    <div class="pt-3">
        <input type="hidden" name="action" value="forget">
        <button type="submit" class="btn btn-primary">Resetuj lozinku</button>
    </div>
</form>

<?php

$f = 0;

if (isset($_GET["f"]) and is_numeric($_GET['f'])) {
    $f = (int)$_GET["f"];

    if (array_key_exists($f, $messages)) {
        echo '
                    <div class="alert alert-info alert-dismissible fade show m-3" role="alert">
                        ' . $messages[$f] . '
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                    ';
    }
}

?><br> <br>
        <p style="text-align: center">Nemate nalog? <a href="register.php">Registrujte se</a></p>
</div>
    </div>
</div>
</div>
<?php
require_once 'footer.php';
?>
</body>
</html>
