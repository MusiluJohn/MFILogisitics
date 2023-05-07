
<html>
<head>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootsrap2.css"/>
	<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<script src="js/script.js">
</script>
<script src="js/bootstrap1.js"></script>
<script src="js/bootstrap2.js.js"></script>
<script src="js/bootstrap3.js"></script>
<script src="js/jquery1.js"></script>
<script src="js/jquery2.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>

<script src="js/script.js">
</script>
<!-- <style>
/* Style for positioning toast */
.toast{
    position: absolute; 
    top: 100px; 
    right: 500px;
}
</style> -->
<body onload="selectall()">
<?php include 'navbar2.php' ?>
<div id="table" style='margin-top:90px;'>
    <span class='symbol-input100' style='margin-left:15px;'>
    <i class='fa fa-user-circle' aria-hidden='true'><?php echo  $_SESSION['user'] ; ?></i></span>
    <span class='symbol-input100' style='margin-left:15px;'>
    <i class='fa fa-database' aria-hidden='true'><?php echo $_SESSION['db'] ; ?></i></span>
    <a class="nav-link" href="index.php" style='color:blue'>sign out</a>
<ul><a>Select the lines you would like to add to a shipment then click "CREATE" below:</a></ul>
<table class="table table-bordered table-striped table-hover" style='font-size:10px;margin-top:5px;'>
        <thead>
            <tr>
				<th><input type='checkbox' id="checkall"/></th>
                <th>Code</th>
				<th>PO Number</th>
				<th>Description</th>
				<th>Scheme</th>
				<th>Scheme Allocated</th>
				<th>Weight</th>
				<th>Grv Number</th>
				<th>Grv Quantity</th>
				<th>Qty Processed</th>
				<th>Inclusive Amount</th>		
            </tr>
        </thead>
        <tbody>
		<?php
		//require_once("insert.php");
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
		  $_SESSION['shipment_no']= $_GET['PO'];
		  $query = $_GET['PO'];
		 $results = array('error' => false, 'data' => '');
		
			 $sql = "select bl.ilineid AS Row#,bl.idinvoicelines as id,st.stocklink as stocklink, st.Code as code,ordernum as ordernum,st.Description_1, ucIIScheme,ufIIweight, invnumber, fquantity,bl.fQtyLastProcess, fQtyLastProcessLineTotInclForeign from _btblinvoicelines bl join StkItem st on bl.istockcodeid=st.stocklink 
			 join invnum im on bl.iInvoiceID=im.AutoIndex
			 join _etblUserHistLink ek on ek.TableID=im.autoindex 
			 join _rtblUserDict rt on ek.UserDictID=rt.idUserDict
			 where rt.cFieldName='ucIDPOrdShipmentNo' and ek.UserValue ='$query'  and im.doctype=5 and im.docflag in (1) and im.docstate in (4) and bl.fqtyLastprocess>0  and im.InvNumber<>''
			 order by ordernum";
			 $params = array();
			 $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			 $stmt = sqlsrv_query($conn,$sql,$params,$options);		
			 if( $stmt === false) {
					die( print_r( sqlsrv_errors(), true) );
				}
                $row_count = sqlsrv_num_rows($stmt);
				if ($row_count>0) {
                $rows=0;
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					?>
					<form name="po_details" method="post" action="insert.php" >
					<tr class="<?php if(isset($classname)) echo $classname;?>">
					<td><input type='checkbox' id="checkboxes" name="users[]" value="<?php echo $row["id"] ;?>" /></td>
					<td hidden><input id='code<?php echo $rows;?>' name='code' value="<?php echo $row["stocklink"] ;?>" hidden/> </td>
					<td> <?php echo $row["code"] ;?></td>
					<td> <?php echo $row["ordernum"] ;?></td>
					<td> <?php echo $row["Description_1"] ;?></td>
					<td> <?php echo $row["ucIIScheme"] ;?></td>
					<td> <select><option id='A' name='A'>A 10%</option>
						<option id='B' name='B'>B 0%</option>
						<option id='C' name='C'>C 35%</option>
						<option id='D' name='D'>D 25%</option>	
					</select></td>
					<td><input style='width:90px;height:12.5px;' class='form-control' id='weight<?php echo $rows;?>' name='weight<?php echo $rows;?>' value="<?php echo $row["ufIIweight"] ;?>" /></td>
					<script>
						$(document).ready(function(){
						$('#weight<?php echo $rows;?>').change(function(){
							$.ajax({
								url: 'chweight.php',
								type: 'post',
								data: {id: $('#code<?php echo $rows;?>').val(),weight: $('#weight<?php echo $rows;?>').val()},
								success: function(result){
								alert('updated');
								window.location.reload();
								}
								
								})
										}); 
									});
        			</script>
					<td> <?php echo $row["invnumber"] ;?></td>
					<td> <?php echo $row["fquantity"] ;?></td>
					<td> <?php echo $row["fQtyLastProcess"] ;?></td>
					<td> <?php echo $row["fQtyLastProcessLineTotInclForeign"] ;?></td>					
				</tr>
				<?php 
				$rows++;}}
				else { ?>
				<a>
					<script> alert("Kindly click Create Shipment to create the shipment <?php echo $query ?>"); </script>
				</a>
				<?php } 
		//sqlsrv_close($conn);
	    ?>
		
        </tbody>
	</table>
	<button class="btn btn-success" type="submit" name="submit" >CREATE</button>	
	</form>
			</div>
<!-- ---toast- 
<div class="toast" id="myToast" >
    <div class="toast-body" style='background-color:green;'>
          <a style='color:white; font-weight:bold;font-size:17px;'>The weight has been updated.</a> 
    </div>
    </div> -->
	</body>
	<ul></ul>
	<a id="link" href="CostEstimateHome.php"><<<<<< Go back</a>
    </html>