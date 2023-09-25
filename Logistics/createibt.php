<?php

?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="css/bootstrap2.css"/>
<link rel="stylesheet" href="css/style.css">
<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<script src="js/script.js"></script>
<script src="js/jquery1.js"></script>
<script src="js/jquery2.js"></script>
<script src="js/bootstrap1.js"></script>
<script src="js/bootstrap2.js"></script>
<script src="js/bootstrap3.js"></script>
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<body>
<?php include 'navbar2.php' ?>
<div style='margin-top:90px;'>
<?php include 'login_details.php' ?>
<table>
<tr style='width:200px;'>
<form method="post" action="">
<td><a>Select shipment to create ibt:</a>
<?php
		//Select shipment 

        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
		
			 $sql = "select distinct _cplshipmentmaster.id, _cplshipmentmaster.shipment_no
			 from _cplshipmentmaster join _cplshipmentlines on _cplshipmentmaster.shipment_no=_cplshipmentlines.shipment_no
			 where isnull(active,'True')='True' and isnull(updated,'False') in ('False','True')
             ";	
			// $params = array();
			// $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			 $stmt = sqlsrv_query($conn,$sql);
			if ($stmt) {
			 echo"<select id='SH' name='SH' class='form-control select2' style='width:300px;'>";
			 //echo "<option  value=" .$row["id"]. "> " .$row["shipment_no"]. "</option>";
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<option value=" .$row["id"]. "> " .$row["shipment_no"]. "</option>";
				}
			echo"</select>";
			}
		sqlsrv_close($conn);
	    ?>
<script>
    $('.select2').select2();
</script>
<script type="text/javascript">
    document.getElementById('SH').value = "<?php echo $_POST['SH']; ?>";
</script>
<button class="btn btn-success" type="submit" ><span class="glyphicon glyphicon-search" style="margin-right:2px;"></span>SEARCH</button>
</td>
</tr>
</table>
<br>
<table  style="margin-top:10px;">
<tr>
<!---Select the landed cost supplier below--->
<td><a>Select landed cost supplier:</a>
        <?php  
                include("config.php");
                $conn2 = sqlsrv_connect( $servername, $connectioninfo);
                $sql2 = "Select distinct dclink,name from vendor where account='LandedSupp'";	
                $stmt2 = sqlsrv_query($conn2,$sql2);
                if ($stmt2) {			 
                echo "<select id='vendor' name='vendor' class='form-control select2' style='width:300px;height:30px;margin-bottom:5px;'>";
                    while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {				
                        echo "<option  value=" .$row2["dclink"]. "> " .$row2["name"]. "</option>";
                    }   
                echo"</select>";
                }
            ?>
            <script>
                $('.select2').select2();
            </script>
            <script type="text/javascript">
                document.getElementById('SH').value = "<?php echo $_POST['SH']; ?>";
            </script>    
            <?php 
            $_SESSION['shipment']= $_POST['SH'] ?? '';
            ?>
    </td>
            
    <td><a>Select From Warehouse:</a>
        <?php  
                include("config.php");
                $conn2 = sqlsrv_connect( $servername, $connectioninfo);
                $sql2 = "Select distinct whselink, name from whsemst where whselink=5";	
                $stmt2 = sqlsrv_query($conn2,$sql2);
                if ($stmt2) {			 
                echo "<select id='transit' name='transit' class='form-control select2' style='width:300px;height:30px;margin-bottom:5px;'>";
                    while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {				
                        echo "<option  value=" .$row2["whselink"]. "> " .$row2["name"]. "</option>";
                    }   
                echo"</select>";
                }
            ?>
            <script>
                $('.select2').select2();
            </script>
            <script type="text/javascript">
                document.getElementById('SH').value = "<?php echo $_POST['SH']; ?>";
            </script>
    </td>
    <td><a>Select Warehouse To Transfer To:</a>
        <?php  
                include("config.php");
                $conn2 = sqlsrv_connect( $servername, $connectioninfo);
                $sql2 = "Select distinct whselink, name from whsemst";	
                $stmt2 = sqlsrv_query($conn2,$sql2);
                if ($stmt2) {			 
                echo "<select id='final' name='final' class='form-control select2' style='width:300px;height:30px;margin-bottom:5px;'>";
                    while( $row2 = sqlsrv_fetch_array( $stmt2, SQLSRV_FETCH_ASSOC) ) {				
                        echo "<option  value=" .$row2["whselink"]. "> " .$row2["name"]. "</option>";
                    }   
                echo"</select>";
                }
            ?>
            <script>
                $('.select2').select2();
            </script>
            <script type="text/javascript">
                document.getElementById('SH').value = "<?php echo $_POST['SH']; ?>";
            </script>
    </td>
    </tr>
            </table>
<table id="table" name="table" class="table table-bordered table-striped table-hover" style='font-size:10px;margin-top:10px;'>
    <thead class="table-success">
        <tr>
            <th>Code</th>
            <th>Description</th>
            <th hidden>stkid</th>
            <th hidden>idlines</th>
            <th>Landed Cost</th>
            <th>Landed Cost Without FOB</th>
            <th>Qty Ordered</th>
            <th>Qty Received</th>
            <th>Qty To Be Received</th>					
        </tr>
    </thead>
    <tbody>
    <?php
    //require_once("insert.php");
    include("config.php");
    $conn = sqlsrv_connect( $servername, $connectioninfo);
    $id=$_POST['SH'] ?? 0;
    $results = array('error' => false, 'data' => '');
    $sql="select invoicelineid,stkcode,code,max(description)description,max(totals)totals,sum(round(unitcst,2)) as unitcst,max(qty)qty,max(recqty)recqty,max(unitid)unitid
    from(
    select distinct cs.invoicelineid,cs.stkcode,cs.code, max(cs.description) as description, max(totals) as totals, 
        case when cs.costcode in (2,3,4,5,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,29,30,31,32,33)  then max(isnull(cs.cost,0)) else 0 end as unitcst,max(qty) as qty,
        max(isnull(rec_qty,0)) as recqty,max(iuomstockingunitid) as unitid
        from stkitem st
        join _cplshipmentlines cs
        on st.stocklink=cs.stkcode join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join _cplscheme ce on cs.scheme=ce.Scheme
        join _cplcostmaster c on c.id=cs.costcode
        where tm.id=$id
        group by cs.invoicelineid,cs.code,cs.stkcode,costcode)p
    group by invoicelineid,code,stkcode";
         $params = array();
         $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );			
         $stmt = sqlsrv_query($conn,$sql,$params,$options) or die(print_r( sqlsrv_errors(), true));		
         $_SESSION['rowno']=sqlsrv_num_rows($stmt);
         $count=sqlsrv_num_rows($stmt);
         if( $stmt === false) {
                die( print_r( sqlsrv_errors(), true) );
            }
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                $id=$row['stkcode']; ?>
                <tr>
                <td id='code[]' name='code[]'> <?php echo $row["code"] ;?></td>
                <td id='description[]' name='description[]'> <?php echo $row["description"] ;?></td>
                <td hidden> <input class='form-control' style='width:100px;' type='number' id='stid[]' name='stid[]' value="<?php echo $row["stkcode"] ;?>" disabled></td>
                <td hidden> <input class='form-control' style='width:100px;' type='number' id='id<?php echo $id; ?>' name='id[]' value=<?php echo $row["invoicelineid"] ;?> disabled></td>
                <td hidden> <input class='form-control' style='width:100px;' type='number' id='unitid[]' name='unitid[]' value=<?php echo $row["unitid"] ;?> disabled></td>
                <td id='totals[]' name='totals[]'> <?php echo $row["totals"] ;?></td>	
                <td><input class='form-control' style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;' type='number' id='unitcst[]' name='unitcst[]' value=<?php echo $row["unitcst"] ;?> disabled></td>
                <td id='qty<?php echo $id; ?>'> <?php echo $row["qty"] ;?></td>
                <td id='recqty<?php echo $id; ?>'> <?php echo $row["recqty"] ;?></td>
                <td> <input id='qtyrec<?php echo $id; ?>' name='qtyrec[]' value=<?php echo $row["qty"]-$row['recqty'] ;?> type='number' class='form-control'></input></td>	
                <script>
                    $(document).ready(function(){
                    $("#qtyrec<?php echo $id; ?>").keyup(function(){
                        $.ajax({
                            url: 'chkqty.php',
                            type: 'post',
                            data: {id:$("#id<?php echo $id; ?>").val(), qty:$("#qtyrec<?php echo $id; ?>").val(),shipno:$("#SH").val()},
                            success: function(result){
                                if (result==''){
                                    }
                                    else{
                                    alert(result);
                                    }
                            },
                            error: function(result) {
                                    alert('ERROR');
                            }
                                }); 
                            });
                    });
                </script>  
  
            </tr>     
        
            <?php }
    sqlsrv_close($conn);

?>
</tbody>

</form>
<button id="t" name="t" class='btn btn-success' style='margin-top:10px;'> <span class="glyphicon glyphicon-floppy-save"></span>CREATE IBT</button>
</table>

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
                var invoicelineid=[];
                $('input[name^="id"]').each(function() {
                    invoicelineid.push(this.value);
                });
                $.ajax({
                    url: 'ibtinsert-old.php',
                    type: 'post',
                    data: {id:id, transit:$("#transit").val(),final:$("#final").val(),
                          vendor:$("#vendor").val(),qtyrec:qtyrec,
                          unitcst:unitcst,unitid:unitid,shipment:$("#SH").val(),invoicelineid:invoicelineid},
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
</div>

</body>
</html>