<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Upgrade</title>
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
<br>
<div class="container py-5">
    <h2 class="text-center mb-4">Odaberite tip naloga</h2>
    <div class="row g-4">
        <?php if ($_POST['plan']=='bronze'): ?>
        <!-- Silver -->
        <div class="col-md-4">
            <div class="card border-secondary h-100 shadow-sm">
                <div class="card-header text-white bg-secondary text-center">
                    <h4>Silver</h4>
                </div>
                <div class="card-body text-center">
                    <h2 class="card-title">€0.99</h2>
                    <p class="card-text">150 API poziva/mesečno</p>
                    <form action="checkout.php" method="post">
                        <input type="hidden" name="plan" value="silver">
                        <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
                        <input type="hidden" name="price" value="99">
                        <button type="submit" class="btn btn-secondary">Kupi Silver</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Gold -->
        <div class="col-md-4">
            <div class="card border-warning h-100 shadow-sm">
                <div class="card-header text-white bg-warning text-center">
                    <h4>Gold</h4>
                </div>
                <div class="card-body text-center">
                    <h2 class="card-title">€1.99</h2>
                    <p class="card-text">300 API poziva/mesečno</p>
                    <form action="checkout.php" method="post">
                        <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
                        <input type="hidden" name="plan" value="gold">
                        <input type="hidden" name="price" value="199">
                        <button type="submit" class="btn btn-warning text-white">Kupi Gold</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Platinum -->
        <div class="col-md-4">
            <div class="card border-primary h-100 shadow-sm">
                <div class="card-header text-white bg-primary text-center">
                    <h4>Platinum</h4>
                </div>
                <div class="card-body text-center">
                    <h2 class="card-title">€2.99</h2>
                    <p class="card-text">500 API poziva/mesečno</p>
                    <form action="checkout.php" method="post">
                        <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
                        <input type="hidden" name="plan" value="platinum">
                        <input type="hidden" name="price" value="299">
                        <button type="submit" class="btn btn-primary">Kupi Platinum</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($_POST['plan']=='silver'): ?>

        <!-- Gold -->
        <div class="col-md-6">
            <div class="card border-warning h-100 shadow-sm">
                <div class="card-header text-white bg-warning text-center">
                    <h4>Gold</h4>
                </div>
                <div class="card-body text-center">
                    <h2 class="card-title">€1.99</h2>
                    <p class="card-text">300 API poziva/mesečno</p>
                    <form action="checkout.php" method="post">
                        <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
                        <input type="hidden" name="plan" value="gold">
                        <input type="hidden" name="price" value="199">
                        <button type="submit" class="btn btn-warning text-white">Kupi Gold</button>
                    </form>
                </div>
            </div>
        </div>

            <!-- Platinum -->
            <div class="col-md-6">
                <div class="card border-primary h-100 shadow-sm">
                    <div class="card-header text-white bg-primary text-center">
                        <h4>Platinum</h4>
                    </div>
                    <div class="card-body text-center">
                        <h2 class="card-title">€2.99</h2>
                        <p class="card-text">500 API poziva/mesečno</p>
                        <form action="checkout.php" method="post">
                            <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
                            <input type="hidden" name="plan" value="platinum">
                            <input type="hidden" name="price" value="299">
                            <button type="submit" class="btn btn-primary">Kupi Platinum</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($_POST['plan']=='gold'): ?>
        <!-- Platinum -->
        <div class="col-md-12">
            <div class="card border-primary h-100 shadow-sm">
                <div class="card-header text-white bg-primary text-center">
                    <h4>Platinum</h4>
                </div>
                <div class="card-body text-center">
                    <h2 class="card-title">€2.99</h2>
                    <p class="card-text">500 API poziva/mesečno</p>
                    <form action="checkout.php" method="post">
                        <input type="hidden" name="email" value="<?php echo $_POST['email']; ?>">
                        <input type="hidden" name="plan" value="platinum">
                        <input type="hidden" name="price" value="299">
                        <button type="submit" class="btn btn-primary">Kupi Platinum</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <?php if ($_POST['plan']=='platinum'): ?>
            <div class="col-12 text-center">
                <div class="alert alert-success">Već imate najviši nivo naloga (Platinum). Za dodatne API pozive kontaktirati <a href="mailto:rsharp@rsharp.stud.vts.su.ac.rs">administratora</a>.</div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php
require_once 'footer.php';
?>
</body>
</html>