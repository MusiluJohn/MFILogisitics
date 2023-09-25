<?php
include 'config2.php';
?>
<!doctype html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<title>MFI Logistics Application</title>
<link rel="stylesheet" href = "https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css">
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.min.js" integrity="sha384-Atwg2Pkwv9vp0ygtn1JAojH0nYbwNJLPhwyoVbhoPwBhjQPR5VtM2+xf0Uwh9KtT" crossorigin="anonymous"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script src="script.js" defer></script>

<link rel="stylesheet" href ="">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

	
<style>
    table {border-collapse:collapse; padding:20px; }
    td,th{padding:5px;width:200px;}
	div{}
    
    .activate{
        background:white;
        border-top:1px solid #333;
        border-left:1px solid #333;
        border-right:1px solid #ccc;
        border-bottom:1px solid #ccc;
        padding:2px;
    }
	#Break th {
    padding-top: 12px;
    padding-bottom: 12px;
    text-align: center;
    background-color: #26e400;
    color: white;
  }

</style>


<body>
<?php
  include('navbar.php');
 include('pagination.php');
?>

<!-- <nav aria-label="Page navigation example">
  <ul class="pagination">
    <li class="page-item">
      <a class="page-link" href="orders.php?page=<?= $Previous; ?>" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <?php for($i = 1; $i<= $pages; $i++) : ?>
				    	<li><a href="orders.php?page=<?= $i; ?>"><?= $i; ?></a></li>
				    <?php endfor; ?>
      <a class="page-link" href="orders.php?page=<?= $Next; ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav> -->

<!-- <div class="row"> -->
			<!-- <div class="text-center" style="margin-top: 20px; " class="col-md-2"> -->
				
				<!-- </div> -->
		<!-- </div> -->
		
    <!-- Button trigger modal -->

<!-- Modal -->

<form align = "right" method="post" action="#">
						<select name="limit-records" id="limit-records">
							<option disabled="disabled" selected="selected">---Results Per Page---</option>
							<?php foreach([10,50,100,200,500] as $limit): ?>
								<option <?php if( isset($_POST["limit-records"]) && $_POST["limit-records"] == $limit) echo "selected" ?> value="<?= $limit; ?>"><?= $limit; ?></option>
							<?php endforeach; ?>
						</select>
					</form>
    <div class="table-responsive col-md-12 col-lg-12">
    <table class="table table-striped table-hover table-bordered border-secondary table-sm id" id = "orders">
    <thead id="Break">
    <tr>
      <th align="left" scope="col">Po Number</th>
      <th scope="col">Item Code</th>
	  <th scope="col">Item Description</th>
      <th scope="col">Quantity Ordered</th>
      <th scope="col">Quantity Processed</th>
      <th scope="col">MIPO Number</th>
      <th scope="col">MIPO Quantity</th>
      <th scope="col">PI Number</th>
      <th scope="col">PI Date</th>
      <th scope="col">ACTIONS</th>
     
     
    </tr>
  </thead>

<?php


if(sqlsrv_num_rows($result) > 0){
  
	
}


while($row = sqlsrv_fetch_array($result)) { 
                $PO_NO = $row['PO_NO'];
				$ITEM_CODE = $row['Stock_Ordercode'];
        $ITEM_DESC = $row['description_1'];
				$QUANTITY = $row['Quantity_Ordered'];
				$MIPO_NO = $row['MIPO_NO'];
				$MIPO_QUANTITY = $row['MIPO_Quantity'];
        $PI_NO= $row['PI_NO'];
				$PI_DATE= $row['PI_Date'];
				$LINE_ID= $row['Line_ID'];
  
  ?>

<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo('$filter'); ?></h4>
      </div>
      <div class="modal-body">
      <form method = "POST" action = "" >
      <div class="row mb-3">
    <label for="Item Code" class="col-sm-2 col-form-label">Item Code</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="Item_Code">
    </div>
  </div>
  <div class="row mb-3">
    <label for="MIPO_NO" class="col-sm-2 col-form-label">MIPO Number</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="MIPO_NO">
    </div>
  </div>
  <div class="row mb-3">
    <label for="MIPO_Quantity" class="col-sm-2 col-form-label">MIPO Quantity</label>
    <div class="col-sm-10">
      <input type="text" class="form-control" id="MIPO_Quantity">
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
      <input type="date" class="form-control" id="PI_Date">
    </div>
  </div>
 
  <button type="button" value = "submit" onclick="formsubmit()" id= "save" class="btn btn-success">Save</button>
  <button  class="btn btn-danger" data-dismiss="modal">Close</button>
</form>
      </div>
    </div>

  </div>
</div>

  
<tr>
<td  class="table-active"  align="left" id = "PO_NO"> <?php echo $row["PO_NO"]; ?></td>
<td  class="table-active" align="left"><?php echo $row["Stock_Ordercode"];?></td>
<td  class="table-active" align="left"><?php echo $row["description_1"];?></td>
<td class="table-active" align="center"><?php echo $row["Quantity_Ordered"];?></td>
<td class="table-active" align="center"><?php echo $row["Quantity_Ordered"];?></td>
<td align="left"><div contenteditable = "true" onBlur= "updateValues(this,'Sage_International_Order.MIPO_NO',<?php echo($LINE_ID); ?>) "><?php echo $row["MIPO_NO"]; ?></div> </td>
<td align="center"><div contenteditable = "true" onBlur= "updateValues(this,'Sage_International_Order.MIPO_Quantity',<?php echo($LINE_ID); ?>)"><?php echo $row["MIPO_Quantity"]; ?></div></td>
<td align="center"><div contenteditable = "true" onBlur= "updateValues(this,'Sage_International_Order.PI_NO',<?php echo($LINE_ID); ?>)"><?php echo $row["PI_NO"]; ?></div></td>
<td align="center"><div contenteditable = "true" onBlur= "updateValues(this,'Sage_International_Order.PI_Date',<?php echo($LINE_ID); ?>)"><input type = "date" value = "<?php  echo date_format( $row['PI_Date'],'Y-m-d' ); ?>"></input></div></td>

<!-- <td> <div align="left">
     <button type="button" name="add" id="add" class="btn btn-success btn-xs" data-toggle="modal" data-target="#myModal">+</button>
    </div>
</td> -->

<?php
      echo "<td> <button type='button' name='add' id='add' class='btn btn-success btn-xs' data-toggle='modal' data-target='#myModal' >+</button></td>";
} ?>
</tbody>
</table>




</body>
<script type="text/javascript">
	$(document).ready(function(){
		$("#limit-records").change(function(){
			$('form').submit();
		})
	})


  function formsubmit() {
            var MIPO_NO = document.getElementById('MIPO_NO').value;
            var MIPO_Quantity = document.getElementById('MIPO_Quantity').value;
            var PI_Num = document.getElementById('PI_Num').value;
            var PI_Date = document.getElementById('PI_Date').value;
            var Item_Code = document.getElementById('Item_Code').value;
            var PO_NO = document.getElementById('search').value;
            //store all the submitted data in astring.
            var formdata =
                'MIPO_NO=' + MIPO_NO
                + '&MIPO_Quantity=' + MIPO_Quantity
                + '&PI_Num=' + PI_Num
                + '&PI_Date=' + PI_Date
                +'&Item_Code=' + Item_Code
                + '&PO_NO=' + PO_NO;
            // validate the form input
            // if (empname == '' ) {
            //     alert("Please Enter Employee Name");
            //     return false;
            // }
            // if(email == '') {
            //     alert("Please Enter Email id");
            //     return false;
            // }
            // if(username == '') {
            //     alert("Please Enter Username");
            //     return false;
            // }
            // if(pwd == '') {
            //     alert("Please Enter Password");
            //     return false;
            // }
            
            // AJAX code to submit form.
            $.ajax({
                 type: "POST",
                 url: "insert.php", //call storeemdata.php to store form data
                 data: formdata,
                 cache: false,
                 success: function(html) {
                  alert(html);
                 }
            });
            
            return false;
        }
   
 
</script>
</html>