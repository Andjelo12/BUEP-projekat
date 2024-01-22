<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
$code=$_GET['code']??null;
if (!isset($code)){
    $_SESSION['error']="Zvanica ne postoji";
    header("Location: index.php");
    exit();
}
$sql="SELECT email FROM invites WHERE invite_code=:invite_code";
$stmt=$pdo->prepare($sql);
$stmt->bindValue(":invite_code",$code,PDO::PARAM_STR);
$stmt->execute();
$result=$stmt->fetch();
if ($stmt->rowCount()>0) {
    $email = $result['email'];
}else{
    $_SESSION['error']="Zvanica ne postoji";
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lista događaja</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap5.0.1.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/datatables-1.10.25.min.css" />
    <style type="text/css">
        .btnAdd {
            text-align: center;
        }
    </style>
<body class="bg-light ">
<?php
if($email==$_SESSION['username']){
    require_once 'header.php';
} else {
?>
<nav class="navbar navbar-expand-sm navbar-dark bg-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Home</a>
    </div>
</nav>
<?php } ?>
<br>
<div class="container-fluid">
    <div class="row">
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <table id="example" class="table">
                        <thead>
                        <th>Naziv događaja</th>
                        <th>Datum održavanja</th>
                        <th>Status</th>
                        <th>Poklon</th>
                        <th>Izmeni status</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2"></div>
            </div>
        </div>
    </div>
</div>
<!-- Optional JavaScript; choose one of the two! -->
<!-- Option 1: Bootstrap Bundle with Popper -->
<script src="js/jquery-3.6.0.min.js" crossorigin="anonymous"></script>
<script src="js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script type="text/javascript" src="js/dt-1.10.25datatables.min.js"></script>
<!-- Option 2: Separate Popper and Bootstrap JS -->
<!--
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
-->
<?php
if (isset($_SESSION['invite_status'])){
    echo "<script>
            $(window).on('load', function() {
                $('#message').text('".$_SESSION['invite_status']."');
                $('#notificationModal').modal('show');
                  });    
          </script>";
    unset($_SESSION['invite_status']);
}
?>
<script type="text/javascript">
    $(document).ready(function() {
        $('#example').DataTable({
            "fnCreatedRow": function(nRow, aData, iDataIndex) {
                $(nRow).attr('id', aData[0]);
            },
            'serverSide': 'true',
            'processing': 'true',
            'paging': 'true',
            'order': [],
            'ajax': {
                'url': 'fetch_data_invite.php',
                'type': 'post',
                data:{email:'<?php echo $email;?>'}
            },
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [4]
            },

            ]
        });
    });
    /*$(document).on('submit', '#addUser', function(e) {
        e.preventDefault();
        var email = $('#addEmailField').val();
        var fname = $('#addFnameField').val();
        var errorSpan= $('#error');
        var checked = $('#addWishList').is(":checked");
        var addWishList='no';
        if (checked==true){
            addWishList='yes'
        }
        if (email != '' && fname != '') {
            $.ajax({
                url: "add_user.php",
                type: "post",
                data: {
                    id: <?php //echo $id?>,
                    email : email,
                    fname : fname,
                    addWishList: addWishList
                },
                success: function(data) {
                    var json = JSON.parse(data);
                    var status = json.status;
                    if (status === 'true') {
                        mytable = $('#example').DataTable();
                        mytable.draw();
                        alert("Zvanica uspešno dodata")
                        $('#addEmailField').val('');
                        $('#addFnameField').val('');
                        $('#addUserModal').modal('hide');
                    } else {
                        errorSpan.text(status);
                    }
                }
            });
        } else {
            alert('Popunite sva polja');
        }
    });
    $(document).on('submit', '#updateUser', function(e) {
        e.preventDefault();
        //var tr = $(this).closest('tr');
        var fname = $('#fnameField').val();
        var arriving= $('#arriving').val();
        var id=$('#id').val();
        if (fname != '') {
            $.ajax({
                url: "update_user.php",
                type: "post",
                data: {
                    fname:fname,
                    id: id,
                    arriving:arriving
                },
                success: function(data) {
                    var json = JSON.parse(data);
                    var status = json.status;
                    if (status == 'true') {
                        table = $('#example').DataTable();
                        // table.cell(parseInt(trid) - 1,0).data(id);
                        // table.cell(parseInt(trid) - 1,1).data(username);
                        // table.cell(parseInt(trid) - 1,2).data(email);
                        // table.cell(parseInt(trid) - 1,3).data(mobile);
                        // table.cell(parseInt(trid) - 1,4).data(city);
                        //var button = '<td><a href="javascript:void();" data-id="' + id + '" class="btn btn-info btn-sm editbtn">Izmeni</a>  <a href="#!"  data-id="' + id + '"  class="btn btn-danger btn-sm deleteBtn">Obriši</a></td>';
                        table.draw();
                        /!*var row = table.row("[id='" + trid + "']");
                        row.row("[id='" + trid + "']").data([id, email, fname, lname, iscoming, button]);*!/
                        $('#exampleModal').modal('hide');
                    } else {
                        alert('failed');
                    }
                }
            });
        } else {
            alert('Popunite sva polja');
        }
    });
    $('#example').on('click', '.editbtn ', function(event) {
        var trid = $(this).closest('tr').attr('id');
        var id = $(this).data('id');
        $('#exampleModal').modal('show');

        $.ajax({
            url: "get_single_data.php",
            data: {
                id: id
            },
            type: 'post',
            success: function(data) {
                var json = JSON.parse(data);
                $('#fnameField').val(json.name);
                $('#id').val(id);
                $('#trid').val(trid);
                $('#arriving').val(json.are_coming);
            }
        });
    });

    $(document).on('click', '.deleteBtn', function(event) {
        var table = $('#example').DataTable();
        event.preventDefault();
        var id = $(this).data('id');
        if (confirm("Sigurno želite da izbrišete ovu zvanicu? ")) {
            $.ajax({
                url: "delete_user.php",
                data: {
                    id: id
                },
                type: "post",
                success: function(data) {
                    var json = JSON.parse(data);
                    status = json.status;
                    if (status == 'success') {
                        //table.fnDeleteRow( table.$('#' + id)[0] );
                        //$("#example tbody").find(id).remove();
                        //table.row($(this).closest("tr")) .remove();
                        // $("#" + id).closest('tr').remove();
                        table.draw();
                    } else {
                        alert('Failed');
                        return;
                    }
                }
            });
        } else {
            return null;
        }



    })*/
</script>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Izmeni zvanicu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateUser">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="mb-3 row">
                        <label for="fnameField" class="col-md-3 form-label">Ime</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="fnameField" name="fname">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="arriving" class="col-md-3 form-label">Dolazi</label>
                        <div class="col-md-9">
                            <select name="arriving" class="form-select" id="arriving">
                                <option value="Didn't decided">nije odlučio</option>
                                <option value="Yes">da</option>
                                <option value="No">ne</option>
                                <option value="Maybe">možda</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Izmeni</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Otkaži</button>
            </div>
        </div>
    </div>
</div>
<!-- Add user Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Dodaj zvanicu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addUser">
                    <div class="mb-3 row">
                        <label for="addEmailField" class="col-md-3 form-label">Email</label>
                        <div class="col-md-9">
                            <input type="email" class="form-control" id="addEmailField" name="email">
                        </div>
                        <span class="col-md-3"></span>
                        <span class="col-md-9" id="error" style="color: #f00"></span>
                    </div>
                    <div class="mb-3 row">
                        <label for="addFnameField" class="col-md-3 form-label">Ime</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="addFnameField" name="fname">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="addWishList" class="col-md-3 form-label" style="font-size: 14px">Dodaj listu želja</label>
                        <input type="checkbox" style="margin-left: 3px" class="col-md-1 form-check" id="addWishList" name="wishList">
                    </div>
                    <div class="text-center">
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
<!--<div class="container">
    <div class="justify-content-center text-center">
        <button class="btn btn-primary" onclick="pageRedir()">Dodaj zvanicu</button>
    </div>
</div>-->
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
<?php
require_once 'footer.php';
?>
</body>
</html>
