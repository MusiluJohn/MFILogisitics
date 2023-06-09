<?php
//require_once("insert.php");
include("config.php");
//session_start();
$conn = sqlsrv_connect( $servername, $connectioninfo);
$query=$_POST['shipment'];
$from=$_POST['transit'] ?? 0;
$to=$_POST['final'] ?? 0;
$supplier=$_POST['vendor'] ?? '';
$_SESSION['vendor']=$supplier;
$conn = sqlsrv_connect( $servername, $connectioninfo); 

/*Check if shipment was posted sage*/
$posted="select posted from _cplshipmentmaster where id=$query";
$chkone=sqlsrv_query($conn, $posted) or die(print_r( sqlsrv_errors(), true));
while( $row = sqlsrv_fetch_array( $chkone, SQLSRV_FETCH_ASSOC) ) { 

// If IBT was not created in sage then

if (($row["posted"]==0)){

$insertmasteribt="
declare @ibtno as varchar(50)
set @ibtno=(select cast('IBT' as varchar(20))+cast(format(iNextNo,'000000') as varchar(20)) from _rtblrefbase where cRefType='NextWHIBTNo' and _rtblRefBase_iBranchID=0)

IF OBJECT_ID('tempdb..#tmpcost') IS NOT NULL DROP TABLE #tmpcost
select distinct cs.invoicelineid as id,cs.code as code,max(stkcode) as stid, max(cs.description) as description, (totals) as totals, 
cast(max(unit_amount_kes) as int)  unitcst,
(sum(isnull(cs.cost,0)))-max(case when cr.cost='VATonDuty' then (isnull(cs.cost,0)) else 0 end)-max(case when cr.cost='TT_SWIFT_Charges' then (isnull(cs.cost,0)) else 0 end) as addcost,max(qty) as qty,max(iuomstockingunitid) as unitid
into #tmpcost
from stkitem st
join _cplshipmentlines cs
on st.stocklink=cs.stkcode join _cplshipmentmaster tm
on cs.shipment_no=tm.shipment_no
join _cplcostmaster cr on cs.costcode=cr.id
where tm.id=cast($query as int)
group by cs.invoicelineid,cs.code,Totals

insert into [dbo].[_etblWhseIBT] ([cIBTNumber], [cIBTDescription], [iWhseIDFrom], [iWhseIDTo], [iWhseIDIntransit], [iWhseIDVariance], [iWhseIDDamaged], [iIBTStatus], [cDelNoteNumber], [iProjectID], [dDateIssued], [dDateReceived], [cAuditNumberIssued], [cAuditNumberReceived], [bUseAddCostPerLine], [fFixedAddCost], [iAgentIDIssue], [iAgentIDReceive], [iBranchIDFrom], [dDateRequired], [dDateRequested], [dDateApproved], [iLinkedReqID], [_etblWhseIBT_iBranchID], [_etblWhseIBT_dCreatedDate], [_etblWhseIBT_dModifiedDate], [_etblWhseIBT_iCreatedBranchID], [_etblWhseIBT_iModifiedBranchID], [_etblWhseIBT_iCreatedAgentID], [_etblWhseIBT_iModifiedAgentID], [_etblWhseIBT_iChangeSetID], [_etblWhseIBT_Checksum])
values (@ibtno,'Goods Receive from FOB',$from,$to,3,4,5,0,NULL,0,NULL,NULL,NULL,NULL,0,(select sum(addcost) from #tmpcost),0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL)

declare @ibtid as int
set @ibtid=(select IDWhseIBT from [dbo].[_etblWhseIBT] where cIBTNumber=@ibtno)

insert into [dbo].[_etblWhseIBTAddCosts] 
([iWhseIBTID], [iSupplierID], [cReference], [cDescription], [fLineTotalExcl], [iTaxTypeID], [fLineTaxAmount], [iCurrencyID], [fExchangeRate], [fLineTotalExclForeign], [_etblWhseIBTAddCosts_iBranchID], [_etblWhseIBTAddCosts_dCreatedDate], [_etblWhseIBTAddCosts_dModifiedDate], [_etblWhseIBTAddCosts_iCreatedBranchID], [_etblWhseIBTAddCosts_iModifiedBranchID], [_etblWhseIBTAddCosts_iCreatedAgentID], [_etblWhseIBTAddCosts_iModifiedAgentID], [_etblWhseIBTAddCosts_iChangeSetID], [_etblWhseIBTAddCosts_Checksum])
values 
(@ibtid,$supplier, 'Importation cost','Importation costs', (select sum(addcost) from #tmpcost),7,0,(select isnull(iCurrencyID,0) from vendor where dclink=$supplier), 1,(select sum(addcost) from #tmpcost),0,NULL, NULL,NULL,NULL,NULL,NULL,NULL,NULL )

";

sqlsrv_query($conn, $insertmasteribt) or die(print_r( sqlsrv_errors(), true));
$id = $_POST['id'];
$qtyrec=$_POST['qtyrec'];
$unitcst=$_POST['unitcst'];
$unitid=$_POST['unitid'];
$invlineid=$_POST['invoicelineid'];
for($i = 0; $i < count($id); $i++){
$insertibtlines="
declare @ibtno as varchar(50)
set @ibtno=(select cast('IBT' as varchar(20))+cast(format(iNextNo,'000000') as varchar(20)) from _rtblrefbase where cRefType='NextWHIBTNo' and _rtblRefBase_iBranchID=0)

declare @ibtid as int
set @ibtid=(select IDWhseIBT from [dbo].[_etblWhseIBT] where cIBTNumber=@ibtno)

update _cplshipmentlines set rec_qty=(isnull(rec_qty,0))+$qtyrec[$i] where invoicelineid=$invlineid[$i]

insert into _etblwhseibtlines (iwhseIBTId, istockid,cReference,iProjectID,bIsSerialItem,bIsLotItem,iLotID,fQtyIssued,fAdditionalcost,fQtyReceived,fQtyDamaged,fQtyVariance,fQtyOverDelivered,fNewReceiveCost,fIssuedCost,iUnitsOfMeasureStockingID,iUnitsOfMeasureCategoryID,iUnitsOfMeasureID,fQtyRequired,fQtyApproved)
values (@ibtid,$id[$i],'IMPORT', 0,0,0,0,$qtyrec[$i],$unitcst[$i],$qtyrec[$i],0,0,0,0,$unitcst[$i],$unitid[$i],$unitid[$i],$unitid[$i],$qtyrec[$i],$qtyrec[$i])
";
sqlsrv_query($conn, $insertibtlines) or die(print_r( sqlsrv_errors(), true));

}

//insert into _cplibts the ibt numbers created and the shipment no
$sql = "insert into _cplibts (ibtno,shipment_no) select cast('IBT' as varchar(20))+cast(format(iNextNo,'000000') as varchar(20)) as ibtno,$query from _rtblrefbase where cRefType='NextWHIBTNo' and _rtblRefBase_iBranchID=0"; 
sqlsrv_query($conn,$sql) or die(print_r( sqlsrv_errors(), true));

$updateibtno="update _rtblrefbase set inextno=inextno+1 from _rtblrefbase where cRefType='NextWHIBTNo' and _rtblRefBase_iBranchID=0";
sqlsrv_query($conn, $updateibtno) or die(print_r( sqlsrv_errors(), true));

//update shipment as posted
$update="update _cplshipmentmaster set posted=1 where id=$query";
sqlsrv_query($conn, $update) or die(print_r( sqlsrv_errors(), true));

//retrieve ibts to display
$retrieve="select ibtno from _cplibts where shipment_no=$query";
$validate=sqlsrv_query($conn, $retrieve) or die(print_r( sqlsrv_errors(), true));
       while( $row = sqlsrv_fetch_array( $validate, SQLSRV_FETCH_ASSOC) ) { 
            $ibtno=$row['ibtno'];
            echo " IBT has been successfully created in sage as ".$ibtno."";
       }

}else {

//Ibt already in sage
$retrieve="select ibtno from _cplibts where shipment_no=$query";
$string='has been created';
$validate=sqlsrv_query($conn, $retrieve) or die(print_r( sqlsrv_errors(), true));
       while( $row = sqlsrv_fetch_array( $validate, SQLSRV_FETCH_ASSOC) ) { 
           $ibtno=$row['ibtno'];
           echo  " IBT already created in sage as ".$ibtno."";
       }
}          }
?>