<?php 
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

include("connect.php");

$updatecounter="update _cplcounter set id=0";
sqlsrv_query($conn, $updatecounter);

for($i = 0; $i <count($invno); $i++){
$check_if_posted="select count(invoiceid) as invid from _cplsupplierwvat where invoiceid=$id[$i]";
$chk=sqlsrv_query($conn, $check_if_posted);
while( $row = sqlsrv_fetch_array( $chk, SQLSRV_FETCH_ASSOC) ) { 
    $invidone=$row['invid'];
$ins="update _cplcounter set id=id+$invidone";
sqlsrv_query($conn, $ins);
}}

$counter="select id from _cplcounter";
$counterchk=sqlsrv_query($conn, $counter);
while( $row = sqlsrv_fetch_array($counterchk, SQLSRV_FETCH_ASSOC) ) { 
$invid=$row['id'];

if ($invid==0){

for($i = 0; $i <count($invno); $i++){
        //get batch no
        $batchno="select batchno from _cplbatchno";
        $chk=sqlsrv_query($conn, $batchno);
        while( $row = sqlsrv_fetch_array( $chk, SQLSRV_FETCH_ASSOC) ) { 
            $bno=$row['batchno']+1;
        
        $insertdb="insert into _cplsupplierwvat ([invoiceid], [invoiceno], [invoiceAmt], [withholding_rate], [withholding_amt], 
        [income_withheld_rate], [income_withheld_amt], [amount_payable], [payment_date], [type], pin_no,invoice_date,invoice_amt_excl
        ,status,batch)
        values (($id[$i]), '$invno[$i]',$Inc_amt[$i], 2.00, $vat_w[$i] ,0.00,$incwith[$i],
        $Inc_amt[$i]-($vat_w[$i]+$incwith[$i]),'$payment_date','PO','$pin[$i]','$invdate[$i]' ,$inv_gr_tot[$i],'Yes',
        $bno)";
            
        /*$insertcrn="insert into _cplsuppliercrn (autoid,batch) 
        values (case when $crn_id[$i]>0 then $crn_id[$i] else 0 end,$bno)";*/

        sqlsrv_query($conn, $insertdb);
        /*sqlsrv_query($conn, $insertcrn);*/
        }
        }
        echo  "$bno has been created";
//update batch no
$updatebatchno="update _cplbatchno set batchno=batchno+1";
sqlsrv_query($conn, $updatebatchno);
} else
{
    echo "Batch already created";
}
 
}
?>