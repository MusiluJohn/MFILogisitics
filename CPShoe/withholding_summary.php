<?php
session_start();
?>

<!DOCTYPE html>
<html>

<head>
    <!-- <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no"> -->
    <style>@media print{@page {size: landscape}}</style>
    <title>WITHHOLDING SUMMARY</title>
    <link rel="stylesheet" type="text/css" href="assets/css/remmittance.css"/>
 </head>
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
 <div>
 <table id="headings" name="headings">
    <tr>
        <th>C & P SHOE INDUSTRIES LTD</th>
    </tr>
    <tr>
        <th>PIN: P000614054Q</th><th>VAT NO. : 0010235W</th>
    </tr>
    <tr>
        <th>VAT WITHHOLDING SUMMARY FROM <?php echo $_GET['repfrmdate']; ?> TO <?php echo $_GET['reptodate']; ?></th>
    </tr>
</table>
<table id="summary" name="summary">
    <thead>
        <th style="text-align:right;">S/NO</th>
        <th style="text-align:right;">DATE</th>
        <th style="text-align:right;">INV NO</th>
        <th style="text-align:left;">SUPPLIER</th>
        <th style="text-align:right;">INV TOTAL</th>
        <th style="text-align:right;">VAT WITH</th>
        <th style="text-align:right;">INC WITH</th>
        <th style="text-align:right;">PAID</th>
    </thead>
<tbody>
    <?php
                    include("connect.php");
                    
                    $fromdate=$_GET['repfrmdate'];
                    $todate=$_GET['reptodate']; 
                    $sql = "select row_number() over (order by invoiceid) row_num, invoice_date, invoiceno,c.Name, 
                    case when c.iCurrencyID>0 then (invoiceAmt * b.fExchangeRate)
                    else invoiceAmt end invoiceAmt,
                    case when c.iCurrencyID>0 then withholding_amt*fExchangeRate else withholding_amt end as withholding_amt 
                    ,case when c.iCurrencyID>0 then income_withheld_amt *fExchangeRate else income_withheld_amt end as inc_with_amt,                   
                    case when c.iCurrencyID>0 then amount_payable * fExchangeRate else amount_payable end as amount_payable 
                    from _cplsupplierwvat a left join postap b on a.invoiceid=b.AutoIdx left join vendor c on c.dclink=b.accountlink
                    where 
                    format(cast(payment_date as date),'yyyy-MM-dd') 
                    between '$fromdate'  and '$todate'
                    order by c.name asc";
                    $params = array();	
                    $stmt = sqlsrv_query($conn, $sql) or die(print_r( sqlsrv_errors(), true));
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    ?>
    <tr>
        <td style="text-align:right;">
        <?php echo $row['row_num'] ?>
        </td>
        <td style="text-align:right;">
            <?php echo $row['invoice_date'] ?>
        </td>
        <td style="text-align:right;">
            <?php echo $row['invoiceno'] ?>
        </td>
        <td style="text-align:left;">
            <?php echo $row['Name'] ?>
        </td>
        <td style="text-align:right;">
        <?php echo number_format($row['invoiceAmt'],2) ?>
        </td>
        <td style="text-align:right;">
        <?php echo number_format($row['withholding_amt'],2) ?>
        </td>
        <td style="text-align:right;">
        <?php echo number_format($row['inc_with_amt'],2) ?>
        </td>
        <td style="text-align:right;">
        <?php echo number_format($row['amount_payable'],2) ?>
        </td>
    </tr>
    <?php }?>
</tbody>
<tfoot>
<?php
                    include("connect.php");
                    
                    $fromdate=$_GET['repfrmdate'];
                    $todate=$_GET['reptodate']; 
                    $sql = "select '' as invoice_date , 
                    '' as row_num,
                    '' as invoiceno,'' as Name, 
                    case when max(b.iCurrencyID)>0 then sum(invoiceAmt * fexchangerate) else sum(invoiceAmt) end as invoiceAmt,
                    case when max(b.iCurrencyID)>0 then sum(withholding_amt *fExchangeRate) else sum(withholding_amt) end as withholding_amt, 
                    case when max(b.iCurrencyID)>0 then sum(a.income_withheld_amt * fExchangeRate) else sum(a.income_withheld_amt) end as inc_with,
                    case when max(b.iCurrencyID)>0 then sum(amount_payable * fexchangerate) else sum(amount_payable) end as amount_payable                     
                    from _cplsupplierwvat a left join postap b on a.invoiceid=b.AutoIdx left join 
                    vendor c on c.dclink=b.accountlink
                    where 
                    format(cast(payment_date as date),'yyyy-MM-dd') 
                    between '$fromdate'  and '$todate'";
                    $params = array();	
                    $stmt = sqlsrv_query($conn, $sql, $params) or die(print_r( sqlsrv_errors(), true));
                    while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    ?>
<tr>
        <td style="text-align:right;">
        TOTAL
        </td>
        <td style="text-align:right;">
            
        </td>
        <td style="text-align:right;">
            
        </td>
        <td style="text-align:right;">
            
        </td>
        <td style="text-align:right;">
        <?php echo number_format($row['invoiceAmt'],2) ?>
        </td>
        <td style="text-align:right;">
        <?php echo number_format($row['withholding_amt'],2) ?>
        </td>
        <td style="text-align:right;">
        <?php echo number_format($row['inc_with'],2) ?>
        </td>
        <td style="text-align:right;">
        <?php echo number_format($row['amount_payable'],2) ?>
        </td>
    </tr>
    <?php }?>
</tfoot>
</table>

</div>
 </body>
 </html>
