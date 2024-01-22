<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
if (!isset($_SESSION['username']) OR !isset($_SESSION['id_user']) OR !is_int($_SESSION['id_user'])) {
    redirection('login.php?l=0');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lista zvanica</title>
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
require_once 'header.php';
?>
<br>
<div class="container-fluid">
    <div class="row">
        <div class="container">
            <div class="row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <table id="example" class="table">
                        <thead>
                        <th>No</th>
                        <th>Email</th>
                        <th>Ime</th>
                        <th>Dolazi</th>
                        <th>Opcije</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2"></div>
                <?php
                    if($_GET['archived']=='no'){
                ?>
                <div class="btnAdd">
                    <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addUserModal" class="btn btn-success btn-sm">Dodaj zvanicu</a>
                </div>
                <?php
                    }
                ?>
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
$id=$_GET['id'];
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
                'url': 'fetch_data.php',
                'type': 'post',
                data:{id:<?php echo $id;?>,archived:'<?php echo $_GET['archived'];?>'}
            },
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [0,4]
            },

            ]
        });
        $('#addUserModal').on('hidden.bs.modal', function (e) {
            $('#addEmailField').val('');
            $('#addFnameField').val('');
            $('#addEmailFieldError').text('');
            $('#addFnameFieldError').text('');
            if ($('#addWishList').length > 0)
                $('#addWishList').prop('checked',false);
        });
    });
    $(document).on('submit', '#addUser', function(e) {
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
                    id: <?php echo $id?>,
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
                        //alert("Zvanica uspešno dodata")
                        $('#message').text("Zvanica uspešno dodata!");
                        $('#addEmailField').val('');
                        $('#addFnameField').val('');
                        $('#addEmailFieldError').text('');
                        $('#addFnameFieldError').text('');
                        $('#addUserModal').modal('hide');
                        $('#notificationModal').modal('show');
                    } else if (status === 'invalid email'){
                        $('#addEmailFieldError').text('E-mail adresa je u lošem formatu!');
                    } else {
                        $('#addFnameFieldError').text('');
                        $('#addEmailFieldError').text('Korisnik je već dodat u listu zvanica!');
                        //errorSpan.text(status);
                    }
                }
            });
        } else {
            //alert('Popunite sva polja');
            if (email == '') {
                $('#addEmailFieldError').text('Unesite email zvanice.');
            } else {
                $('#addEmailFieldError').text('');
            }

            if (fname == '') {
                $('#addFnameFieldError').text('Unesite ime zvanice.');
            } else {
                $('#addFnameFieldError').text('');
            }
        }
    });
    $(document).on('submit', '#updateUser', function(e) {
        e.preventDefault();
        //var tr = $(this).closest('tr');
        var fname = $('#fnameField').val();
        var arriving= $('#arriving').val();
        var id=$('#id').val();
        if (fname != '' && arriving!="Didn't decided") {
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
                        /*var row = table.row("[id='" + trid + "']");
                        row.row("[id='" + trid + "']").data([id, email, fname, lname, iscoming, button]);*/
                        $('#exampleModal').modal('hide');
                    } else {
                        alert('failed');
                    }
                }
            });
        } else {
            //alert('Popunite sva polja');
            if (fname == '') {
                $('#addFnameError').text('Ime zvanice je obavezno!');
            } else {
                $('#addFnameError').text('');
            }
            if (arriving == "Didn't decided") {
                $('#addArrivingError').text('Morate odlučiti!');
            } else {
                $('#addArrivingError').text('');
            }
        }
    });
    $('#example').on('click', '.editbtn ', function(event) {
        var trid = $(this).closest('tr').attr('id');
        var id = $(this).data('id');
        $('#addFnameError').text('');
        $('#addArrivingError').text('');
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
        $('#deleteInviteConfirmModal').modal('show');
        $('#confirm-delete').off('click').on('click', function () {
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
                        $('#deleteInviteConfirmModal').modal('hide');
                        $('#message').text('Zvanica uspešno obrisana');
                        $('#notificationModal').modal('show');
                    } else {
                        alert('Failed');
                        return;
                    }
                }
            });
        });
    })
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
                            <small id="addFnameError" class="form-text text-danger"></small>
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
                            <small id="addArrivingError" class="form-text text-danger"></small>
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
                            <input type="text" class="form-control" id="addEmailField" name="email">
                            <small id="addEmailFieldError" class="form-text text-danger"></small>
                        </div>
                        <span class="col-md-3"></span>
                        <span class="col-md-9" id="error" style="color: #f00"></span>
                    </div>
                    <div class="mb-3 row">
                        <label for="addFnameField" class="col-md-3 form-label">Ime</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="addFnameField" name="fname">
                            <small id="addFnameFieldError" class="form-text text-danger"></small>
                        </div>
                    </div>
                    <?php
                    $sql="SELECT COUNT(*) AS CNT FROM wish_list WHERE event_id=:event_id";
                    $stmt=$pdo->prepare($sql);
                    $stmt->bindValue(":event_id",$id,PDO::PARAM_INT);
                    $stmt->execute();
                    $result=$stmt->fetch();
                    if ($result['CNT'] > 0){
                    ?>
                    <div class="mb-3 row">
                        <label for="addWishList" class="col-md-3 form-label" style="font-size: 14px">Dodaj listu želja</label>
                        <input type="checkbox" style="margin-left: 3px" class="col-md-1 form-check" id="addWishList" name="wishList">
                    </div>
                    <?php }?>
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
<div class="modal fade" id="deleteInviteConfirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Potvrda brisanja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Sigurno želite da izbrišete ovu zvanicu?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" id="confirm-delete">Da</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Ne</button>
            </div>
        </div>
    </div>
</div>
<!--<div class="container">
    <div class="justify-content-center text-center">
        <button class="btn btn-primary" onclick="pageRedir()">Dodaj zvanicu</button>
    </div>
</div>-->
<?php
require_once 'footer.php';
?>
</body>
</html>
