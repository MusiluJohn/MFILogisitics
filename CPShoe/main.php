<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>WITHHOLDING REPORT</title>
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
<script>
     $(document).ready(function(){
        $('#checkall').click(function() {
            if ($(this).is(':checked')){
                var checkboxes = $(this).closest('form').find(':checkbox');
                //checkboxes.prop('checked', $(this).is(':checked'));
                checkboxes.slice(1,11).prop("checked",true);
            } else {
                var checkboxes = $(this).closest('form').find(':checkbox');
                //checkboxes.prop('checked', $(this).is(':checked'));
                checkboxes.slice(1,11).prop("checked",false);
            }
        });
     });
</script>
<script>
$(document).ready(function(){
$('input[type=checkbox]').change(function(e){
   if ($('input[type=checkbox]:checked').length > 10) {
        $(this).prop('checked', false)
        alert("Only 10 invoices are allowed");
   }
});
});
</script>
<body class="hold-transition skin-red-light fixed sidebar-mini" onload="selectall()" style="overflow:scroll">
<!-- Site wrapper -->
<div class="wrapper" style="margin-bottom:30px;">
<header class="main-header">
    <!-- Logo -->
    <a href="main.php" class="logo">
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">C&P Shoe SWMS</span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
          <!-- <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
</a> -->
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <!-- User Account: style can be found in dropdown.less -->
                <table><tr><td>
                <li class="dropdown user user-menu">
                    <a href="#" >
                        Logged in as: <?php echo $_SESSION['user'] ?>
                    </a>
                </li>
                </td>
                <td>
                    <a href="index.php" >
                    &nbsp;&nbsp;&nbsp;  sign out
                    </a>              
                </td>
                </tr></table>
            </ul>
        </div>
    </nav>
</header>
</div>
<!-- =============================================== -->
<!-- Left side column. contains the sidebar -->
<div style="overflow:scroll">
<aside class="left-side sidebar-offcanvas" style="border:1px;margin-top:1px;overflow-y: auto;positon:fixed;">
    <section class="sidebar" style="margin-top:1px;flex-grow:1">        
        <ul class='sidebar-menu'>
        <div class="box body no-padding" style="">
            <a href="" class="font-weight-bold">Suppliers</a>
            <form id="supplier-form" method="get" action="">
            <?php
            
            include("connect.php");

            $sql = "exec supplier";	
            $stmt = sqlsrv_query($conn,$sql);
            if ($stmt) {			 
            echo "<select name='Supplier' class='form-control' id='Supplier'  style='width:200px;height:40px;margin-top:10px;'>";
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {				
            echo "<option  value='" .$row["account"]. "'> " .$row["Name"]. "</option>";
            }                     
            echo"</select>";
            }
            sqlsrv_close($conn);
            ?>
<script>
    $('#select').select();
</script>
<script type="text/javascript">
    document.getElementById('Supplier').value = "<?php echo $_GET['Supplier']; ?>";
</script>
<?php $_SESSION['supp']= $_GET['Supplier'] ?? ''; ?>

<a href="">From</a><input class='form-control'  type='date' id="fromdate" name="fromdate" /> 
<script type="text/javascript">
    document.getElementById('fromdate').value = "<?php echo $_GET['fromdate']; ?>";
</script>
<?php $_SESSION['frmdate']=$_GET['fromdate'] ?? '' ?>
<?php $_SESSION['todate']=$_GET['todate'] ?? ''?>
<a href="">To</a><input class='form-control' type='date' id="todate" name="todate" /> 
<script type="text/javascript">
    document.getElementById('todate').value = "<?php echo $_GET['todate']; ?>";
</script>
<button class="btn btn-success" type="submit" style="" id="submit" name="submit">VIEW</button>
        </div>
</form>
<div class="box body no-padding" style="overflow:scroll">
<a href="" class="font-weight-bold">Reports</a>
<!--with holding vat report-->
<form method="GET" action="withholdingvat.php">
<a href="">From</a><input class='form-control'  type='date' id="repfrmdate" name="repfrmdate" /> 
<a href="">To</a><input class='form-control' type='date' id="reptodate" name="reptodate" /> 
<input class='btn btn-success' type='submit' value='Withholding Vat Export' /></i>
</form>
<!--with holding vat summary report-->
<form method="GET" action="withholding_summary.php">
<a href="">From</a><input class='form-control'  type='date' id="repfrmdate" name="repfrmdate" /> 
<a href="">To</a><input class='form-control' type='date' id="reptodate" name="reptodate" />
<script type="text/javascript">
    document.getElementById('repfrmdate').value = "<?php echo $_GET['repfrmdate']; ?>";
</script>
<script type="text/javascript">
    document.getElementById('reptodate').value = "<?php echo $_GET['reptodate']; ?>";
</script>
<?php $_SESSION['repfrmdate']=$_GET['repfrmdate'] ?? '' ?>
<?php $_SESSION['reptodate']=$_GET['reptodate'] ?? ''?>
<input class='btn btn-success' type='submit' value='Withholding Summary Report' /></i>
</form>
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
    <a href="Search_batch.php" style="font-weight:bold">
            Click To Search Batch To Reprint
    </a>
        </h1>
    <br>
    <h1>
        SELECT INVOICES TO PAY
    </h1>
    <a>CLICK NEXT TO PROCEED TO BATCH. IF INVOICE IS NOT SHOWING IT MEANS IT HAS ALREADY
        BEEN PAID FOR
    </a>
</section>
<!-- Main content -->

        <br>
<section class="content">
    <!-- Default box -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title">Supplier Invoices</h3>
                    
                    <div class="box body no-padding" style="overflow:scroll">
                    <form action='Create_batch.php' method='GET'>   
                    <table class="table table-stripped table-hover table-condensed">
                            <tr class="info table-condensed">
                                <th><input type='checkbox' id="checkall" /></th>
                                <th>SUPPLIER</th>
                                <th hidden>SUPPLIER_id</th>
                                <th>PIN NO</th>
                                <th>INV NO.</th>
                                <th>INV DATE</th>
                                <th>% VAT</th>
                                <th>INV. VAT</th>
                                <th>INV INCL</th>
                                <th>VAT-W</th>
                                <th>NET TOT</th>
                                <th>NON VAT AMT</th>
                                <th>INCOME WITHHELD</th>
                                <th>AMT PAYABLE</th>
                                <th>PAYMENT DATE</th>
                                <th>CURR</th>
                                <th>RATE</th>
                                <th>WITHHOLDING RATE</th>
                            </tr>
                            <tbody>
                                <?php
                                require "connect.php";
                                if (isset($_GET['submit']))
                                {
                                    $supplier =$_GET['Supplier'];
                                    $fromdate=$_GET['fromdate'];
                                    $todate=$_GET['todate'];

                                    $sql = "exec supplier_invoices '$supplier','$fromdate','$todate'";
                                    $params = array();	
                                    $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
                                    $rowcnt = "select @@ROWCOUNT as cnt";
                                    $statement = sqlsrv_query($conn, $rowcnt, $params) or die(print_r( sqlsrv_errors(), true));
                                    while( $row = sqlsrv_fetch_array( $statement, SQLSRV_FETCH_ASSOC) ) {
                                    if ($row['cnt']>0) {
                                        $_SESSION['cnt']=$row['cnt'];
                                    $rows=0;
                                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {?>
                                        <tr class="table-condensed text-right">
                                        <td><input type='checkbox' id="checkboxes" name="invoices[]" value="<?php echo $row["auto_id"] ;?>" /></td>
                                        <td><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;' id="sup" name="sup[]" value='<?php echo $row['Supplier'] ?? '' ?>' disabled/></td>
                                        <td hidden ><input hidden id="supid" name="supid[]" value=<?php echo $row['auto_id'] ?? 0 ?> /></td>
                                        <td><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;' id="pin[]" name="pin[]" value='<?php echo $row['Pin'] ?? '' ?>' disabled/></td>
                                        <td><input  id="invnum" name="invnum[]" value='<?php echo $row['InvNo'] ?>' disabled /></td>
                                        <td><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:90px;' id="invdate" name="invdate[]"  value='<?php echo $row['InvDate'] ?? '' ?>' disabled/></td>
                                        <td><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:40px;' id="vat[]" name="vat[]" value=<?php echo $row['taxrate'] ?? '' ?> disabled/></td>
                                        <td><?php echo number_format($row['Inv_Vat'] ?? 0,2) ?><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:70px;' id="inv_vat[]" name="inv_vat[]"   disabled value=<?php echo $row['Inv_Vat'] ?? 0 ?> hidden></td>
                                        <td><?php echo number_format($row['Inc_amt'] ?? 0,2) ?><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:80px;' id="Inc_amt[]" name="Inc_amt[]"  value=<?php echo $row['Inc_amt'] ?? 0 ?> hidden disabled/></td>
                                        <td><?php echo number_format($row['vat_w'] ?? 0.00,2) ?><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:80px;' id="vat_w[]" name="vat_w[]"  value=<?php echo $row['vat_w'] ?? 0.00 ?> hidden disabled/></td>
                                        <td><?php echo number_format($row['Inv_gr_tot'] ?? 0,2) ?><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:80px;' id="inv_gr_tot[]" name="inv_gr_tot[]"  value=<?php echo $row['Inv_gr_tot'] ?? 0 ?> hidden disabled/></td>
                                        <td><?php echo number_format($row['nva'] ?? 0,2) ?><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:80px;' id="nva[]" name="nva[]"  value=<?php echo $row['nva'] ?? 0 ?> hidden disabled/></td> 
                                        <td><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:80px;' class='form-control text-right'  id="incwith<?php echo $rows;?>" name="incwith[]" value=<?php echo $row['income_withheld']; ?> disabled/></td>
                                        <td><?php echo number_format($row['amt_payable'] ?? 0.00,2) ?><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:80px;' id="amt_pay<?php echo $rows;?>" name="amt_pay[]" value=<?php echo $row['amt_payable'] ?? 0.00 ?> hidden disabled/></td> 
                                        <td><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:130px;' class='form-control' id="date[]" name="date[]" type="date" value='<?php echo $row['paymentdate']?>' disabled/></td>
                                        <td><input style='width:50px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;height:19px;' class='form-control' id="curr[]" name="curr[]" value=<?php echo $row['Curr'] ?? '' ?> disabled /></td>
                                        <td><?php echo $row['exchrate']; ?></td>  
                                        <td><select name='Supplier[]' id='Supplier[]' class='form-control select2' style='width:70px;height:29px;'>
                                        <option value=2>2</option>
                                        </select>
                                        </td>  
                                    </tr>
                                    <?php $rows++;}}}}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <button class="form-control btn btn-success" id="save" name="save"  style="margin-bottom:10px;width:70px;margin-left:15px;align:center">
        Next
        </button> 
        </form>
        
        
    </div>
            <!---Start of credit note table --->
            <div class="row">
                <div class="col-sm-12">
                    <div class="box box-warning">
                        <div class="box-header">
                            <h3 class="box-title">Supplier Credit Notes</h3>
        
                        </div>
                        <div class="box body no-padding" style="overflow:scroll">
        
                            <table class="table table-stripped table-hover" id="trans">
        
                                <thead>
                                <tr class="info">
                                        <th>CN NO</th>
                                        <th>NOM CAT</th>
                                        <th>CN VAT</th>
                                        <th>VAT GROSS</th>
                                        <th>VAT-W</th>
                                        <th>CN GROSS TOT</th>
                                        <th>NON VAT AMT</th>
                                        <th>AMT DEDUCTIBLE</th>
                                        <th>REMARKS</th>
                                        <th>CURR</th>
                                        <th>RATE</th>
                                        <th>WITHHOLDING RATE</th>
                                    </tr>
                                </thead>
        
                                <tbody>
                                    <?php
                                        require "connect.php";
                                        if (isset($_GET['submit']))
                                        {
                                            $supplier =$_GET['Supplier'];
                                            $fromdate=$_GET['fromdate'];
                                            $todate=$_GET['todate'];
        
                                            $sql = "exec supplier_creditnotes '$supplier','$fromdate','$todate'";
                                            $params = array();	
                                            $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
                                            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {?>
                                                <tr>
                                                <td><?php echo $row['InvNo']; ?></td>
                                                <td><?php echo 0; ?></td>
                                                <td><?php echo number_format($row['Inv_Vat'] ?? 0,2) ?></td>
                                                <td><?php echo number_format($row['Vat_Gross'] ?? 0,2) ?></td>
                                                <td><?php echo number_format($row['vat_w'] ?? 0,2) ?></td>
                                                <td><?php echo number_format($row['Inv_excl_tot'] ?? 0,2) ?></td>
                                                <td><?php echo number_format($row['nva'] ?? 0,2) ?></td> 
                                                <td><?php echo number_format($row['Vat_Gross'] ?? 0,2) ?></td> 
                                                <td><?php echo $row['remarks'] ?? '' ?></td> 
                                                <td><?php echo $row['Curr'] ?? '' ?></td>
                                                <td><?php echo $row['exchrate'] ?? '' ?></td> 
                                                <td><select name='Supplier' id='Supplier' class='form-control select2' style='width:50px;height:30px;'>
                                                <option value=1>2</option>
                                                </select>
                                                </td>                    
                                            </tr>
                                            <?php }  
        
                                        }
                                        ?>
                                </tbody>
        
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        <!-- end of credit notes table -->

    
</section>
</div>
<!-- /.content -->
<!-- /.content-wrapper -->
</body>
</html>