<?php
// Header is needed to export to CSV. If header is not include, the data will display in the Web browser
header("Content-Type: text/csv;charset=utf-8");

$frmdate=$_GET['repfrmdate'];
$todate=$_GET['reptodate'];
$filename='withholdingvat_' ;
$seperator='_';
$csv='.csv';
$finalfilename=$filename.$frmdate.$seperator.$todate.$csv;

header('Content-Disposition: filename="'.$finalfilename.'"');

include("connect.php");

$select = "select pin_no,invoiceno,invoice_date,
(case when fExchangeRate>0 then round((invoice_amt_excl*fExchangeRate),2) else invoice_amt_excl end) as invoice_amt_excl
,format(getdate(),'dd/MM/yyyy') as payment_date from _cplsupplierwvat 
a join postap b on a.invoiceid=b.autoidx
where payment_date between '$frmdate' and '$todate' 
and withholding_amt<>0 and status='Yes'";
$select_query = sqlsrv_query($conn, $select);
$fp= fopen('php://output', 'w');

//While loop, create array named $row, Use fputcsv function
while ($row = sqlsrv_fetch_array($select_query, SQLSRV_FETCH_ASSOC)) {
	fputcsv($fp, array_values($row));
}

//die is needed to prevent the data from displaying twice in the CSV file
die;

fclose($fp);
?>