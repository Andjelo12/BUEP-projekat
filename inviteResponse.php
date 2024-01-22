<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
$code=$_GET['code'];
$sql="SELECT id,event_id, are_coming, invite_code, email, wish_list FROM invites WHERE invite_code=:invite_code";
$stmt=$GLOBALS['pdo']->prepare($sql);
$stmt->bindParam(":invite_code",$code,PDO::PARAM_STR);
$stmt->execute();

$result = $stmt->fetch(PDO::FETCH_ASSOC);
if (empty($result)){
    $_SESSION['error']="Zvanica ne postoji";
    header("Location: index.php");
    exit();
}
$addWishList=$result['wish_list'];
$id=$result['id'];
$email=$result['email'];
$invite_id=$result['event_id'];
switch ($result['are_coming']){
    case "Yes":
        $selected='selected';
        break;
    case "No":
        $selected2='selected';
        break;
    case "Maybe":
        $selected3='selected';
        break;
}
$selected4='';
/*$sql="SELECT user_buying_present
        FROM wish_list
       WHERE event_id=:event_id AND is_selected='yes'";
$stmt=$GLOBALS['pdo']->prepare($sql);
$stmt->bindParam(":event_id",$invite_id,PDO::PARAM_STR);
$stmt->execute();

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
//$flag=0;
foreach ($results as $result)
{
    //if ($result['user_buying_present']==$email)
        //$selected4='selected';
        //$flag=1;
}*/
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Odgovor na poziv</title>
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
    </style>
</head>
<body class="bg-light " style="text-align: center">
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Home</a>
    </div>
</nav>
<br>
<form id="form" method="get" action="inviteResponseAction.php" style="display: inline-block">
    <input type="hidden" name="id" value="<?php echo $id?>">
    <input type="hidden" name="name" value="<?php echo $email?>">
    <input type="hidden" name="code" value="<?php echo $code?>">
    <div class="mb-3" style="text-align: left">
        <label class="form-label" for="answer">Dolazite li na događaj?</label>
        <select class="form-select" id="answer" name="action">
            <option value="Didn't decided">Izaberite</option>
            <option value="Yes" <?php if(isset($selected))echo $selected ?>>Da</option>
            <option value="No" <?php if(isset($selected2))echo $selected2 ?>>Ne</option>
            <option value="Maybe" <?php if(isset($selected3))echo $selected3 ?>>Možda</option>
        </select>
        <p style="color: red" id="error"></p>
    </div>
    <?php
    $sql="SELECT item, link, id, is_selected, user_buying_present
            FROM wish_list
           WHERE event_id=:event_id";
    $stmt=$GLOBALS['pdo']->prepare($sql);
    $stmt->bindParam(":event_id",$invite_id,PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (!empty($results) && $addWishList=='yes'){
    ?>
    <div class="mb-3" style="text-align: left">
        <label class="form-label" for="wishListItem">Izaberite poklon koji ćete doneti</label>
        <select class="form-select" id="wishListItem" name="wishListItem">
            <option value="null">Izaberite</option>
            <?php
            foreach ($results as $result){
                if ($result['user_buying_present']==$email){
                    echo "<option value='{$result['id']}' data-description='{$result['link']}' selected>{$result['item']}</option>";
                }
                if ($result['is_selected']=='no') {
                    echo "<option value='{$result['id']}' data-description='{$result['link']}'>{$result['item']}</option>";
                }
            }
            ?>
        </select>
        <p style="color: red" id="error2"></p>
    </div>
    <div class="mb-3" style="text-align: left">
        <label class="form-label" for="description" style="visibility: hidden"><a id="link">Link proizvoda</a></label>
    </div>
    <?php
    }
    ?>
    <button type="submit" class="btn btn-primary">Sačuvaj</button><br><br>
</form>
<script>
    var selectElement = document.getElementById('wishListItem');
    var descriptionElement = document.getElementById('link');
    // Add an event listener for the change event
    if (selectElement!=null) {
        selectElement.addEventListener('change', function () {
            // Access the selected option
            var selectedOption = selectElement.options[selectElement.selectedIndex];

            // Access the description using the data-description attribute
            var description = selectedOption.getAttribute('data-description');
            if (selectElement.getElementsByTagName('option')[0].selected === false) {
                // Set the description in the input field
                descriptionElement.href = description;
                descriptionElement.style.visibility = 'visible';
            } else {
                descriptionElement.style.visibility = 'hidden';
            }
        });
    }
    // window.addEventListener("DOMContentLoaded",init);
    // function init() {

    const wishListItem = document.querySelector('#wishListItem');
    const answer = document.querySelector('#answer');
    const form = document.querySelector('#form');
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        if(validateAdd())
            form.submit();
    });
    let validateAdd=()=>{
        let isValid = true;
        if (answer.getElementsByTagName('option')[0].selected){
            isValid=false;
            showErrorMessage("Izaberite da li dolazite na događaj");
        }else {
            hideErrorMessage();
        }
        if (wishListItem!=null) {
            if (wishListItem.getElementsByTagName('option')[0].selected) {
                isValid = false;
                showErrorMessage2("Izaberite jedan od poklona iz liste");
            } else {
                hideErrorMessage2();
            }
        }
        return isValid;
    }
    const showErrorMessage = (message) => {
        const error = document.querySelector('#error');
        error.innerText = message;
    };
    const hideErrorMessage = () => {
        const error = document.querySelector('#error');
        error.innerText = '';
    }
    const showErrorMessage2 = (message) => {
        const error2 = document.querySelector('#error2');
        error2.innerText = message;
    };
    const hideErrorMessage2 = () => {
        const error2 = document.querySelector('#error2');
        error2.innerText = '';
    }
    // }
</script>
<div class="container">
    <footer class="py-3 my-4">
        <ul class="nav justify-content-center border-bottom pb-3 mb-3">

        </ul>
        <p class="text-center text-muted">&copy; 2023 Jevanđel Đurić<br>Napredno veb programiranje projekat</p>
    </footer>
</div>
</body>
</html>