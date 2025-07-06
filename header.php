<script>
    function pageRedirLogin() {
        window.location.href="login.php";
    }
    function pageRedirRegister() {
        window.location.href="register.php";
    }
    function logout(){
        window.location.href="logout.php";
    }
    function APItoken(){
        window.location.href="token.php";
    }
</script>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Home</a>
        <?php
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
        if (isset($decoded) && $decoded->data->is_admin=='No') {
        ?>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mynavbar">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">

                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Događaji</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="add_event.php">Kreiraj događaj</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="all_events.php?archived=0">Moji događaji</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="all_events.php?archived=1">Arhivirani događaji</a>
                        <div class="dropdown-divider"></div>
                        <?php
                        $sql="SELECT invite_code FROM invites WHERE email=:email";
                        $stmt5=$pdo->prepare($sql);
                        $stmt5->bindValue(":email",$decoded->data->username,PDO::PARAM_STR);
                        $stmt5->execute();
                        $result5=$stmt5->fetch();
                        if ($stmt5->rowCount()>0)
                        {
                            $code=$result5['invite_code'];
                            echo '<a class="dropdown-item" href="invite_dashboard.php?code='.$code.'">Pozivnice na događaje</a>';
                        }else{
                            echo '<a class="dropdown-item disabled" href="">Pozivnice na događaje</a>';
                        }
                        ?>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="edit_profile.php">Izmeni profil</a>
                </li>
            </ul>
            <form class="d-flex me-2">
                <button class="btn btn-warning" type="button" onclick="APItoken()">API token</button>
            </form>
            <form class="d-flex">
                <button class="btn btn-primary" type="button" onclick="logout()">Logout</button>
            </form>
        </div>
            <?php } elseif (isset($decoded) && $decoded->data->is_admin=='Yes') {?>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mynavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">

                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Događaji</a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="all_events.php?archived=0">Svi događaji</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="all_events.php?archived=1">Arhivirani događaji</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="user_profiles.php">Korisnički nalozi</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="logs.php">Logovi</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="api_tokens.php">API tokeni</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="edit_profile.php">Izmeni profil</a>
                    </li>
                </ul>
                <form class="d-flex">
                    <button class="btn btn-primary" type="button" onclick="logout()">Logout</button>
                </form>
            </div>
            <?php } else {?>
                <form class="d-flex">
                    <button class="btn btn-primary" style="margin-right: 10px" type="button" onclick="pageRedirLogin()">Login</button>
                    <button class="btn btn-primary" type="button" onclick="pageRedirRegister()">Registracija</button>
                </form>
            <?php }?>
    </div>
</nav>