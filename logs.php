<?php
session_start();
require_once 'config.php';
require_once 'functions_def.php';
if (!isset($_SESSION['username'])) {
    redirection('login.php?l=0');
}
if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']=='No'){
    redirection('login.php?l=0');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Logovi</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap5.0.1.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="css/datatables-1.10.25.min.css" />
    <style type="text/css">
        html,
        body {
            position: relative;
            height: 100%;
        }

        body {
            background: #eee;
            font-family: Helvetica Neue, Helvetica, Arial, sans-serif;
            /*font-size: 14px;*/
            color: #000;
            margin: 0;
            padding: 0;
        }
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
                <div class="col-md-1"></div>
                <div class="col-md-10">
                    <table id="example" class="table">
                        <thead>
                        <th>No</th>
                        <th>IP adresa</th>
                        <th>e-mail</th>
                        <th>Ima nalog</th>
                        <th>Status</th>
                        <th>Datum vreme</th>
                        <th></th>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-1"></div>
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
                'url': 'fetch_data_users.php',
                'type': 'post',
            },
            "aoColumnDefs": [{
                "bSortable": false,
                "aTargets": [0,6]
            },

            ]
        });
    });
    $(document).on('submit', '#addUser', function(e) {
        e.preventDefault();
        var email = $('#addEmailField').val();
        var fname = $('#addFnameField').val();
        var errorSpan= $('#error');
        var checked = $('#addWishList').is(":checked");
        var addWishList='no';
        if (checked == true){
            addWishList='yes'
        }
        if (email != '' && fname != '') {
            $.ajax({
                url: "add_user.php",
                type: "post",
                data: {
                    id: "",
                    email : email,
                    fname : fname,
                    addWishList: addWishList
                },
                success: function(data) {
                    var json = JSON.parse(data);
                    var status = json.status;
                    if (status == 'true') {
                        mytable = $('#example').DataTable();
                        mytable.draw();
                        alert("Zvanica uspešno dodata")
                        $('#inputEmailField').val('');
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
                        /*var row = table.row("[id='" + trid + "']");
                        row.row("[id='" + trid + "']").data([id, email, fname, lname, iscoming, button]);*/
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
    $('#example').on('click', '.detailsbtn ', function(event) {
        var trid = $(this).closest('tr').attr('id');
        var id = $(this).data('id');
        $('#exampleModal').modal('show');

        $.ajax({
            url: "get_single_data_logs.php",
            data: {
                id: id
            },
            type: 'post',
            success: function(data) {
                var json = JSON.parse(data);
                $('#countryField').val(json.country);
                switch (json.device_type){
                    case "phone":
                        $('#typeField').val('telefon');
                        break;
                    case "table":
                        $('#typeField').val('tablet');
                        break;
                    case "computer":
                        $('#typeField').val('računar');
                        break;
                }
                if (json.proxy==0)
                    $('#proxyField').val('ne');
                else
                    $('#proxyField').val('da');
                if (json.user_agent != '')
                    $('#clientField').val(json.user_agent);
                else{
                    $('#clientField').parent().parent().hide();
                }
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



    })
</script>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Detalji</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="updateUser">
                    <input type="hidden" name="id" id="id" value="">
                    <input type="hidden" name="trid" id="trid" value="">
                    <div class="mb-3 row">
                        <label for="countryField" class="col-md-3 form-label">Zemlja</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="countryField" name="country" disabled>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="typeField" class="col-md-3 form-label">Tip uređaja</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="typeField" name="type" disabled>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="proxyField" class="col-md-3 form-label">Proksi</label>
                        <div class="col-md-9">
                            <input type="text" class="form-control" id="proxyField" name="proxy" disabled>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="clientField" class="col-md-3 form-label">Korisnički klijent</label>
                        <div class="col-md-9">
                            <textarea class="form-control" id="clientField" name="client" disabled></textarea>
                        </div>
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
<?php
require_once 'footer.php';
?>
</body>
</html>
