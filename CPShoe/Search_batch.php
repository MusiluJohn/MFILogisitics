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
	    function selectall(){
        var chk=document.getElementById('checkall');
        var items=document.getElementsByName('select[]');
        for(var i=0; i<items.length; i++){
            if(items[i].type=='checkbox' && chk.checked==1){
				chk.checked=true;
                items[i].checked=true;
            } else {
				chk.checked=true;
                items[i].checked=true;    
            }
    }
        }
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
            <a href="" class="font-weight-bold">Batches</a>
            <form id="supplier-form" method="get" action="">
            <?php
            
            include("connect.php");

            $sql = "exec batch_list";	
            $stmt = sqlsrv_query($conn,$sql);
            if ($stmt) {			 
            echo "<select name='Batch' class='form-control' id='Batch'  style='width:200px;height:40px;margin-top:10px;'>";
            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {				
            echo "<option  value='" .$row["batch"]. "'> " .$row["batch"]. " " .$row["name"]. "</option>";
            }                     
            echo"</select>";
            }
            sqlsrv_close($conn);
            ?>
<script>
    $('#select').select();
</script>
<script type="text/javascript">
    document.getElementById('Batch').value = "<?php echo $_GET['Batch']; ?>";
</script>
<?php $_SESSION['batch']= $_GET['Batch'] ?? ''; ?>
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
        SEARCH BATCH TO PREVIEW
    </h1>
    <a> SELECT BATCH TO REPRINT</a>
    <?php  
        require "connect.php";
        $bno=$_GET['Batch'] ?? 1;
       
        $sql="select distinct (payment_date) from _cplsupplierwvat where 
        batch=$bno";
        $statement=sqlsrv_query($conn, $sql);
        while( $row = sqlsrv_fetch_array( $statement, SQLSRV_FETCH_ASSOC) ) {
                                
    ?>
    <br><br><a>Payment Date: </a>&nbsp;&nbsp;<input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:130px;' class='form-control' id="dates" name="dates" type="date" value='<?php echo $row['payment_date'] ?? '06/09/2023'; ?>' />
        <?php }?>
</section>
<!-- Main content -->
<section class="content">
    <!-- Default box -->
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-warning">
                <div class="box-header">
                    <h3 class="box-title">Supplier Invoices</h3>
                    <div class="box body no-padding" style="overflow:scroll">
                        <table class="table table-stripped table-hover table-condensed">
                            <tr class="info table-condensed">
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
                                <th>DELETE</th>
                            </tr>
                            <tbody>
                                <?php
                                require "connect.php";

                                
                                
                                if(isset($_SESSION['batch'])){
                                    $batches= $_GET['Batch'] ?? 0;
                                $invoices_to_pay="select invoiceid as auto_id, c.Name as Supplier, (pin_no) as Pin, '' as PI_no,
                                (invoiceno) as 'InvNo',(invoice_date) as 'InvDate', b.Tax_Amount as vat, 
                                case when e.taxrate=0 then 0 else (invoice_amt_excl * 0.16) end as Inv_Vat,
                                invoiceamt as Inc_amt,  
                                withholding_amt as vat_w,
                                case when e.taxrate=0 then invoice_amt_excl else (invoice_amt_excl * (e.taxrate/100)) end as Inv_gr_tot, 
                                0 as nva,
                                amount_payable
                                as amt_payable, 
                                payment_date as paymentdate, 
                                case when b.iCurrencyID=0 then 'KES' else d.CurrencyCode end as Curr,
                                AutoIdx as Supplier_id,
                                income_withheld_amt  as income_withheld,
                                a.status as 'Status',
                                e.TaxRate as taxrate, 
                                0 as exchrate,
                                batch as batch  
                                from _cplsupplierwvat a join postap b on a.invoiceid=b.AutoIdx
                                join vendor c on c.DCLink=b.AccountLink
                                left join Currency d on d.CurrencyLink=c.iCurrencyID
                                left join TaxRate e on e.idTaxRate=b.TaxTypeID
                                where batch=$batches";
                            }
                                $params = array();	
                                $stmt = sqlsrv_query($conn, $invoices_to_pay, $params) or die(print_r( sqlsrv_errors(), true));
                                $rowcnt = "select @@ROWCOUNT as cnt";
                                $statement = sqlsrv_query($conn, $rowcnt, $params) or die(print_r( sqlsrv_errors(), true));
                                while( $row = sqlsrv_fetch_array( $statement, SQLSRV_FETCH_ASSOC) ) {
                                if ($row['cnt']>0) {
                                    $_SESSION['cnt']=$row['cnt'];
                                $rows=0;
                                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                                
                                ?>
                                <tr class="table-condensed text-right"><form action='remmittance_advice old.php' method='GET'>
                                        <td><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;' id="sup" name="sup[]" value='<?php echo $row['Supplier'] ?? '' ?>' disabled/></td>
                                        <td hidden ><input hidden id="supid" name="supid[]" value=<?php echo $row['auto_id'] ?? 0 ?> /></td>
                                        <td><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;' id="pin[]" name="pin[]" value='<?php echo $row['Pin'] ?? '' ?>' disabled/></td>
                                        <td><input  id="invnum" name="invnum[]" value='<?php echo $row['InvNo'] ?>' disabled /></td>
                                        <td><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:90px;' id="invdate" name="invdate[]"  value='<?php echo $row['InvDate'] ?? '' ?>' disabled/></td>
                                        <td><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:40px;' id="vat[]" name="vat[]" value=<?php echo $row['taxrate'] ?? '' ?> disabled/></td>
                                        <td><?php echo number_format($row['Inv_Vat'] ?? 0,2) ?><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:70px;' id="inv_vat[]" name="inv_vat[]"   disabled value=<?php echo $row['Inv_Vat'] ?? 0 ?> hidden></td>
                                        <td><?php echo number_format($row['Inc_amt'] ?? 0,2) ?><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:80px;' id="Inc_amt[]" name="Inc_amt<?php echo $rows; ?>"  value=<?php echo $row['Inc_amt'] ?? 0 ?> hidden disabled/></td>
                                        <td><?php echo number_format($row['vat_w'] ?? 0.00,2) ?><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:80px;' id="vat_w[]" name="vat_w<?php echo $rows; ?>"  value=<?php echo $row['vat_w'] ?? 0.00 ?> hidden disabled/></td>
                                        <td><?php echo number_format($row['Inv_gr_tot'] ?? 0,2) ?><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:80px;' id="inv_gr_tot[]" name="inv_gr_tot[]"  value=<?php echo $row['Inv_gr_tot'] ?? 0 ?> hidden disabled/></td>
                                        <td><?php echo number_format($row['nva'] ?? 0,2) ?><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:80px;' id="nva[]" name="nva[]"  value=<?php echo $row['nva'] ?? 0 ?> hidden disabled/></td> 
                                        <td><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:80px;' class='form-control text-right'  id="incwith[]" name="incwith<?php echo $rows; ?>" value=<?php echo $row['income_withheld']; ?> /></td>
                                        <script>
                                                $(document).ready(function(){
                                                $('input[name="incwith<?php echo $rows; ?>"]').keyup(function(){
                                                    $.ajax({
                                                        url: 'Amt_pay.php',
                                                        type: 'POST',
                                                        data: {Incl: $('input[name="Inc_amt<?php echo $rows; ?>"]').val(),Vat_w: $('input[name="vat_w<?php echo $rows; ?>"]').val(),
                                                        incwith: $('input[name="incwith<?php echo $rows; ?>"]').val(),id: $('input[name="sup_id[]"]').val() },
                                                        success: function(result){
                                                            $('input[name="amt_pay<?php echo $rows; ?>"]').val(result);
                                                            $('td[name="amt_pay_one<?php echo $rows; ?>"]').html(result);
                                                        }
                                                        })
                                                });
                                                }); 
                                        </script>
                                        <td id="amt_pay_one[]" name="amt_pay_one<?php echo $rows; ?>"><?php echo number_format($row['amt_payable'],2)?><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:80px;' id="amt_pay[]" name="amt_pay[]" value=<?php echo $row['amt_payable'] ?? 0.00 ?> hidden disabled/></td> 
                                        <td><input style='width:200px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;width:130px;' class='form-control' id="date[]" name="date[]" type="date" value='<?php echo $row['paymentdate']?>' /></td>
                                        <td><input style='width:50px;border-top-style: hidden;border-right-style: hidden;border-left-style: hidden;border-bottom-style: hidden;height:19px;' class='form-control' id="curr[]" name="curr[]" value=<?php echo $row['Curr'] ?? '' ?> disabled /></td>
                                        <td><?php echo $row['exchrate']; ?></td>  
                                        <td><select name='Supplier[]' id='Supplier[]' class='form-control select2' style='width:70px;height:29px;'>
                                        <option value=2>2</option>
                                        </select>
                                        </td> 
                                        <td>
                                            <a href='delete.php?delete=<?php echo $row['auto_id']; ?>'>Delete</a>
                                        </td>
                                    </tr>
                                    <?php $rows++;}}}?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> 
        <button class="form-control btn btn-success" id="submit" name="submit"  style="margin-bottom:10px;width:200px;margin-left:15px;align:center">
            print REMMITTANCE ADVICE
        </button> 
        </form>
        <button class="form-control btn btn-success" id="save" name="save"  style="margin-bottom:10px;width:70px;margin-left:15px;align:center">
        UPDATE
        </button>
        <script>
            $(document).ready(function(){
            $("#save").click(function(){
                var id=[];
                $('input[name^="supid"]').each(function() {
                    id.push(this.value);
                });
                var pin=[];
                $('input[name^="pin"]').each(function() {
                    pin.push(this.value);
                });
                var invno=[];
                $('input[name^="invnum"]').each(function() {
                    invno.push(this.value);
                });
                var invdate=[];
                $('input[name^="invdate"]').each(function() {
                    invdate.push(this.value);
                });
                var vat=[];
                $('input[name^="vat"]').each(function() {
                    vat.push(this.value);
                });
                var inv_vat=[];
                $('input[name^="inv_vat"]').each(function() {
                    inv_vat.push(this.value);
                });
                var Inc_amt=[];
                $('input[name^="Inc_amt"]').each(function() {
                    Inc_amt.push(this.value);
                });
                var vat_w=[];
                $('input[name^="vat_w"]').each(function() {
                    vat_w.push(this.value);
                });
                var inv_gr_tot=[];
                $('input[name^="inv_gr_tot"]').each(function() {
                    inv_gr_tot.push(this.value);
                });
                var nva=[];
                $('input[name^="nva"]').each(function() {
                    nva.push(this.value);
                });
                var incwith=[];
                $('input[name^="incwith"]').each(function() {
                    incwith.push(this.value);
                });
                var amt_pay=[];
                $('input[name^="amt_pay"]').each(function() {
                    amt_pay.push(this.value);
                });
                var curr=[];
                $('input[name^="curr"]').each(function() {
                    curr.push(this.value);
                });
                var status=[];
                $('select[name^="Status"]').each(function() {
                    status.push(this.value);
                });
                $.ajax({
                    url: 'update.php',
                    type: 'post',
                    data: {id:id,pin:pin,invnum:invno,invdate:invdate,vat:vat,inv_vat:inv_vat,
                    Inc_amt:Inc_amt,vat_w:vat_w,inv_gr_tot:inv_gr_tot,nva:nva,incwith:incwith,
                    amt_pay:amt_pay,curr:curr,payment_date:$('#dates').val(),status:status},
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

                                                                    $batches= $_GET['Batch'] ?? 0;
                                            $sql = "select autoidx, b.account, b.Name as Supplier, b.Registration as Pin, '' as PI_no,
                                            reference as 'InvNo', format(txdate,'dd/MM/yyyy') as 'InvDate', tr.TaxRate as vat, a.tax_amount as Inv_Vat,
                                            debit as Vat_Gross, 0 as vat_w,debit-tax_amount as Inv_excl_tot, 0 as nva,
                                            0 as amt_payable, cast(format(getdate(),'yyyy-MM-dd') as varchar(50)) as paymentdate, 
                                            case when a.iCurrencyID=0 then 'KES' else
                                            c.Description end as Curr, a.Description as remarks,a.fExchangeRate as exchrate
                                            from postap a join vendor b
                                            on a.AccountLink=b.DCLink
                                            
                                            left join TaxRate tr on tr.idTaxRate=a.TaxTypeID
                                            
                                            left join currency c on c.currencylink=b.iCurrencyID
                                            left join _cplsuppliercrn d on d.autoid=a.autoidx
                                            where batch=$batches";
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