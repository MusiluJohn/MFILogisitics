<?php
session_start();
include("connect.php");
$batch=$_SESSION['batch'];
?>

<!DOCTYPE html>
<html>

<head>
    <!-- <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=yes"> -->
    <title>REMMITTANCE ADVICE</title>
    <link rel="stylesheet" type="text/css" href="assets/css/remmittance.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap1.css"/>
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap2.css"/>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet"> 
 </head>
 <script src="assets/js/script.js"></script>
 <body>
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <button class="btn btn-success" id="prints" name="prints" onclick='print_page()'>
    Print
    </button>
    <button class="btn btn-success" id="homes" name="homes" onclick='home()'>
    Home
    </button>
    <script type="text/javascript">
        function print_page() {
            var ButtonControl = document.getElementById("prints");
            var ButtonControlTwo = document.getElementById("homes");
            ButtonControl.style.visibility = "hidden";
            ButtonControlTwo.style.visibility="hidden";
            window.print();
            ButtonControl.style.visibility = "visible";
            ButtonControlTwo.style.visibility="visible";
        }
        function home() {
            window.location.href = "main.php";
        }
    </script>
 <table  id="top-table" name="top-table">
 <?php
    include("connect.php");
    $sql = "exec DATE $batch";
    $params = array();	
    $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
?>    
        <label id="sup_copy" name="sup_copy">SUPPLIER COPY</label>
        <tr>
            <th>
                <label id='co_name' name='co_name'>C & P SHOE INDUSTRIES LTD</label>
            </th>
            <th>
                <label id="remit" name="remit">REMITTANCE ADVICE</label>
            </th>
            <th id="supcopy" name="supcopy">     
            </th>
            <th>
                <label id='d' name='d'>DATE: </label><?php  echo $row['payment_date']; ?>
            </th>
        </tr>
        <?php } ?>
        <tr>
            <th>
                <?php 
                include("connect.php");
                $q1="select distinct name, tax_number from _cplsupplierwvat a join postap b 
                on a.invoiceid=b.AutoIdx
                join vendor c on c.DCLink=b.AccountLink
                where a.batch=$batch";

                $chk=sqlsrv_query($conn, $q1);
                while( $row = sqlsrv_fetch_array( $chk, SQLSRV_FETCH_ASSOC) ) { 
                ?>
                <label id='sup_name' name='sup_name'>SUPPLIER NAME:</label> <?php echo $row['name']; ?>
            </th>
            <td>      
            </td>
            <td>
            </td>
            <th>
                <label id='batch' name='batch'>BATCH: <?php echo $batch; ?></label>
            </th>
        </tr>
        <tr>
            <th>
                <label id='inv_paid' name='inv_paid'>INVOICE/S BEING PAID</label>
            </th>
            <th>
            <label>PIN:</label>  <?php echo $row['tax_number']; }?>  
            </th>
            <td>
            </td>
            <td>
            </td>
        </tr>
</table>
<table   id="middle-table" name="middle-table">
        <tr id='middle-table-head'>
            <th class='m-left'>
                INVOICE NO.
            </th>
            <th class='m-right'>
                INV DATE     
            </th>
            <th class='m-right'>
                GR INV AMT
            </th>
            <th class='m-right'>
                VAT W/HELD
            </th>
            <th class='m-right'>
                INC W/HELD
            </th>
            <th class='m-right'>
                AMOUNT PAID
            </th>
            <th class='m-right'>
                CURR
            </th>
        </tr>
        <?php
                $sql = "exec Paid_Invoices $batch";
                $params = array();	
                $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                ?>   
    <tbody>
    <tr class='row_one'>
            <td class='m-left'>
                <?php echo $row['invoiceno']; ?>
            </td>
            <td class='m-right'>
            <?php echo $row['invoice_date']; ?>     
            </td>
            <td class='m-right'>
            <?php echo number_format($row['invoice_amt_excl'] ?? 0,2); ?>
            </td>
            <td class='m-right'>
            <?php echo number_format($row['withholding_amt'] ?? 0,2); ?>
            </td>
            <td class='m-right'>
            <?php echo number_format($row['income_withheld_amt'] ?? 0,2) ?>
            </td>
            <td class='m-right'>
            <?php echo number_format($row['amount_payable'],2); ?>
            </td>
            <td class='m-right'>
            <?php echo $row['curr']; ?>        
            </td>
</tr>

<?php } ?>
</tbody>
</table>
<!---Totals--->   
<?php

    //get long inv no
    $long="select max(invoiceno) as long from _cplsupplierwvat
    where batch=$batch";
    $stmtone = sqlsrv_query($conn, $long) or die(print_r( sqlsrv_errors(), true));
    while( $rowone = sqlsrv_fetch_array( $stmtone, SQLSRV_FETCH_ASSOC) ) {
   
?>      
<table class="totals">
<thead>
        <tr id="row_totals" name="row_totals">
            <th style="color:transparent;" class='text-left'>
            <?php echo $rowone['long']; }?>
            </th>
            <th style="color:transparent;" class='text-right'>
                INV DATE     
            </th>
            <th style="color:transparent;" class='text-right'>
                GR INV AMT.
            </th>
            <th style="color:transparent;" class='text-right'>
                VAT W/HELD
            </th>
            <th style="color:transparent;" class='text-right'>
                INC W/HELD
            </th>
            <th style="color:transparent;" class='text-right'>
                AMOUNT PAID
            </th>
            <th style="color:transparent;" class='text-left'>
                CURR
            </th>
        </tr>
</thead>
<?php
  $sql = "exec Invoice_Totals $batch";
  $params = array();	
  $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
  while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {?>
<tfoot ><tr>
            <td class="text-left">
            TOTALS
            </td>
            <td class="text-right">
           
            </td>
            <td class="text-right">
                <?php echo number_format($row['gross'],2) ?>
            </td>
            <td class="text-right">
                <?php echo number_format($row['vat_w'],2) ?>
            </td>
            <td class="text-right">
                <?php echo number_format($row['inc_withheld'],2) ?>
            </td>
            <td class="text-right">
                <?php echo number_format($row['amt_paid'],2) ?>
            </td>
            <td class="text-left">
            </td>
        </tr>
        <?php } ?>

    </tfoot>
</table>
<!---Check if credit notes are there--->
<?php

    $sql = "exec crn_report_count $batch";
    $params = array();	
    $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
        if (($row['cnt']!=0)) {?>
<!---Credit notes start --->
<table  id="cn-table-one" name="cn-table-one">
<thead>
    <tr>
        <th class="text-left"><label id="cn_head" name="cn_head">CREDIT NOTES</label></th>
    </tr>
        <tr id="cn-table-one-row" name="cn-table-one-row">
            <th class="text-left">
                CREDIT NOTE NO.
            </th>
            <th class="text-right">
                CN DATE     
            </th>
            <th class="text-right">
                GROSS CN AMT.
            </th>
            <th class="text-left">
                MEMO
            </th>
            <th>
                CURR
            </th>
        </tr>
</thead>
        <?php
                $sql = "exec crn_report $batch";
                $params = array();	
                $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                ?>   
    <tbody>
    <tr>
            <td class="text-left">
                <?php echo $row['InvNo']; ?>
            </td>
            <td class="text-right">
            <?php echo $row['InvDate']; ?>     
            </td>
            <td class="text-right">
            <?php echo number_format($row['Vat_Gross'],2); ?>
            </td>
            <td class="text-left">
            <?php echo $row['remarks']; ?>
            </td>
            <td>
            <?php echo $row['Curr'] ?>
            </td>
</tr>
<?php } ?>
</tbody>   
</table>
<!----end of credit note table --->
<?php }
        else
        {

        }
    }
    ?> 
<br/><br/>
<table class='summary'>
<?php
    $sql = "exec DATE $batch";
    $params = array();	
    $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
?>
        <tr>
            <th>
                <label style="border-bottom:1px;">PREPARED BY: <?php echo $_SESSION['user']; echo '         '; echo $row['payment_date']; ?></label>
            </th>
            <th>
                CHECKED BY: ________________________
            </th>
            <th>
                AUTHORISED BY: _____________________
            </th>
        </tr>
<?php } ?>
</table>
<label id="seperate" name="seperate">------------------------------------------------------------------------------------------------------------------------------------------------------------------</label>
<table id="bottom-table" name="bottom-table">
 <?php
    include("connect.php");
    $sql = "exec DATE $batch";
    $params = array();	
    $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
?> 
<label id='sup_copy_two' name='sup_copy_two'>C&P SHOE COPY</label>
        <tr>
            <th>
                <label id='co_name' name='co_name'>C & P SHOE INDUSTRIES LTD</label>
            </th>
            <th>
                <label id="remit" name="remit">REMITTANCE ADVICE</label>
            </th>
            <th id="supcopy" name="supcopy">
                
            </th>
            <th>
                <label id="d" name="d">DATE: </label><?php echo $row['payment_date']; ?>
            </th>
        </tr>
<?php } ?>
        <tr>
            <th>
                <?php 
                include("connect.php");
                $q1="select distinct name, tax_number from _cplsupplierwvat a join postap b 
                on a.invoiceid=b.AutoIdx
                join vendor c on c.DCLink=b.AccountLink
                where a.batch=$batch";

                $chk=sqlsrv_query($conn, $q1);
                while( $row = sqlsrv_fetch_array( $chk, SQLSRV_FETCH_ASSOC) ) { 
                ?>
                <label id='sup_name' name='sup_name'>SUPPLIER NAME:</label> <?php echo $row['name']; ?>
            </th>
            <td>      
            </td>
            <td>
            </td>
            <th>
                <label>BATCH: <?php echo $batch; ?></label>
            </th>
        </tr>
        <tr>
            <th>
                <label id='inv_paid' name='inv_paid'>INVOICE/S BEING PAID</label>
            </th>
            <th>
            <label>PIN:</label>  <?php echo $row['tax_number']; }?>  
            </th>
            <td>
            </td>
            <td>
            </td>
        </tr>
</table>

<table id="middle-bottom-table" name="middle-bottom-table" style="border-bottom:solid 0.5px;">
<thead>
        <tr class="middle-bottom-head">
            <th>
                INVOICE NO.
            </th>
            <th class="text-right" >
                INV DATE     
            </th>
            <th class="text-right">
                GR INV AMT.
            </th>
            <th class="text-right">
                VAT W/HELD
            </th>
            <th class="text-right">
                INC W/HELD
            </th>
            <th class="text-right">
                AMOUNT PAID
            </th>
            <th class="text-right">
                CURR
            </th>
        </tr>
</thead>
        <?php
                $sql = "exec Paid_Invoices $batch";
                $params = array();	
                $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
                while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                ?>   
    <tbody>
    <tr id='row_one'>
            <td class="text-left">
                <?php echo $row['invoiceno']; ?>
            </td>
            <td class="text-right">
            <?php echo $row['invoice_date']; ?>     
            </td>
            <td class="text-right">
            <?php echo number_format($row['invoice_amt_excl'],2); ?>
            </td>
            <td class="text-right">
            <?php echo number_format($row['withholding_amt'],2); ?>
            </td>
            <td class="text-right">
            <?php echo number_format($row['income_withheld_amt'],2) ?>
            </td>
            <td class="text-right">
            <?php echo number_format($row['amount_payable'],2); ?>
            </td>
            <td class="text-right">
            <?php echo $row['curr']; ?>
            </td>
</tr>
<?php } ?>
</tbody>   
   
</table>
<?php
    //get long inv no
    $long="select max(invoiceno) as long from _cplsupplierwvat
    where batch=$batch";
    $stmtone = sqlsrv_query($conn, $long) or die(print_r( sqlsrv_errors(), true));
    while( $rowone = sqlsrv_fetch_array( $stmtone, SQLSRV_FETCH_ASSOC) ) {
   
?> 
  
<table class="totals2">
<thead >
        <tr>
            <th style="color:transparent; " class="text-left">
             <?php echo $rowone['long']; }?>
            </th>
            <th style="color:transparent;" class="text-right">
                INV DATE     
            </th>
            <th style="color:transparent;" class="text-right">
                GR INV AMT.
            </th>
            <th style="color:transparent;" class="text-right">
                VAT W/HELD
            </th>
            <th style="color:transparent;" class="text-right">
                INC W/HELD
            </th>
            <th style="color:transparent;" class="text-right">
                AMOUNT PAID
            </th>
            <th style="color:transparent;" class="text-left">
                CURR
            </th>
        </tr>
</thead>
<?php
    $sql = "exec Invoice_Totals $batch";
    $params = array();	
    $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
?> 
<tfoot>
<tr>
            <td class="text-left">
                TOTALS
            </td>
            <td style="text-align:right;">
            </td>
            <td style="text-align:right;">
                <?php echo number_format($row['gross'],2) ?>
            </td>
            <td class="text-right">
                <?php echo number_format($row['vat_w'],2) ?>
            </td>
            <td class="text-right">
                <?php echo number_format($row['inc_withheld'],2) ?>
            </td>
            <td class="text-right">
                <?php echo number_format($row['amt_paid'],2) ?>
            </td>
            <td>
            </td>
        </tr>
        <?php } ?>
    </tfoot>
</table>
<!---Check if credit notes are there--->
<?php

    $sql = "exec crn_report_count $batch";
    $params = array();	
    $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
        if (($row['cnt']!=0)) {?>
            <!---Start of credit note table--->
            <table id="cn-table" name="cn-table">
            <thead>
                <tr>
                    <th class="text-left"><label id="cn_head" name="cn_head">CREDIT NOTES</label></th>
                </tr>
                    <tr id="cn-table-row">
                        <th class="text-left">
                            CREDIT NOTE NO.
                        </th>
                        <th class="text-right">
                            CN DATE     
                        </th>
                        <th class="text-right">
                            GROSS CN AMT.
                        </th>
                        <th class="text-left">
                            MEMO
                        </th>
                        <th class="text-right">
                            CURR
                        </th>
                    </tr>
            </thead>
                    <?php
                            $sql = "exec crn_report $batch";
                            $params = array();	
                            $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
                            while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
                            ?>   
                <tbody>
                <tr>
                        <td class="text-left">
                            <?php echo $row['InvNo']; ?>
                        </td>
                        <td class="text-right">
                        <?php echo $row['InvDate']; ?>     
                        </td>
                        <td class="text-right">
                        <?php echo number_format($row['Vat_Gross'],2); ?>
                        </td>
                        <td class="text-left">
                        <?php echo $row['remarks']; ?>
                        </td>
                        <td class="text-right">
                        <?php echo $row['Curr'] ?>
                        </td>
            </tr>
            <?php } ?>
            </tbody>   
            </table>
            <!---Credit note end---->
        <?php }
        else
        {

        }
    }
    ?>   

<br/><br/>
<div id='summary2' name='summary2'>
<table class="summary2">
<?php
    $sql = "exec DATE $batch";
    $params = array();	
    $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
?>
        <tr>
            <th>
            <label style="border-bottom:1px;">PREPARED BY: <?php echo $_SESSION['user']; echo '         '; echo $row['payment_date']; ?></label>
            </th>
            <th>
                CHECKED BY: ________________________
            </th>
            <th>
                AUTHORISED BY: _____________________
            </th>
        </tr>
        <?php } ?>
    </table>
    </div>
<br/><br/><br/><br/>
 </body>
 </html>
