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
if (!isset($decoded)) {
    redirection('login.php?l=0');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>API token</title>
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
<div class="container">
    <div class="row">
            <div style="font-size: 12pt">
                <?php
                $sql = "SELECT token, calls_no, account_type, tokens_no FROM tokens WHERE email = :email;";
                $stmt = $pdo->prepare($sql);
                $stmt->bindValue(":email", $decoded->data->username);
                $stmt->execute();
                $result = $stmt->fetch();
                $token = $result['token'];
                if ($token) {
                    $color="sandybrown";
                    switch ($result['account_type']) {
                        case "silver":
                            $color="silver";
                            break;
                        case "gold":
                            $color="gold";
                            break;
                        case "platinum":
                            $color="#e5e4e2";
                            break;
                    }
                    preg_match('/Bearer\s(\S+)/', $token, $matches);
                    //echo "Bearer token:".$matches[1];
                    $bareToken = $matches[1];
                    ?>
                    <div class="card shadow-sm">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Tvoj API token</h5>
                        </div>
                        <div class="card-body">
                            <!-- API Endpoint -->
                            <label class="form-label">API endpoint</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">API</span>
                                <input type="text" class="form-control" id="apiEndpoint" value="https://rsharp.stud.vts.su.ac.rs/api/events/" readonly>
                                <button class="btn btn-outline-secondary copy-btn" data-target="apiEndpoint">
                                    <i class="bi bi-clipboard"></i> Copy
                                </button>
                            </div>

                            <!-- Bearer token -->
                            <label class="form-label">Bearer token</label>
                            <div class="input-group mb-3">
                                <span class="input-group-text">Token</span>
                                <input type="text" class="form-control" id="bearerToken" value="<?= htmlspecialchars($bareToken) ?>" readonly>
                                <button class="btn btn-outline-secondary copy-btn" data-target="bearerToken">
                                    <i class="bi bi-clipboard"></i> Copy
                                </button>
                            </div>

                            <!-- TIP NALOGA -->
                            <p class="mb-2">
                                <strong>Tip naloga:</strong>
                                <span class="badge" style="background-color: <?php echo $color; ?>; color: black;"><?php echo ucfirst($result['account_type']); ?></span>
                            <form method="post" action="upgrade_account.php" class="d-inline-block ms-2">
                                <input type="hidden" name="plan" value="<?php echo $result['account_type']; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-info">Upgrade</button>
                            </form>
                            </p>

                            <!-- API POZIVI -->
                            <p>
                                <strong>Preostali broj API poziva:</strong>
                                <?php
                                    $left_calls = $result['tokens_no'] - $result['calls_no'];
                                    if ($left_calls > 0) {
                                        echo "<span class='text-success'>".$left_calls."</span>";
                                    }else{
                                        echo "<span class='text-danger'>".$left_calls."</span>";
                                    }
                                ?>
                            </p>
                        </div>
                    </div>
                    <script>
                        document.querySelectorAll(".copy-btn").forEach(button => {
                            button.addEventListener("click", function () {
                                const targetId = this.dataset.target;
                                const input = document.getElementById(targetId);
                                input.select();
                                input.setSelectionRange(0, 99999);
                                navigator.clipboard.writeText(input.value).then(() => {
                                    this.innerHTML = '<i class="bi bi-clipboard-check"></i> Copied!';
                                    setTimeout(() => {
                                        this.innerHTML = '<i class="bi bi-clipboard"></i> Copy';
                                    }, 2000);
                                });
                            });
                        });
                    </script>
                        <?php
                    //echo "Tip naloga: <div style='display: inline; color: " .$color."'>".$result['account_type']."</div>";
                ?>
<!--                <form method="post" action="upgrade_account.php" id="GenerateAPItoken" style="display: inline;">
                    <input type="hidden" name="plan" value="<?php /*echo $result['account_type']; */?>">
                    <input type="submit" class="btn btn-info text-white" value="upgrade">
                </form>-->
                <?php
                    //echo "<br>Preostali broj API poziva: ".$result['tokens_no']-$result['calls_no'];
                }else{
                ?>
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0">Nemaš generisan API token</h5>
                        </div>
                        <div class="card-body">
                            <p class="mb-3">Trenutno nemaš aktivan API token. Klikni na dugme ispod da generišeš novi token i započneš korišćenje API-ja.</p>
                            <form method="post" action="generate_token.php" id="GenerateAPItoken">
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-plus-circle"></i> Kreiraj token
                                </button>
                            </form>
                        </div>
                    </div>
                <!--<form method="post" action="generate_token.php" id="GenerateAPItoken" class="d-flex">
                    <input type="submit" class="btn btn-info" value="kreiraj token">
                </form>-->
                <?php
                }
                ?>
        </div>
    </div>
</div>
<?php
require_once 'footer.php';
?>
</body>
</html>