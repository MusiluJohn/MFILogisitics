<?php
session_start();
include("config.php");
$batch=$_POST['batch'] ?? '';
$landed=$_SESSION['vendor'] ?? '';
$id=$_SESSION['ship'];
$conn = sqlsrv_connect( $servername, $connectioninfo); 
$batchlanded="--Insert into batch lines landed cost reverse
IF OBJECT_ID('tempdb..#h') IS NOT NULL DROP TABLE #h
select distinct costcode ,sum(cost) as kes
into #h
from _cplshipmentlines cs  join _cplshipmentmaster cr
on cs.shipment_no=cr.shipment_no where cr.id=$id
group by costcode

declare @amt float
set @amt=(select sum(kes) from #h)

insert into _etblarapbatchlines (iBatchID, idLinePermanent, dtxdate, iaccountid,imodule, iAccountCurrencyID, iTrCodeID, iGLContraID, cReference,
cDescription, fAmountExcl,iTaxTypeID,fAmountIncl,fExchangeRate,fAmountExclForeign,fAmountInclForeign)
values($batch,0,format(getdate(),'yyyy-MM-dd'),$landed
,1,(select isnull(iCurrencyID,0) from vendor where dclink=$landed),21,
1642,'reverse','reverse landed cost', @amt ,7,@amt, 1, @amt, @amt)";
sqlsrv_query($conn, $batchlanded) or die(print_r( sqlsrv_errors(), true)); 

$cost=$_POST['cost'];
$supplier=$_POST['supplier'];
$rate=$_POST['rate'];
$ref=$_POST['ref'];
$desc=$_POST['description'];
for($i = 0; $i < count($cost); $i++){
$batchlines="--Insert into batch lines
insert into _etblarapbatchlines (iBatchID, idLinePermanent, dtxdate, iaccountid,imodule, iAccountCurrencyID, iTrCodeID, iGLContraID, cReference,
cDescription, fAmountExcl,iTaxTypeID,fAmountIncl,fExchangeRate,fAmountExclForeign,fAmountInclForeign)
values($batch,0,format(getdate(),'yyyy-MM-dd'),$supplier[$i],1,(select iCurrencyID from vendor where dclink=$supplier[$i]),23,
1642,'$ref[$i]','$desc[$i]', $cost[$i],7,$cost[$i], $rate[$i], $cost[$i]/nullif($rate[$i],0), $cost[$i]/nullif($rate[$i],0))";
sqlsrv_query($conn, $batchlines) or die(print_r( sqlsrv_errors(), true));    
}
?>