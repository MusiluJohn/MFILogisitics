<?php
session_start();
include("config.php");
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="css/bootsrap2.css"/>
<link rel="stylesheet" href="css/style.css">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
<script src="js/bootstrap1.js"></script>
<script src="js/bootstrap2.js.js"></script>
<script src="js/bootstrap3.js"></script>
<script src="js/jquery1.js"></script>
<script src="js/jquery2.js"></script>
</head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<body>
<?php include 'navbar2.php' ?>
<div style='margin-top:70px;'>
<tr style='width:200px;'><td><a>Select landed cost supplier:</a>
        <?php  
                include("config.php");
                $conn2 = sqlsrv_connect( $servername, $connectioninfo);
                $sql2 = "Select distinct dclink,name from vendor";	
                $stmt2 = sqlsrv_query($conn2,$sql2);
                if ($stmt2) {			 
                echo "<select id='vendor' name='vendor' class='form-control select2' style='width:250px;height:30px;margin-bottom:5px;'>";
                    while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {				
                        echo "<option  value=" .$row2["dclink"]. "> " .$row2["name"]. "</option>";
                    }   
                echo"</select>";
                }
            ?>
            <script>
                $('.select2').select2();
            </script>
        </td>
    <td><a>Select Transit Warehouse:</a>
        <?php  
                include("config.php");
                $conn2 = sqlsrv_connect( $servername, $connectioninfo);
                $sql2 = "Select distinct whselink, name from whsemst";	
                $stmt2 = sqlsrv_query($conn2,$sql2);
                if ($stmt2) {			 
                echo "<select id='transit' name='transit' class='form-control select2' style='width:250px;height:30px;margin-bottom:5px;'>";
                    while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {				
                        echo "<option  value=" .$row2["whselink"]. "> " .$row2["name"]. "</option>";
                    }   
                echo"</select>";
                }
            ?>
            <script>
                $('.select2').select2();
            </script>
    </td>
    <td><a>Select Warehouse To Transfer To:</a>
        <?php  
                include("config.php");
                $conn2 = sqlsrv_connect( $servername, $connectioninfo);
                $sql2 = "Select distinct whselink, name from whsemst";	
                $stmt2 = sqlsrv_query($conn2,$sql2);
                if ($stmt2) {			 
                echo "<select id='final' name='final' class='form-control select2' style='width:250px;height:30px;margin-bottom:5px;'>";
                    while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {				
                        echo "<option  value=" .$row2["whselink"]. "> " .$row2["name"]. "</option>";
                    }   
                echo"</select>";
                }
            ?>
            <script>
                $('.select2').select2();
            </script>
    </td>
    </tr>
<table id="table" class="table table-bordered table-striped table-hover" style='font-size:10px;margin-top:10px;'>
    <thead >
        <tr>
            <th>Code</th>
            <th>Description</th>
            <th hidden>stkid</th>
            <th hidden>idlines</th>	
            <th>Landed Cost</th>
            <th>Landed Cost Without FOB</th>
            <th>Qty Received</th>
            <th>Qty To Be Received</th>					
        </tr>
    </thead>
    <tbody>
    <?php
    //require_once("insert.php");
    include("config.php");
    $conn = sqlsrv_connect( $servername, $connectioninfo);
    $id=$_SESSION['ship'];
    $results = array('error' => false, 'data' => '');
    $sql="select distinct cs.invoicelineid,cs.stkcode,cs.code, max(cs.description) as description, max(totals) as totals, 
    (max(unit_amount_kes)*max(qty))-(max(amount)*max(qty)*max(cs.rate)) as unitcst,max(qty) as qty,max(iuomstockingunitid) as unitid
    from stkitem st
    join _cplshipmentlines cs
    on st.stocklink=cs.stkcode join _cplshipmentmaster tm
    on cs.shipment_no=tm.shipment_no join _cplscheme ce on cs.scheme=ce.Scheme
    where tm.id=cast($id as int)
    group by cs.invoicelineid,cs.code,cs.stkcode";
         $params = array();
         $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );			
         $stmt = sqlsrv_query($conn,$sql,$params,$options) or die(print_r( sqlsrv_errors(), true));		
         $_SESSION['rowno']=sqlsrv_num_rows($stmt);
         if( $stmt === false) {
                die( print_r( sqlsrv_errors(), true) );
            }
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $i=0; ?>
                <tr>
                <td id='code[]' name='code[]'> <?php echo $row["code"] ;?></td>
                <td id='description[]' name='description[]'> <?php echo $row["description"] ;?></td>
                <td hidden> <input class='form-control' style='width:100px;' type='number' id='stid[]' name='stid[]' value="<?php echo $row["stkcode"] ;?>" disabled></td>
                <td hidden> <input class='form-control' style='width:100px;' type='number' id='id[]' name='id[]' value=<?php echo $row["invoicelineid"] ;?> disabled></td>
                <td hidden> <input class='form-control' style='width:100px;' type='number' id='unitid[]' name='unitid[]' value=<?php echo $row["unitid"] ;?> disabled></td>
                <td id='totals[]' name='totals[]'> <?php echo $row["totals"] ;?></td>	
                <td><input class='form-control' style='width:100px;' type='number' id='unitcst[]' name='unitcst[]' value=<?php echo $row["unitcst"] ;?> disabled></td>
                <td> <?php echo $row["qty"] ;?></td>
                <td> <input id='qtyrec[]' name='qtyrec[]' value=<?php echo $row["qty"] ;?> type='number' class='form-control'></input></td>	
                </tr>     
            <?php $i++;}
    sqlsrv_close($conn);

?>
<button id="t" name="t" class='btn btn-success'> <span class="glyphicon glyphicon-floppy-save"></span>CREATE IBT</button>
<script>
            $(document).ready(function(){
            $("#t").click(function(){
                var id=[];
                $('input[name^="stid"]').each(function() {
                    id.push(this.value);
                });
                var qtyrec=[];
                $('input[name^="qtyrec"]').each(function() {
                    qtyrec.push(this.value);
                });
                var unitcst=[];
                $('input[name^="unitcst"]').each(function() {
                    unitcst.push(this.value);
                });
                var unitid=[];
                $('input[name^="unitid"]').each(function() {
                    unitid.push(this.value);
                });
                $.ajax({
                    url: 'ibtinsert.php',
                    type: 'post',
                    data: {id:id, transit:$("#transit").val(),final:$("#final").val(),
                          vendor:$("#vendor").val(),transit:$("#transit").val(),qtyrec:qtyrec,
                          unitcst:unitcst,unitid:unitid},
                    success: function(result){
                            alert('IBT ISSUE SUCCESSFULLY POSTED IN SAGE');
                            window.location.href="supplier_costing.php";
                        },
                    error: function(result) {
                            alert('ERROR');
                    }
                            }); 
                        });
            });
        </script>
</div>

</body>
</html>