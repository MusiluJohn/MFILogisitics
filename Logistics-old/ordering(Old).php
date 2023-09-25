<?php
session_start();

?>
<!DOCTYPE html>
<html>
<head>
<!-- <meta name="viewport" content="width=device-width, initial-scale=1"> -->
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<title>MFI Logistics Application</title>
<!-- <link rel="stylesheet" href = "/css/style.css"> -->
<link rel="stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
<!-- <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css"> -->
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
 <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>  
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="fct.js" defer></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<link rel="stylesheet" href ="">
 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> 
<!-- <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@1,300&display=swap" rel="stylesheet"> -->

</head>


<?php 

include('navbar.php');
 ?>


<body>
<!-- SEARCH FUNCTIONALITY AND DEFAULT RECORDS DISPLAY -->
<?php 


include('config.php');
if(isset($_SESSION['user'])) {

  $usercode = $_SESSION['user'];
if(isset($_GET['search']))
{

$filtervalues = $_GET['search'];
$Orders = "SELECT DISTINCT
Vendor.Account
,Vendor.Name
,InvNum.OrderDate
,InvNum.OrderNum
,MAX(InvNum.OrdTotExcl) as OrdTotExcl
,MAX(InvNum.OrdTotIncl) as OrdTotIncl
FROM dbo.InvNum
INNER JOIN dbo.Vendor
ON InvNum.AccountID = Vendor.DCLink
where InvNum.OrderNum like '%$filtervalues%' and DocState in (1,3,4) and docflag = 1
group by 
Vendor.Account
,Vendor.Name
,InvNum.OrderDate
,InvNum.OrderNum
-- ,InvNum.OrdTotExcl
-- ,InvNum.OrdTotIncl
        ";	


$result = sqlsrv_query($conn, $Orders);

while ($row = sqlsrv_fetch_array( $result)){

$PONO = $row['OrderNum'];
$AccountCode = $row['Account'];
$AccountName= $row['Name'];
$OrderDate = $row['OrderDate'];
$Exclusive = $row['OrdTotExcl'];
$Inclusive = $row['OrdTotIncl'];



        
?>

 <!-- ACCORDION CODE(COLLAPSABLE TABLE) -->
 
    <button class="accordion">
    <table class="table table-bordered table-condensed table-hover table-sm table-responsive-md">
      <tr>
      <td > PO Number: </td>
      <td id = "PO"><?php echo $row["OrderNum"]; ?></td>
      <td> PO Date: </td>
      <td> <?php echo $row["OrderDate"]->format('d/m/Y'); ?> </td>
      </tr>
      <tr>
      <td> Supplier Code: </td>
      <td> <?php echo $row["Account"]; ?> </td>
      <td> Supplier Name: </td>
      <td> <?php echo $row["Name"]; ?> </td>
      </tr>
      <tr>
      <td> Exclusive Amount: </td>
      <td> <?php echo number_format($row["OrdTotExcl"], 2); ?> </td>
      <td> Inclusive Amount: </td>
      <td> <?php echo  number_format($row["OrdTotIncl"], 2); ?> </td>
      </tr> 
</table>
    </button>
   
    
<div class="panel">
<div style="height:400px;overflow:auto;">

<table class='table table-bordered table-condensed table-hover table-sm table-responsive-md  ' id= "orders">


<thead class = 'thead sticky-top bg-white' >
    <tr>
     <th scope="col">Line ID</th>
      <th scope="col">Item Code</th>
	  <th scope="col">Item Description</th>
      <th scope="col">Quantity Ordered</th>
      <th scope="col">Quantity Processed</th>
      <th scope="col" align= "center">MV-MIPO Number</th>
      <th scope="col">MV-MIPO Quantity</th>
      <th scope="col" >MV-PI Number</th>
      <th scope="col">MV-PI Date</th>
      <th scope="col">New Item Code</th>
      
     
     
    </tr>
  </thead>
<?php 
 $sql = "SELECT
 CPL_SagePoLine.LineID
 ,CPL_SagePoLine.SequenceNO
 ,CPL_SagePoLine.PONO
 ,Stkitem.code as StockOrdercode
 ,_btblinvoicelines.fquantity as QuantityOrdered
 ,_btblinvoicelines.fqtyprocessed as QuantityProcessed
 ,CPL_SageInternationalOrder.MIPONO
 ,CPL_SageInternationalOrder.IntOrderID
 ,CPL_SageInternationalOrder.MIPOQuantity
 ,CPL_SageInternationalOrder.PINO
 ,CPL_SageInternationalOrder.DocState
 ,CPL_SageInternationalOrder.NewItemCode
--  ,(SELECT sum(Sage_International_Order.MIPOQuantity)FROM dbo.SagePoLine
--  INNER JOIN dbo.Sage_International_Order
--  ON SagePoLine.LineID = Sage_International_Order.LineID
--  JOIN  dbo.stkitem
--  ON SagePoLine.StockID = stkitem.stocklink
--   ) AS QuantityProcessed
  
 ,CPL_SageInternationalOrder.PIDate
 ,stkitem.stocklink as StockID
 ,Stkitem.description_1
 FROM dbo.CPL_SagePoLine
 INNER JOIN dbo.CPL_SageInternationalOrder  ON CPL_SagePoLine.LineID = CPL_SageInternationalOrder.LineID
 JOIN dbo._btblinvoicelines  ON dbo._btblinvoicelines.idinvoicelines = dbo.CPL_SagePoLine.LineID
 left JOIN dbo.invnum on dbo.invnum.autoindex = dbo._btblinvoicelines.idinvoicelines
 JOIN  dbo.stkitem  ON dbo._btblInvoiceLines.iStockCodeID = stkitem.stocklink
   WHERE CPL_SagePoLine.PONO = '$PONO'  
  --  and isnull(CPL_SageInternationalOrder.MIPONO,'') = '' 
  --  and dbo._btblinvoicelines.fqtyprocessed < fquantity
  -- CPL_SageInternationalOrder.DocState in (1,3,4)
  ORDER BY CPL_SagePoLine.SequenceNO 
  ";

$Lines = sqlsrv_query($conn,$sql);

while($row = sqlsrv_fetch_array($Lines)) { 
    $PONO = $row['PONO'];
	$ITEM_CODE = $row['StockOrdercode'];
    $ITEM_DESC = $row['description_1'];
    $QUANTITY = $row['QuantityOrdered'];
    $MIPONO = $row['MIPONO'];
    $MIPOQuantity = $row['MIPOQuantity'];
    $PINO= $row['PINO'];
    $PIDate= $row['PIDate'];
    $LineID= $row['IntOrderID'];
    $QuantityProcessed= $row['QuantityProcessed'];
  
   

?>






<tr>
<td  class="table-active" align="left"><?php echo $row["SequenceNO"];?></td>
<td  class="table-active" align="left"><?php echo $row["StockOrdercode"];?></td>
<td  class="table-active" align="left"><?php echo $row["description_1"];?></td>
<td class="table-active" align="center"><?php echo $row["QuantityOrdered"];?></td>
<td class="table-active" align="center"><?php echo $row["QuantityProcessed"];?></td>
<td align="left"><div class="col"><input type="text" class="form-control" onChange= "updateValues(this,'CPL_SageInternationalOrder.MIPONO',<?php echo($LineID); ?>) " value = <?php echo $row["MIPONO"]; ?> > </div> </td> 
<td ><div class="col" ><input type="text" align="centre" class="form-control"  onChange= "updateOrderQuantity(this,'CPL_SageInternationalOrder.MIPOQuantity',<?php echo($LineID); ?>) " value = <?php echo $row["MIPOQuantity"]; ?> > </div> </td>
<td align="left"><div class="col"><input type="text" class="form-control" onChange= "updateValues(this,'CPL_SageInternationalOrder.PINO',<?php echo($LineID); ?>) " value = <?php echo $row["PINO"]; ?> > </div> </td>
<td align="left"><div class="col"><input type="text" class="form-control" onChange= "" value = <?php echo $row["PIDate"]; ?> > </div> </td>
<td align="left"><div class="col"><input type="text" class="form-control" onChange= "updateValues(this,'CPL_SageInternationalOrder.NewItemCode',<?php echo($LineID); ?>) " value = <?php echo $row["NewItemCode"]; ?> > </div> </td>



<?php   }?>   


</table>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        
        <h4 class="modal-title">Insert New Row</h4>
      </div>
      <div class="modal-body">
      <form method = "POST" action = "" >
      <div class="row mb-3">
    <label for="Item Code" class="col-sm-2 col-form-label">Item Code</label>
    <div class="col-sm-10">

    <?php 
      $code_query = "select CPL_SagePoLine.StockOrdercode from CPL_SagePoLine where PONO  like '%$PONO%'";
      $code = sqlsrv_query($conn,$code_query);

  ?>
     <select name = "item_code" type="text" class="form-control" id = "item_code" >
       <?php
      while($row = sqlsrv_fetch_array($code)){

      
       ?>
       <option value = <?php echo $row["StockOrdercode"];?>> <?php echo $row["StockOrdercode"];?> </option>
         <?php }; ?>
        
</select>
    </div>
  </div>
 
  <div class="row mb-3">
    <label for="MIPONO" class="col-sm-2 col-form-label">MIPO Number</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="MIPONO">
    </div>
  </div>
  <div class="row mb-3">
    <label for="MIPOQuantity" class="col-sm-2 col-form-label">MIPO Quantity</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="MIPOQuantity">
    </div>
  </div>
  <div class="row mb-3">
    <label for="PI_Num" class="col-sm-2 col-form-label">PI Number</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="PI_Num">
    </div>
  </div>
  <div class="row mb-3">
    <label for="PI_Quantity" class="col-sm-2 col-form-label">PI Date</label>
    <div class="col-sm-10">
      <input type="date" class="form-control" id="PIDate">
    </div>
  </div>
 
  <button type="button" value = "submit" onclick="formsubmit()" id= "save" class="btn btn-success" data-dismiss="modal">Save</button>
  <button  class="btn btn-danger" data-dismiss="modal">Close</button>
</form>
      </div>
    </div>

  </div>
</div>
  
</div>

<div align="Center">
     <button type="button" name="add" id="add" class="btn btn-success " data-toggle="modal" data-target="#myModal">Add Row</button>
     <button  type="button" class = "btn btn-success "  onclick = "myFunction()"  action = "submit"> Save </button>
    </div>
<br>
<br>
</div>

<script>
function myFunction() {
  alert("Data Successfully Updated");
}
</script>



  






<?php sqlsrv_free_stmt( $Lines);  }
}
Else{
 echo('<br><br><br><br>
 <div align="center">
 <h3>PLEASE ENTER A PO NUMBER</h3>
 </div>');
}
}
Else{
  echo('LOGIN REQUIRED!');
  
}
?> 


<script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.maxHeight) {
      panel.style.maxHeight = null;
    } else {
      panel.style.maxHeight = panel.scrollHeight + "px";
    } 
  });
}




            
   
 



</script>


</body>


</html>
