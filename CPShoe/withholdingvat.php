<?php
// Header is needed to export to CSV. If header is not include, the data will display in the Web browser
header("Content-Type: text/csv;charset=utf-8");

include("connect.php");

$frmdate=$_GET['repfrmdate'];
$todate=$_GET['reptodate'];

$select = "select pin_no,invoiceno,invoice_date,invoice_amt_excl,format(getdate(),'dd/MM/yyyy') as payment_date from _cplsupplierwvat 
where payment_date between '$frmdate' and '$todate' and withholding_amt<>0 and status='Yes'";
$select_query = sqlsrv_query($conn, $select);
$fp= fopen('php://output', 'w');

// While loop, create array named $row, Use fputcsv function
while ($row = sqlsrv_fetch_array($select_query, SQLSRV_FETCH_ASSOC)) {
	fputcsv($fp, array_values($row));
}

// die is needed to prevent the data from displaying twice in the CSV file
die;

fclose($fp);
?>