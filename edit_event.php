<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
if (!isset($_SESSION['username']) OR !isset($_SESSION['id_user']) OR !is_int($_SESSION['id_user'])) {
    redirection('login.php?l=0');
}
$id= $_GET['id'] ?? null;
$archived=$_GET['archived']??null;
if (isset($_POST['id'])){
    $id=$_POST["id"]??null;
    $location=$_POST['location']??null;
    $name=$_POST["name"]??null;
    $description=$_POST["desc"]??null;
    $date=$_POST["date"]??null;
    $file_temp = $_FILES["img"]["tmp_name"]??null;
    if (!empty($file_temp)) {
        $sql2="SELECT foto FROM event WHERE id=:id";
        $stmt2=$pdo->prepare($sql2);
        $stmt2->bindValue(":id",$id,PDO::PARAM_INT);
        $stmt2->execute();
        $result2=$stmt2->fetch();
        unlink("images/".$result2['foto']);
        $sql = "UPDATE event SET name=:name, description=:description, date=:date,location=:location,foto=:foto WHERE id=:id";
        $random=mt_rand(1,10000);
        $file_name = "$random-".$_FILES['img']["name"];
        $upload = "images/$file_name";
        move_uploaded_file($file_temp, $upload);
    } else
        $sql = "UPDATE event SET name=:name, description=:description, date=:date,location=:location WHERE id=:id";
    $query = $pdo -> prepare($sql);
    $query->bindParam(':id',$id, PDO::PARAM_INT);
    $query->bindParam(':location',$location,PDO::PARAM_STR);
    $query->bindParam(':name',$name, PDO::PARAM_STR);
    $query->bindParam(':description',$description, PDO::PARAM_STR);
    $query->bindParam(':date',$date, PDO::PARAM_STR);
    if (isset($file_name))
        $query->bindParam(':foto',$file_name, PDO::PARAM_STR);
    $query->execute();
    if($query->rowCount() > 0)
    {
        $_SESSION['message_change'] = 'Događaj uspešno izmenjen!';
        header("Location: all_events.php?archived={$_POST['archived']}");
    }
    else {
        header("Location: all_events.php?archived={$_POST['archived']}");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Izmeni događaj</title>
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
    <script src="js/updateEventChk.js"></script>
    <style>
        small{
            color: #f00;
        }
    </style>
<body class="bg-light ">
<?php
require_once 'header.php';
?>
<form method="post" action="edit_event.php" id="updateEvent" name="updateEvent" enctype="multipart/form-data">
    <?php
    $sql = "SELECT * FROM event WHERE id=:id";
    $query = $pdo -> prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    $results=$query->fetchAll(PDO::FETCH_OBJ);
    $cnt=1;
    if($query->rowCount() > 0)
    {
        $name=$results[0]->name;
        $description=$results[0]->description;
        $date=$results[0]->date;
        $location=$results[0]->location;
    }

    ?>
    <input type="text" name="id" value="<?php echo $id; ?>" style="display: none">
    <input type="text" name="archived" value="<?php echo $archived; ?>" style="display: none">
    <div class="container">
        <div class="justify-content-center row" >
            <div style="width: 37rem">
        <div class="mb-3">
            <label for="name" class="form-label">Naziv dogđaja</label>
            <input type="text" class="form-control" name="name" id="name" value="<?php if (isset($name))echo $name; ?>">
            <small></small>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Opis događaja</label>
            <textarea class="form-control" name="desc" id="description" rows="3"><?php if (isset($description))echo $description; ?></textarea>
            <small></small>
        </div>
        <div class="mb-3">
            <label for="date" class="form-label">Datum događaja</label>
            <input type="datetime-local" class="form-control" name="date" id="date" value="<?php if (isset($date))echo $date; ?>">
            <small></small>
        </div>
        <div class="mb-3">
            <label for="location" class="form-label">Lokacija:</label>
            <input type="text" class="form-control" name="location" id="location" value="<?php if (isset($location))echo $location; ?>">
            <small></small>
        </div>
        <div class="mb-3">
            <label for="img" class="form-label">Slika:</label>
            <input type="file" class="form-control" name="img" id="img" accept="image/*">
            <small></small>
        </div>
        <div class="col-auto">
            <input type="submit" class="btn btn-primary mb-3" value="sačuvaj promene" name="sb">
        </div>
            </div>
            </div>
    </div>
    <?php
    require_once 'footer.php';
    ?>
</form>
</body>
</html>
