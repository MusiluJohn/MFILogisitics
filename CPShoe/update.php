<?php 

session_start();

$id=$_POST['id'] ?? 0;
$pin=$_POST['pin'] ?? '';
$invno=$_POST['invnum'] ?? '';
$invdate=$_POST['invdate'] ?? '';
$vat=$_POST['vat'] ?? 0;
$inv_vat=$_POST['inv_vat'] ?? 0;
$Inc_amt=$_POST['Inc_amt'] ?? 0;
$vat_w=$_POST['vat_w'] ?? 0;
$inv_gr_tot=$_POST['inv_gr_tot'] ?? 0;
$nva=$_POST['nva'] ?? 0;
$incwith=$_POST['incwith'] ?? 0;
$amt_pay=$_POST['amt_pay'] ?? 0;
$curr=$_POST['curr'] ?? '';
$payment_date=$_POST['payment_date'] ?? '';
//$status=$_POST['status'] ?? '';
$bno=$_SESSION['batch'];

include("connect.php");


for($i = 0; $i <count($invno); $i++){
        //get batch no
        
        $insertdb="update _cplsupplierwvat set [income_withheld_amt]=$incwith[$i], 
        [payment_date]='$payment_date'
        where
        invoiceid=$id[$i]";

        sqlsrv_query($conn, $insertdb);
        }
echo ('Successfully updated');
?>