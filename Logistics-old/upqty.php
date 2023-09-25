<?php
//require_once("insert.php");
include("config.php");
session_start();
$conn = sqlsrv_connect( $servername, $connectioninfo);
$query=$_POST['shipno'] ?? 0;
$id=$_POST['id'] ?? 0;
$qty=$_POST['qty'] ?? 0;

$query2="update _cplshipmentlines set rec_qty=rec_qty+$qty  from  _cplshipmentlines a join _cplshipmentmaster b
on a.shipment_no=b.shipment_no where rec_qty+$qty<=qty and b.id=cast($query as int) and a.invoicelineid=$id";

sqlsrv_query($conn,$query2) or die(print_r( sqlsrv_errors(), true));

?>