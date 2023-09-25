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
<link rel="stylesheet" href = "style.css">
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
<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@1,300&display=swap" rel="stylesheet">

</head>

<?php include('navbar.php'); ?>


<body>


<!-- SEARCH FUNCTIONALITY AND DEFAULT RECORDS DISPLAY -->
<?php  
include('config2.php');
if(isset($_GET['search']))
{

$filtervalues = $_GET['search'];
$Orders = "SELECT DISTINCT
Vendor.Account
,Vendor.Name
,InvNum.orderDate
,InvNum.OrderNum
,MAX(InvNum.OrdTotExcl) as OrdTotExcl
,MAX(InvNum.OrdTotIncl) as OrdTotIncl
FROM dbo.InvNum
INNER JOIN dbo.Vendor
ON InvNum.AccountID = Vendor.DCLink
where InvNum.OrderNum like '%$filtervalues%' and doctype = 5 and docflag = 1
 and docstate = 4
group by 
Vendor.Account
,Vendor.Name
,InvNum.OrderDate
,InvNum.OrderNum       
        ";  


$result = sqlsrv_query($conn, $Orders);

while ($row = sqlsrv_fetch_array( $result)){

$PONO = $row['OrderNum'];
$AccountCode = $row['Account'];
$AccountName= $row['Name'];
$OrderDate = $row['orderDate'];
$Exclusive = $row['OrdTotExcl'];
$Inclusive = $row['OrdTotIncl'];



        
?>

 <!-- ACCORDION CODE(COLLAPSABLE TABLE) -->

    <button class="accordion">
    <table class="table table-bordered table-condensed table-hover table-sm table-responsive-md">
      <tr>
      <td> PO Number: </td>
      <td id = "PO_Invoice"> <?php echo $row["OrderNum"]; ?> </td>
      <td> PO Date: </td>
      <td> <?php echo $row["orderDate"]->format('d/m/Y'); ?> </td>
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

<table class='table table-bordered table-condensed table-hover table-sm table-responsive-md  ' id= "orders_invoice">


<thead class = 'thead' >
    <tr>
      <th scope="col">Line ID</th>
      <th scope="col">PO NO</th>
      <th scope="col">Item Code</th>
    <th scope="col">Item Description</th>
      <th scope="col">Qty Ordered</th>
      <!-- <th scope="col">Qty Processed</th> -->
      <th scope="col" >MV-MIPO NO</th>
      <th scope="col">MV-MIPO Qty</th>
      
      <th scope="col" >MV-PI NO</th>
      <th scope="col">MV-PI Date</th>
      <th scope="col" >MV-CI NO</th>
      <th scope="col">MV-CI Qty</th>
      <th scope="col">MV-CI Date</th>
      
      <th scope="col">Pickup NO</th>

      
     
     
    </tr>
  </thead>
<?php 

$sql = "SELECT
StkItem.StockLink
,StkItem.Code AS StockOrdercode
,StkItem.Description_1
,CPL_SagePoLine.SequenceNO
,CPL_SageInternationalInvoice.CINO
,ISNULL(CPL_SageInternationalInvoice.CIDate, '2021-01-01') AS CIDate
,CPL_SageInternationalInvoice.CIQuantity
,CPL_SageInternationalInvoice.PickupNO
,InvNum.ordernum AS GRNNumber
,_btblInvoiceLines.fQuantity AS QuantityOrdered
-- ,_btblInvoiceLines.fQtyprocessed AS QuantityProcessed
,CPL_SageInternationalOrder.MIPONO
,CPL_SageInternationalOrder.MIPOQuantity
,CPL_SageInternationalOrder.PINO
,CPL_SageInternationalOrder.PIDate
,CPL_SageInternationalOrder.NewItemcode as FinalItemCode
,CPL_SagePoLine.PONO
,CPL_SageInternationalInvoice.ID
FROM dbo.CPL_SageInternationalInvoice
INNER JOIN dbo.CPL_SageInternationalOrder
ON CPL_SageInternationalOrder.IntOrderID = CPL_SageInternationalInvoice.IntOrderID
INNER JOIN dbo.CPL_SagePoLine
ON CPL_SagePoLine.LINEID = CPL_SageInternationalOrder.LINEID
INNER JOIN dbo._btblInvoiceLines
ON CPL_SagePoLine.LINEID = _btblInvoiceLines.idInvoiceLines
INNER JOIN dbo.InvNum
ON InvNum.AutoIndex = _btblInvoiceLines.iInvoiceID
INNER JOIN dbo.StkItem
ON StkItem.StockLink = _btblInvoiceLines.iStockCodeID
WHERE 
CPL_SageInternationalOrder.Docstate = 4
-- CPL_SageInternationalOrder.MIPONO <> ''
AND invnum.ordernum = '$PONO'
-- AND invnum.invnumber != ''

 ORDER BY  Invnum.invnumber,CPL_SagePoLine.sequenceno";

$Lines = sqlsrv_query($conn,$sql);

while($row = sqlsrv_fetch_array($Lines)) { 
    $PONO = $row['PONO'];
    $GRNNO = $row['GRNNumber'];
  $ITEMCODE = $row['StockOrdercode'];
    $ITEMDESC = $row['Description_1'];
    $QUANTITY = $row['QuantityOrdered'];
    $MIPONO = $row['MIPONO'];
    $MIPOQUANTITY = $row['MIPOQuantity'];
    $PINO= $row['PINO'];
    $PIDATE= $row['PIDate'];
    $LINEID= $row['ID'];
    $CINO= $row['CINO'];
    $CIQUANTITY= $row['CIQuantity'];
    $CIDate= $row['CIDate'];
    $FINAL_ITEM_CODE= $row['FinalItemCode'];
    $PICKUP_NO= $row['PickupNO'];


  

?>






<tr>
<td  class="table-active" align="left"><?php echo $row["SequenceNO"];?></td>
<td  class="table-active" align="left"><?php echo $row["GRNNumber"];?></td>
<td  class="table-active" align="left"><?php echo $row["StockOrdercode"];?></td>
<td  class="table-active" align="left"><?php echo $row["Description_1"];?></td>
<td class="table-active" align="center"><?php echo $row["QuantityOrdered"];?></td>
<!-- <td class="table-active" align="center"><?php echo $row["QuantityProcessed"];?></td> -->
<td class="table-active" align="center"><?php echo $row["MIPONO"]; ?></td>
<td class="table-active" align="center"><?php echo $row["MIPOQuantity"]; ?></td>
<td class="table-active" align="center"><?php echo $row["PINO"]; ?></td>
<td class="table-active" align="center"><?php echo $row["PIDate"]->format('d/m/Y'); ?></td>
<td align="left"><div class="col"><input type="text" class="form-control" onChange= "updateInvoices(this,'CINO',<?php echo($LINEID); ?>) " value = <?php echo $row["CINO"]; ?> > </div> </td>
<td align="left"><div class="col"><input type="text" class="form-control" onChange= "updateInvoiceQuantity(this,'CIQuantity',<?php echo($LINEID); ?>) " value = <?php echo $row["CIQuantity"]; ?> > </div> </td>
<td align="left"><div class="col"><input type="text" class="form-control" onChange= "updateInvoiceDate(this,'CIDate',<?php echo($LINEID); ?>) " value = <?php echo date_format( $row['CIDate'],'d-m-Y' ); ?> > </div> </td>

<td align="left"><div class="col"><input type="text" class="form-control" onChange= "updateInvoices(this,'PickupNO',<?php echo($LINEID); ?>) " value = <?php echo $row["PickupNO"]; ?> > </div> </td>

<?php   }?>   


</table>

<div id="myModal_invoice" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
      <form method = "POST" action = "" >
      <div class="row mb-3">
    <label for="Item Code" class="col-sm-2 col-form-label">Item Code</label>
    <div class="col-sm-10">

    <?php 
      $code_query = "select CPL_SagePoLine.StockOrdercode from CPL_SagePoLine where PONO  like '%$PONO%'";

      echo($code_query);
      $code = sqlsrv_query($conn,$code_query);

  ?>
     <select name = "ItemCode" type="text" class="form-control" id = "ItemCode" >
       <?php
      while($row = sqlsrv_fetch_array($code)){

      
       ?>
       <option value = <?php echo $row["StockOrdercode"];?>> <?php echo $row["StockOrdercode"];?> </option>
         <?php }; ?>
        
</select>
    </div>
  </div>
  
  <div class="row mb-3">
    <label for="MIPONO" class="col-sm-2 col-form-label">CI Number</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="CINO">
    </div>
  </div>
  <div class="row mb-3">
    <label for="MIPOQuantity" class="col-sm-2 col-form-label">CI Quantity</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="CIQuantity">
    </div>
  </div>
  
  <div class="row mb-3">
    <label for="PIQuantity" class="col-sm-2 col-form-label">CI Date</label>
    <div class="col-sm-10">
      <input type="date" class="form-control" id="CIDate">
    </div>
  </div>
  <!-- <div class="row mb-3">
    <label for="PINum" class="col-sm-2 col-form-label">Final Item Code</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="FinalItemCode">
    </div>
  </div> -->
  <div class="row mb-3">
    <label for="PINum" class="col-sm-2 col-form-label">Pickup Number</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="Pickup_No">
    </div>
  </div>

 
  <button type="button" value = "submit" onclick="Invoicesubmit()" id= "save" class="btn btn-success">Save</button>
  <button  class="btn btn-danger" data-dismiss="modal">Close</button>
</form>
      </div>
    </div>

  </div>
</div>
  
</div>
<div align="Center">
     <button type="button" name="add" id="add" class="btn btn-success " data-toggle="modal" data-target="#myModal_invoice">Add Row</button>
     <button  type="button" class = "btn btn-success " onclick="myFunction()"  action = "submit"> Save </button>
    </div>
<br>
<br>
</div>

<br>
<br>
</div>


<script>
function myFunction() {
  alert("Data Successfully Updated!");
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
