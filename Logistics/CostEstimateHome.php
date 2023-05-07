<html>
<head>
<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="css/bootsrap2.css"/>
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<script src="js/bootstrap1.js"></script>
<script src="js/bootstrap2.js.js"></script>
<script src="js/bootstrap3.js"></script>
<script src="js/jquery1.js"></script>
<script src="js/jquery2.js"></script>
<link href="//netdna.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
<script src="//netdna.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<title>
	
</title>
<body>
<?php include 'navbar2.php' ?>
<div id="pono" style='margin-top:90px;'>
	<span class='symbol-input100' style='margin-left:15px;'>
    <i class='fa fa-user-circle' aria-hidden='true'><?php echo  $_SESSION['user'] ; ?></i></span>
    <span class='symbol-input100' style='margin-left:15px;'>
    <i class='fa fa-database' aria-hidden='true'><?php echo $_SESSION['db'] ; ?></i></span>
    <a class="nav-link" href="index.php" style='color:blue'>sign out</a>
<table id="body" class="table table-bordered table-striped table-hover" style="margin-top:5px;">
<tr id="rbody"><td id="tbody">
    <p>| SELECT SHIPMENT NUMBER |</p>
	<form id="ship" method="get" action="PO.php">
    <?php
		
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
		
			 $sql = "select distinct uservalue as ucIDPOrdShipmentNo  from _etblUserHistLink ek join _rtblUserDict rt on ek.UserDictID=rt.idUserDict 
			 where cTableName='Invnum'
			 and cFieldName='ucIDPOrdShipmentNo' and uservalue not in (select shipment_no from _cplshipmentmaster)
			 and ek.UserValue in (select cshipmentno from _cplShipment)";	
			 $stmt = sqlsrv_query($conn,$sql);
			if ($stmt) {			 
			 echo "<select name='PO' class='form-control select2' style='width:300px;'>";
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {				
					echo "<option  value=" .$row["ucIDPOrdShipmentNo"]. "> " .$row["ucIDPOrdShipmentNo"]. "</option>";
				} 
				
			echo"</select>";
			}
		sqlsrv_close($conn);
	    ?>
<script>
    $('.select2').select2();
</script>
<button class="btn btn-success" type="submit" ><span class="glyphicon glyphicon-search" style="margin-right:2px;"></span>VIEW</button>
</form>
</td></tr>
<tr id="rbody"><td id="tbody">
<p>| SELECT SHIPMENT NUMBER TO VIEW SHIPMENT AND ALLOCATE COSTS |</p>
<form id="ship2" method="get" action="Shipment_no.php">
    <?php
		
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
		
			 $sql = "select distinct _cplshipmentmaster.id, _cplshipmentmaster.shipment_no
			 from _cplshipmentmaster join _cplshipmentlines on _cplshipmentmaster.shipment_no=_cplshipmentlines.shipment_no
			 where isnull(active,'True')='True' and isnull(updated,'False') in ('False','True')";	
			// $params = array();
			// $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			 $stmt = sqlsrv_query($conn,$sql);
			if ($stmt) {
			 echo"<select id='SH' name='SH' class='form-control select2' style='width:300px;'>";
			 //echo "<option  value=" .$row["id"]. "> " .$row["shipment_no"]. "</option>";
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<option  value=" .$row["id"]. "> " .$row["shipment_no"]. "</option>";
				}
			echo"</select>";
			}
		sqlsrv_close($conn);
	    ?>
<script>
    $('.select2').select2();
</script>
<button class="btn btn-success" type="submit" ><span class="glyphicon glyphicon-search" style="margin-right:2px;"></span>VIEW</button>
</form>
</td></tr>
<tr id="rbody"><td id="tbody">
<p>| SELECT SHIPMENT NUMBER TO VIEW CLOSED SHIPMENTS |</p>
<form id="ship3" method="get" action="Shipment_no.php">
    <?php
		
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
		
			 $sql = "select distinct _cplshipmentmaster.id, _cplshipmentmaster.shipment_no
			 from _cplshipmentmaster inner join _cplshipmentlines on _cplshipmentmaster.shipment_no=_cplshipmentlines.shipment_no
			 where active='False'";	
			// $params = array();
			// $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			 $stmt = sqlsrv_query($conn,$sql);	
			if ($stmt) {
			 echo"<select name='SH' class='form-control select2' style='width:300px;'>";
			 //echo "<option  value=" .$row["id"]. "> " .$row["shipment_no"]. "</option>";
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<option  value=" .$row["id"]. "> " .$row["shipment_no"]. "</option>";
				}
			echo"</select>";
			}
		sqlsrv_close($conn);
	    ?>
<script>
    $('.select2').select2();
</script>
<button class="btn btn-success" type="submit" ><span class="glyphicon glyphicon-search" style="margin-right:2px;"></span>VIEW</button>
</form>
</td></tr>
</table>
</div>
<title>
</title>
<script src="script.js">
</script>
</body>
</html>