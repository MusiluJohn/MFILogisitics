<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>UOM ITEM CHANGER</title>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap1.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap2.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/cpshoe.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap3.3.4.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/blue.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/AdminLTE.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/fontawesome.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
 </head>
 <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
<script src="assets/js/script.min.js"></script>
<script src="assets/js/jquery1.js"></script>
<script src="assets/js/jquery2.js"></script>
<script src="assets/js/bootstrap1.js"></script>
<script src="assets/js/bootstrap2.js"></script>
<script src="assets/js/bootstrap3.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
<link href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css" rel="stylesheet"> 
<body class="hold-transition skin-red-light fixed sidebar-mini" onload="selectall()" style="overflow:scroll">
<br/>
</div>
</ul>
</section>
</aside>
</div>
<!-- Left Side navigation --><!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        CHANGE ITEM UNIT OF MEASURE 
    </h1>
</section>
<!-- Main content -->

<section class="content">
    <!-- Default box -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-warning">
                <div class="box-header">
                    <?php

                    include("connect_main.php");

                    $sql = "select stocklink, description_1 from stkitem";	
                    $stmt = sqlsrv_query($conn,$sql);
                    if ($stmt) {			 
                    echo "<a>Select item to change</a><select name='Items' class='form-control' id='Items'  style='width:500px;height:40px;margin-top:10px;'>";
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {				
                    echo "<option  value='" .$row["stocklink"]. "'> " .$row["description_1"]. "</option>";
                    }                     
                    echo"</select>";
                    }
                    sqlsrv_close($conn);
                    ?>
                    <script>
                    $('#select').select();
                    </script>
                    <script type="text/javascript">
                    document.getElementById('Items').value = "<?php echo $_GET['Items']; ?>";
                    </script>
                    </br>
                </br>
                <?php
                include("connect_main.php");
                $sql = "select cunitcode,idunits from _etblunits";	
                    $stmt = sqlsrv_query($conn,$sql);
                    if ($stmt) {			 
                    echo "<a>Select uom to change to</a><select name='Units' class='form-control' id='Units'  style='width:500px;height:40px;margin-top:10px;'>";
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {				
                    echo "<option  value='" .$row["idunits"]. "'> " .$row["cunitcode"]. "</option>";
                    }                     
                    echo"</select>";
                    }
                    sqlsrv_close($conn);
                    ?>
                    <script>
                    $('#select').select();
                    </script>
                    <script type="text/javascript">
                    document.getElementById('Items').value = "<?php echo $_GET['Items']; ?>";
                    </script>
                    </div>     
                    </div>   
                </div>
                </div> 
                <button class="form-control btn btn-success" id="submit" name="submit"  style="margin-bottom:10px;width:70px;margin-left:15px;align:center">
                save
                </button> 

        <script>
            $(document).ready(function(){
            $("#submit").click(function(){

                $.ajax({
                    url: 'updateuom.php',
                    type: 'post',
                    data: {id:$('#Units').val(),item:$('#Items').val()},
                    success: function(result){
                            alert(result);
                        },
                    error: function(result) {
                            alert('ERROR');
                            
                    }
                            }); 
                        });
            });
        </script>   
</section>
</div>
<!-- /.content -->
<!-- /.content-wrapper -->
</body>
</html>