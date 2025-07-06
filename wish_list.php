<?php
session_start();
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://code.jquery.com; style-src 'self' 'unsafe-inline'; img-src 'self' data:; connect-src 'self'; font-src 'self'; object-src 'none'; frame-src 'self'; base-uri 'self';");
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
if (!isset($decoded) /*OR !isset($_SESSION['id_user']) OR !is_int($_SESSION['id_user'])*/) {
    redirection('login.php?l=0');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lista želja</title>
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
                        <th>Poklon</th>
                        <th>Kupuje ga</th>
                        <th>Opcije</th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2"></div>
                <?php if($_GET['archived']=='no'){?>
                <div class="btnAdd">
                    <a href="#!" data-id="" data-bs-toggle="modal" data-bs-target="#addPresentModal" class="btn btn-success btn-sm">Dodaj poklon</a>
                </div>
                <?php }?>
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
                'url': 'fetch_data_present.php',
                'type': 'post',
                data:{id:<?php echo $id;?>,archived:'<?php echo $_GET['archived'];?>'}
            },
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [0,3]
            },

            ],
            "columns": [
                null, // No specific width for the first column
                null, // No specific width for the second column
                null, // No specific width for the third column
                { "width": "150px" } // Set a specific width for the fourth column (Opcije)
            ]
        });
        $('#exampleModal').on('hidden.bs.modal', function (e) {
            $('#updateNameFieldError').text('');
            $('#updateLinkFieldError').text('');
        });
        $('#addPresentModal').on('hidden.bs.modal', function (e) {
            $('#addPresentField').val('');
            $('#addLinkField').val('');
            $('#addPresentFieldError').text('');
            $('#addLinkFieldError').text('');
        });
    });
    //Dodavanje poklona u listu želja primenom jQuery-a i AJAX-a
    $(document).on('submit', '#addPresent', function(e) {
        e.preventDefault();
        var present = $('#addPresentField').val();
        var link = $('#addLinkField').val();
        //Provera podataka pre slanja
        if (present != '' && link != '') {
            /*Slanje podataka na stranicu add_user_present.php*/
            $.ajax({
                url: "add_user_present.php",
                type: "post",
                data: {
                    id:<?php echo $id?>,
                    present:present,
                    link:link
                },
                success: function(data) {
                    var json = JSON.parse(data);
                    var status = json.status;
                    /*provera vraćenih podataka*/
                    if (status === 'true') {
                        mytable = $('#example').DataTable();
                        mytable.draw();
                        $('#message').text('Poklon uspešno dodat!');
                        $('#notificationModal').modal('show');
                        $('#addPresentField').val('');
                        $('#addLinkField').val('');
                        $('#addPresentFieldError').text('');
                        $('#addLinkFieldError').text('');
                        $('#addPresentModal').modal('hide');
                    } else {
                        $('#addLinkFieldError').text('Link poklona nije validan.');
                    }
                }
            });
        } else {
            /*Obaveštenja o grešci*/
            if (present == '') {
                $('#addPresentFieldError').text('Unesite naziv poklona.');
            } else {
                $('#addPresentFieldError').text('');
            }

            if (link == '') {
                $('#addLinkFieldError').text('Unesite link poklona.');
            } else {
                $('#addLinkFieldError').text('');
            }
        }
    });
    $(document).on('submit', '#updatePresent', function(e) {
        e.preventDefault();
        //var tr = $(this).closest('tr');
        var item = $('#nameField').val();
        var linkUpdate = $('#linkField').val();
        var id=$('#id').val();
        if (item != '' && linkUpdate != '') {
            $.ajax({
                url: "update_user_present.php",
                type: "post",
                data: {
                    item:item,
                    link:linkUpdate,
                    id: id
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
                        $('#updateNameFieldError').text('');
                        $('#updateLinkFieldError').text('');
                        $('#exampleModal').modal('hide');
                    } else {
                        //alert('failed');
                        $('#updateLinkFieldError').text('Link proizvoda nije validan!');
                    }
                }
            });
        } else {
            //alert('Popunite sva polja');
            if (item == '') {
                $('#updateNameFieldError').text('Unesite naziv poklona.');
            } else {
                $('#updateNameFieldError').text('');
            }

            if (linkUpdate == '') {
                $('#updateLinkFieldError').text('Unesite link poklona.');
            } else {
                $('#updateLinkFieldError').text('');
            }
        }
    });
    $('#example').on('click', '.editbtn ', function(event) {
        var table = $('#example').DataTable();
        var trid = $(this).closest('tr').attr('id');
        // console.log(selectedRow);
        var id = $(this).data('id');
        $('#exampleModal').modal('show');

        $.ajax({
            url: "get_single_data_present.php",
            data: {
                id: id
            },
            type: 'post',
            success: function(data) {
                var json = JSON.parse(data);
                $('#nameField').val(json.item);
                $('#linkField').val(json.link);
                $('#id').val(id);
                $('#trid').val(trid);
            }
        })
    });

    $(document).on('click', '.deleteBtn', function(event) {
        var table = $('#example').DataTable();
        event.preventDefault();
        var id = $(this).data('id');
        $('#deletePresentConfirmModal').modal('show');
        $('#confirm-delete').off('click').on('click', function () {
            $.ajax({
                url: "delete_user_present.php",
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
                        $('#deletePresentConfirmModal').modal('hide');
                        table.draw();
                        $('#message').text('Poklon je obrisan.');
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
                <h5 class="modal-title" id="exampleModalLabel">Izmeni poklon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updatePresent">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="mb-3 row">
                        <label for="nameField" class="col-md-3 form-label">Naziv poklona</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="nameField" name="name">
                            <small id="updateNameFieldError" class="form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="linkField" class="col-md-3 form-label">Link poklona</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="linkField" name="link">
                            <small id="updateLinkFieldError" class="form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Izmeni</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zatvori</button>
            </div>
        </div>
    </div>
</div>
<!-- Add user Modal -->
<div class="modal fade" id="addPresentModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Dodaj poklon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="addPresent" action="">
                    <div class="mb-3 row">
                        <label for="addPresentField" class="col-md-3 form-label">Naziv poklona</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="addPresentField" name="present">
                            <small id="addPresentFieldError" class="form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="addLinkField" class="col-md-3 form-label">Link poklona</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="addLinkField" name="fname">
                            <small id="addLinkFieldError" class="form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Dodaj</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
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
<div class="modal fade" id="deletePresentConfirmModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Potvrda brisanja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Sigurno želite da izbrišete ovaj poklon?</p>
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
