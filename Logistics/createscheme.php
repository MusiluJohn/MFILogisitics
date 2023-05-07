<html>
<head>
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
	<link rel="stylesheet" type="text/css" href="css/bootsrap2.css"/>
<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
</head>
	<script src="js/bootstrap1.js"></script>
	<script src="js/bootstrap2.js>"></script>
	<script src="js/bootstrap3.js>"></script>
	<script src="js/jquery1.js>"></script>
	<script src="js/jquery2.js>"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<title>
	
</title>
<body>
<?php include 'navbar2.php' ?>
<script>
$(document).ready(function () {
	// $('#cost').change(function(){
		var id=$('#cost option:selected').text();
		//alert(id);
		if(id==" Vat"){			
		//alert("hi");
			$("#Vat").attr('disabled',false)
			$("#Rate").attr('disabled',true);
		}
		else{
			$("#Vat").attr('disabled',true);
			//alert("no");
		}
	// });
});
</script>
<hr></hr>
<div id="schemes" style='margin-top:90px;'>
	<span class='symbol-input100' style='margin-left:15px;'>
    <i class='fa fa-user-circle' aria-hidden='true'><?php echo  $_SESSION['user'] ; ?></i></span>
    <span class='symbol-input100' style='margin-left:15px;'>
    <i class='fa fa-database' aria-hidden='true'><?php echo $_SESSION['db'] ; ?></i></span>
    <a class="nav-link" href="index.php" style='color:blue'>sign out</a>
<form id="ship" method="POST">
<table id="scheme" class="table table-bordered table-striped table-hover" style='font-size:12px;'>
<tr id="trsch"><td id="tdsch">
		Enter the scheme:
		</td><td id="tdsch"><input name="scheme" class='form-control' style='width:150px;'/>
</td></tr>
<tr id="trsch"><td id="tdsch">
		Select the type of cost:
		</td>
		<td id="tdsch">
		<?php
		//Cost drop down
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
		
			 $sql = "Select id, cost from _cplcostmaster";	
			// $params = array();
			// $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			 $stmt = sqlsrv_query($conn,$sql);		
			 echo"<select id='cost' name='cost' class='form-control' style='width:150px;'>";
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<option  value=" .$row["id"]. "> " .$row["cost"]. "</option>";
				}
			echo"</select>";
			?>
</td></tr>
<script>
$(document).ready(function () {
	$('#cost').change(function(){
		var id=$('#cost option:selected').text();
		//alert(id);
		if(id==" EXCISE_DUTY"){			
		//alert("hi");
			$("#Vat").attr('disabled',false)
			$("#Rate").attr('disabled',true);
		}
		else{
			$("#Vat").attr('disabled',true);
			//alert("no");
		}
	});
});
</script>
<tr id="trsch"><td id="tdsch">
		Select the calculation base:		
		</td>
		<td id="tdsch">
		<?php
		//Calculation base dropdown
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
			 $sql = "Select id, calcbase from _cplcalcbase";	
			// $params = array();
			// $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			 $stmt = sqlsrv_query($conn,$sql);		
			 echo"<select name='calcbase' class='form-control' style='width:150px;'>";
			//echo "<option  value=" .$row["id"]. "> " .$row["calcbase"]. "</option>";
				while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
					echo "<option  value=" .$row["id"]. "> " .$row["calcbase"]. "</option>";
				}
			echo"</select>";
			?>
	</td></tr>
	<tr id="trsch"><td id="tdsch">
			Enter the rate/duty(%): 
			</td>
			<td id="tdsch">
			<input id="Rate" name="Rate" class='form-control' style='width:150px;' value=0 />
	</td></tr>
	<tr id="trsch"><td id="tdsch">
			Enter the Excise duty %: 
			</td>
			<td id="tdsch">
			<input  id="Vat" name="Vat" class='form-control' style='width:150px;' value=0 />
	</td></tr>
	<?php
	//insert into _cplscheme
	if (isset($_POST['submit'])){
		$scheme= $_POST['scheme'];
		$cost= $_POST['cost'];
		$calcbase= $_POST['calcbase'];
		$rate= $_POST['Rate'];
		$vat= $_POST['Vat'];
		$conn = sqlsrv_connect( $servername, $connectioninfo);
		$insscheme="insert into _cplScheme (scheme,Cost_Code,calcbase, rate,vat)
		values ('$scheme', $cost, $calcbase,$rate,$vat)";
		sqlsrv_query($conn, $insscheme) or die(print_r( sqlsrv_errors(), true));
	}
	?>
	</table>
	<button type="submit" class="btn btn-success" name='submit' onclick="update()">SUBMIT</button>
</form>
<hr></hr>
</div>

<a id="link" href="CostEstimateHome.php"><<<<<< Go back</a>
</body>
<script src="js/script.js">
</script>
</html>