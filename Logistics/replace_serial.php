<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
    <link rel="stylesheet" type="text/css" href="css/bootsrap2.css"/>
    <link rel="stylesheet" href="css/style.css">
    <script src="js/bootstrap1.js"></script>
    <script src="js/bootstrap2.js.js"></script>
    <script src="js/bootstrap3.js"></script>
    <script src="js/jquery1.js"></script>
    <script src="js/jquery2.js"></script>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>
<link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
<body>
<?php include 'navbar2.php' ?>
<hr></hr>
<div id="replaceserials" style='margin-top:90px;'>
    <span class='symbol-input100' style='margin-left:15px;'>
    <i class='fa fa-user-circle' aria-hidden='true'><?php echo  $_SESSION['user'] ; ?></i></span>
    <span class='symbol-input100' style='margin-left:15px;'>
    <i class='fa fa-database' aria-hidden='true'><?php echo $_SESSION['db'] ; ?></i></span>
    <a class="nav-link" href="index.php" style='color:blue'>sign out</a>
<a>Select the item code from the drop down below and click search for the serial numbers to be
listed on the grid below: 
</a>

<!--Select items selection below-->
<form id="replace_serials" method="POST">
<table id="tableone" class="table table-bordered table-striped table-hover" style='font-size:12px;'>
<tr id="trone">
    <td id="tdone">
        <?php
                $id= $_SESSION['shipment']; 
                include("config.php");

                //Select the shipment we are checking for serial items
                $conn = sqlsrv_connect( $servername, $connectioninfo);	
                    $ship="Select shipment_no from _cplshipmentmaster where id=$id";
                    $exec=sqlsrv_query($conn,$ship);
                    if ($exec) {    
                        echo "Select serial items of shipment ";
                        while( $row = sqlsrv_fetch_array( $exec, SQLSRV_FETCH_ASSOC) ) {  
                            echo $row["shipment_no"]; 
                            }} 

                    //Selection of serial items based on the shipment number here 
                    $sql = "Select distinct sm.Code as code, sm.description_1 from stkitem sm join _cplshipmentlines cs on sm.code=cs.code join _cplshipmentmaster cm on cs.shipment_no=cm.shipment_no where SerialItem=1 and cm.id=$id";	
                    $stmt = sqlsrv_query($conn,$sql);
                    if ($stmt) {			 
                    echo "<select name='code' id='code' class='form-control' style='width:300px;height:30px;'>";
                        while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {				
                            echo "<option  value=" .$row["code"]. "> " .$row["code"]. "      " .$row["description_1"]. "</option>";
                        } 	
                    echo"</select>";
                    }
                sqlsrv_close($conn);
                ?>
    </td>
    <script>
        $(document).ready(function(){
            $('#code').select2();
        });
    </script>
    <script type="text/javascript">
    document.getElementById('code').value = "<?php echo $_POST['code']; ?>";
</script>
</tr>
</table>
<button type="submit" class="btn btn-success" name='submit'>Search</button>
</form>
<hr></hr>
</div>
<div class="align-content-end" >
<a style='margin-left:10px;'>
    Enter the serial numbers to replace on the replace with column and click validate to check if the serial numbers exist. If they do not exist click replace to post.
<br>
<a style='margin-left:10px;'> 
To upload from excel, select the item you want to replace, click search, Click "Export table to excel" to export the table as template in excel. Enter the new serial numbers in the excel.
<br>
<a style='margin-left:10px;'> 
After entering the new serial numbers in excel, Click the button upload serial to navigate to the upload page.
</a>
<br>
<button id='validate' name='validate' type='button' class='btn-danger' style='margin-bottom:10px;' >
    Validate
</button>
<button id='replace' name='replace' type='button' class='btn-success' style='margin-bottom:10px;' >
    Replace
</button>
<button onclick="ExportToExcel('xlsx')" style="margin-bottom:10px;" class='btn-success'>Export table to excel</button>
<button onclick="location.href='upload_serial.php'" id='upload' name='upload' type='button' class='btn-success' style='margin-bottom:10px;' >
    Upload Excel
</button>
</div>
<table id="tabletwo" name="tabletwo" class="table table-bordered table-striped table-hover" style='font-size:12px;'>
<thead class="table-success">
<tr id="thone">
    <td id="tdone">Serial Counter</td>
    <td id="tdtwo">
    Existing Serial Number
    </td>
    <td id="tdthree">
    Replace With
    </td>
</tr>
</thead>
<tbody>
<?php
    include("config.php");
    $conn3 = sqlsrv_connect( $servername, $connectioninfo);
    $code=$_POST['code'] ?? 0;
    $results = array('error' => false, 'data' => '');
    $sql="select serialcounter, serialnumber from serialmf a join stkitem b 
    on a.SNStockLink=b.StockLink
    where b.code='$code' and currentacclink=5";
sqlsrv_query($conn3,$sql);
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
$stmt = sqlsrv_query($conn3,$sql,$params,$options);	
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) );
}	
   while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
        $i=0;
        $id=$row["serialcounter"];?>
    <tr>
        <td><?php echo $row["serialcounter"] ;?><input id='sid' name='sid[]' value=<?php echo $row["serialcounter"] ;?> hidden/></td>
        <td> <?php echo $row["serialnumber"] ;?></td>
        <td> <input id='serial' name='serial[]'  type='text' style='form-control'/></td>
        <?php $i++;}
        sqlsrv_close($conn);
        ?>
    </tr>
</tbody>
</table>
<script>
    $(document).ready( function () {
        $('#tabletwo').DataTable();

    } );
</script>
<a id="link" href="CostEstimateHome.php"><<<<<< Go back</a>
</body>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.js"></script>
    <script src="assets/js/script.min.js"></script>
    <script src="./assets/js/excel.js"></script>
    <script>
        function ExportToExcel(type, fn, dl) {
            var item=document.getElementById('code').value;
            var elt = document.getElementById('tabletwo');
            var wb = XLSX.utils.table_to_book(elt, { sheet: "sheet1" });
            return dl ?
                XLSX.write(wb, { bookType: type, bookSST: true, type: 'base64' }):
                XLSX.writeFile(wb, fn || (item + '.' + (type || 'xlsx')));
            }
    </script>
<script src="js/script.js">
</script>
<!---Replace button--->
<script>
            $(document).ready(function(){
            $("#replace").click(function(){
                var counter=[];
                $('input[name^="sid"]').each(function() {
                    counter.push(this.value);
                });
                var serial=[];
                $('input[name^="serial"]').each(function() {
                    serial.push(this.value);
                });
                $.ajax({
                    url: 'update_serial.php',
                    type: 'post',
                    data: {counter:counter,serial:serial},
                    success: function(result){
                            alert('Serial numbers successfully updated');
                        },
                    error: function(result) {
                            alert('Error');
                    }
                            }); 
                        });
             });
        </script>
                <!---Validate button--->
        <script>
        $(document).ready(function(){
            $("#validate").click(function(){
                var serial=[];
                $('input[name^="serial"]').each(function() {
                    serial.push(this.value);
                });
            $.ajax({
                url: 'check_serial.php',
                type: 'post',
                data: {serial:serial},
                success: function(result){
                        alert(result);
                    },
                error: function(result) {
                        alert('Error');
                }
                        }); 
                    });
            });
        </script>

</html>