<?php
//require_once("insert.php");
include("config.php");
//session_start();
$conn = sqlsrv_connect( $servername, $connectioninfo);
$query=$_POST['shipment'];
$from=$_POST['transit'] ?? 0;
$to=$_POST['final'] ?? 0;
$supplier=$_POST['vendor'] ?? '';
//$_SESSION['vendor']=$supplier;

/*Check if shipment was posted sage*/
$posted="select (case when sum(qty)<>sum(rec_qty) then 0 else max(posted) end) as posted from _cplshipmentmaster a join 
_cplshipmentlines b on a.shipment_no=b.shipment_no 
where a.id=$query";
$chkone=sqlsrv_query($conn, $posted) or die(print_r( sqlsrv_errors(), true));
while( $row = sqlsrv_fetch_array( $chkone, SQLSRV_FETCH_ASSOC) ) { 

// If IBT was not created in sage then

if (($row["posted"]==0)){

//update the two tables to zero. Values will be updated to check warehouses
$update="update _cplchkwh2 set ct=0
update _cplchkwhother set ct=0";
sqlsrv_query($conn, $update);

//check if the shipment has project no 2
$check_if_to_post="select count(ilineprojectid) as ilineprojectid1 from (
select distinct ilineprojectid,code, max(isnull(grv_qty,0))-max(isnull(rec_qty,0)) as diff 
from _btblinvoicelines bs 
join invnum im on bs.iInvoiceID=im.AutoIndex 
join _etblUserHistLink ek on ek.TableID=im.autoindex 
join _rtblUserDict rt on ek.UserDictID=rt.idUserDict
join _cplshipmentmaster cr on ek.UserValue=cr.shipment_no 
join _cplshipmentlines cs on cs.invoicelineid=bs.idInvoiceLines
where cr.id=$query and docflag=1 and iLineProjectID=2 and rt.cFieldName='ucIDPOrdShipmentNo'
group by iLineProjectID,code)pp
group by iLineProjectID";
$chk=sqlsrv_query($conn, $check_if_to_post) or die(print_r( sqlsrv_errors(), true));
while( $row = sqlsrv_fetch_array( $chk, SQLSRV_FETCH_ASSOC) ) { 
/*We may need to generate 2 IBTs based on MDS and other than MDS items. For MDS items IBT will be from FOB to LEASE warehouse. For others it will be from FOb to NBI-ARY-MAIN warehouse*/
   if (($row["ilineprojectid1"]>0)){

        $id = $_POST['id'];
        $qtyrec=$_POST['qtyrec'];
        $invlineid=$_POST['invoicelineid'];
        for($i = 0; $i < count($id); $i++){
        // if line items also belong to project no 2 then update cplchkwh2
        $chckid="update _cplchkwh2 set ct=ct+isnull((select $qtyrec[$i]  from _btblinvoicelines where idinvoicelines=$invlineid[$i] and $qtyrec[$i]>0 and iLineProjectID=2),0)
        ";
            sqlsrv_query($conn, $chckid) or die(print_r( sqlsrv_errors(), true));
        }

        $chck="select ct from _cplchkwh2";
            $valid=sqlsrv_query($conn, $chck) or die(print_r( sqlsrv_errors(), true));
                while( $row = sqlsrv_fetch_array( $valid, SQLSRV_FETCH_ASSOC) ) { 
                    //if some line items belong to project no 2 then perform the following
                    if (($row["ct"]>0 )) {
        $insertmasteribt="
        declare @ibtno as varchar(50)
        set @ibtno=(select cast('IBT' as varchar(20))+cast(format(iNextNo,'000000') as varchar(20)) from _rtblrefbase where cRefType='NextWHIBTNo' and _rtblRefBase_iBranchID=0)


        insert into [dbo].[_etblWhseIBT] ([cIBTNumber], [cIBTDescription], [iWhseIDFrom], [iWhseIDTo], [iWhseIDIntransit], [iWhseIDVariance], [iWhseIDDamaged], [iIBTStatus], [cDelNoteNumber], [iProjectID], [dDateIssued], [dDateReceived], [cAuditNumberIssued], [cAuditNumberReceived], [bUseAddCostPerLine], [fFixedAddCost], [iAgentIDIssue], [iAgentIDReceive], [iBranchIDFrom], [dDateRequired], [dDateRequested], [dDateApproved], [iLinkedReqID], [_etblWhseIBT_iBranchID], [_etblWhseIBT_dCreatedDate], [_etblWhseIBT_dModifiedDate], [_etblWhseIBT_iCreatedBranchID], [_etblWhseIBT_iModifiedBranchID], [_etblWhseIBT_iCreatedAgentID], [_etblWhseIBT_iModifiedAgentID], [_etblWhseIBT_iChangeSetID], [_etblWhseIBT_Checksum],[ucIBTGRVNo])
        values (@ibtno,'Goods Receive from FOB',$from,31,3,79,5,0,NULL,0,format(getdate(),'yyyy-MM-dd'),format(getdate(),'yyyy-MM-dd'),NULL,NULL,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,(select shipment_no from _cplshipmentmaster where id=$query))

        declare @ibtid as int
        set @ibtid=(select IDWhseIBT from [dbo].[_etblWhseIBT] where cIBTNumber=@ibtno)

        insert into [dbo].[_etblWhseIBTAddCosts] 
        ([iWhseIBTID], [iSupplierID], [cReference], [cDescription], [fLineTotalExcl], [iTaxTypeID], [fLineTaxAmount], [iCurrencyID], [fExchangeRate], [fLineTotalExclForeign], [_etblWhseIBTAddCosts_iBranchID], [_etblWhseIBTAddCosts_dCreatedDate], [_etblWhseIBTAddCosts_dModifiedDate], [_etblWhseIBTAddCosts_iCreatedBranchID], [_etblWhseIBTAddCosts_iModifiedBranchID], [_etblWhseIBTAddCosts_iCreatedAgentID], [_etblWhseIBTAddCosts_iModifiedAgentID], [_etblWhseIBTAddCosts_iChangeSetID], [_etblWhseIBTAddCosts_Checksum])
        values 
        (@ibtid,$supplier, @ibtno,'Total Additional Costs', 0,10,0,(select isnull(iCurrencyID,0) from vendor where dclink=$supplier), 1,0,0,NULL, NULL,NULL,NULL,NULL,NULL,NULL,NULL)";

        sqlsrv_query($conn, $insertmasteribt) or die(print_r( sqlsrv_errors(), true));
    }}
        // insert ibt lines
        $id = $_POST['id'];
        $qtyrec=$_POST['qtyrec'];
        $unitcst=$_POST['unitcst'];
        $unitid=$_POST['unitid'];
        $invlineid=$_POST['invoicelineid'];
        for($i = 0; $i < count($id); $i++){
        $chckid="select ilineprojectid from _btblinvoicelines where idinvoicelines=$invlineid[$i]";
            $validate=sqlsrv_query($conn, $chckid) or die(print_r( sqlsrv_errors(), true));
                while( $row = sqlsrv_fetch_array( $validate, SQLSRV_FETCH_ASSOC) ) { 
                    if (($row["ilineprojectid"]==2 ) and ($qtyrec[$i]>0)) {
                        $insertibtlines="
                        declare @ibtno as varchar(50)
                        set @ibtno=(select cast('IBT' as varchar(20))+cast(format(iNextNo,'000000') as varchar(20)) from _rtblrefbase where cRefType='NextWHIBTNo' and _rtblRefBase_iBranchID=0)

                        declare @ibtid as int
                        set @ibtid=(select IDWhseIBT from [dbo].[_etblWhseIBT] where cIBTNumber=@ibtno)


                        insert into _etblwhseibtlines (iwhseIBTId, istockid,cReference,iProjectID,bIsSerialItem,bIsLotItem,iLotID,fQtyIssued,fAdditionalcost,fQtyReceived,fQtyDamaged,fQtyVariance,fQtyOverDelivered,fNewReceiveCost,fIssuedCost,iUnitsOfMeasureStockingID,iUnitsOfMeasureCategoryID,iUnitsOfMeasureID,fQtyRequired,fQtyApproved)
                        (select @ibtid,iStockCodeID, idInvoiceLines, ilineprojectid,0,0,0, $qtyrec[$i], $unitcst[$i],$qtyrec[$i],0,0,0,0,$unitcst[$i],$unitid[$i],$unitid[$i],$unitid[$i],$qtyrec[$i],$qtyrec[$i] from _btblinvoicelines where idinvoicelines=$invlineid[$i] and ilineprojectid=2)
                        update _cplshipmentlines set rec_qty=(isnull(rec_qty,0)-isnull(rec_qty,0))+$qtyrec[$i] where invoicelineid=$invlineid[$i]

                        update _etblWhseIBT set fFixedAddCost=fFixedAddCost+$unitcst[$i] where IDWhseIBT=@ibtid

                        update _etblWhseIBTAddCosts set  fLineTotalExcl = fLineTotalExcl+$unitcst[$i], fLineTotalExclForeign=
                        fLineTotalExclForeign+$unitcst[$i] where iWhseIBTID=@ibtid";
                        
                        sqlsrv_query($conn, $insertibtlines) or die(print_r( sqlsrv_errors(), true));
                        } else {

                        }}
                        }

                        $chck="select ct from _cplchkwh2";
                        $valid=sqlsrv_query($conn, $chck) or die(print_r( sqlsrv_errors(), true));
                            while( $row = sqlsrv_fetch_array( $valid, SQLSRV_FETCH_ASSOC) ) { 
                                if (($row["ct"]>0 )) {
                        
                        //insert the ibt number created as well as the shipment id in the table below
                        $ibtno="insert into _cplibts (ibtno,shipment_no) select (cast('IBT' as varchar(20))+cast(format(iNextNo,'000000') as varchar(20))) as ibtno, $query from _rtblrefbase where cRefType='NextWHIBTNo' and _rtblRefBase_iBranchID=0";
                        $params = array();
                        $options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
                        sqlsrv_query($conn, $ibtno,$params,$options);

                        //update the next ibt number.
                        $updateibtno="update _rtblrefbase set inextno=inextno+1 from _rtblrefbase where cRefType='NextWHIBTNo' and _rtblRefBase_iBranchID=0";
                        sqlsrv_query($conn, $updateibtno) or die(print_r( sqlsrv_errors(), true));
                    }}
                } else {
}
}

//Perform check of the shipment that do not belong to project number 2
$check2="select count(ilineprojectid) as ilineprojectid from (
    select distinct ilineprojectid,code, max(isnull(grv_qty,0))-max(isnull(rec_qty,0)) as diff 
    from _btblinvoicelines bs 
    join invnum im on bs.iInvoiceID=im.AutoIndex 
    join _etblUserHistLink ek on ek.TableID=im.autoindex 
    join _rtblUserDict rt on ek.UserDictID=rt.idUserDict
    join _cplshipmentmaster cr on ek.UserValue=cr.shipment_no 
    join _cplshipmentlines cs on cs.invoicelineid=bs.idInvoiceLines
    where cr.id=$query and docflag=1 and rt.cFieldName='ucIDPOrdShipmentNo'
    group by iLineProjectID,code)pp
    where iLineProjectID<>2";
$val2=sqlsrv_query($conn, $check2) or die(print_r( sqlsrv_errors(), true));
while( $row = sqlsrv_fetch_array( $val2, SQLSRV_FETCH_ASSOC) ) { 
if (($row["ilineprojectid"]>0)){

        $id = $_POST['id'];
        $qtyrec=$_POST['qtyrec'];
        $invlineid=$_POST['invoicelineid'];
        for($i = 0; $i < count($id); $i++){

        //update this warehouse if there are lines that do not belong to project no 2
        $chckid="update _cplchkwhother set ct=isnull(ct,0)+isnull((select $qtyrec[$i]  from _btblinvoicelines where idinvoicelines=$invlineid[$i] and $qtyrec[$i]>0 and iLineProjectID<>2),0)
        ";
        sqlsrv_query($conn, $chckid) or die(print_r( sqlsrv_errors(), true));
        }

        $chck="select ct from _cplchkwhother";
            $valid=sqlsrv_query($conn, $chck) or die(print_r( sqlsrv_errors(), true));
                while( $row = sqlsrv_fetch_array( $valid, SQLSRV_FETCH_ASSOC) ) { 
                    if (($row["ct"]>0 )) {

                //insert into ibt master        
                $insertmasteribt="
                declare @ibtno as varchar(50)
                set @ibtno=(select cast('IBT' as varchar(20))+cast(format(iNextNo,'000000') as varchar(20)) from _rtblrefbase where cRefType='NextWHIBTNo' and _rtblRefBase_iBranchID=0)


                insert into [dbo].[_etblWhseIBT] ([cIBTNumber], [cIBTDescription], [iWhseIDFrom], [iWhseIDTo], [iWhseIDIntransit], [iWhseIDVariance], [iWhseIDDamaged], [iIBTStatus], [cDelNoteNumber], [iProjectID], [dDateIssued], [dDateReceived], [cAuditNumberIssued], [cAuditNumberReceived], [bUseAddCostPerLine], [fFixedAddCost], [iAgentIDIssue], [iAgentIDReceive], [iBranchIDFrom], [dDateRequired], [dDateRequested], [dDateApproved], [iLinkedReqID], [_etblWhseIBT_iBranchID], [_etblWhseIBT_dCreatedDate], [_etblWhseIBT_dModifiedDate], [_etblWhseIBT_iCreatedBranchID], [_etblWhseIBT_iModifiedBranchID], [_etblWhseIBT_iCreatedAgentID], [_etblWhseIBT_iModifiedAgentID], [_etblWhseIBT_iChangeSetID], [_etblWhseIBT_Checksum],ucIBTGRVNo)
                values (@ibtno,'Goods Receive from FOB',$from,23,3,79,5,0,NULL,0,format(getdate(),'yyyy-MM-dd'),format(getdate(),'yyyy-MM-dd'),NULL,NULL,0,0,0,0,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,(select shipment_no from _cplshipmentmaster where id=$query))

                declare @ibtid as int
                set @ibtid=(select IDWhseIBT from [dbo].[_etblWhseIBT] where cIBTNumber=@ibtno)

                insert into [dbo].[_etblWhseIBTAddCosts] 
                ([iWhseIBTID], [iSupplierID], [cReference], [cDescription], [fLineTotalExcl], [iTaxTypeID], [fLineTaxAmount], [iCurrencyID], [fExchangeRate], [fLineTotalExclForeign], [_etblWhseIBTAddCosts_iBranchID], [_etblWhseIBTAddCosts_dCreatedDate], [_etblWhseIBTAddCosts_dModifiedDate], [_etblWhseIBTAddCosts_iCreatedBranchID], [_etblWhseIBTAddCosts_iModifiedBranchID], [_etblWhseIBTAddCosts_iCreatedAgentID], [_etblWhseIBTAddCosts_iModifiedAgentID], [_etblWhseIBTAddCosts_iChangeSetID], [_etblWhseIBTAddCosts_Checksum])
                values 
                (@ibtid,$supplier, @ibtno,'Total Additional Cost', 0,10,0,(select isnull(iCurrencyID,0) from vendor where dclink=$supplier), 1,0,0,NULL, NULL,NULL,NULL,NULL,NULL,NULL,NULL)";

                sqlsrv_query($conn, $insertmasteribt) or die(print_r( sqlsrv_errors(), true));
            }
        }
                //insert the ibt lines
                $id = $_POST['id'];
                $qtyrec=$_POST['qtyrec'];
                $unitcst=$_POST['unitcst'];
                $unitid=$_POST['unitid'];
                $invlineid=$_POST['invoicelineid'];
                for($i = 0; $i < count($id); $i++){
                $chckid2="select ilineprojectid, fQtyLastProcess from _btblinvoicelines where idinvoicelines=$invlineid[$i]";
                    $validate2=sqlsrv_query($conn, $chckid2) or die(print_r( sqlsrv_errors(), true));
                        while( $row = sqlsrv_fetch_array( $validate2, SQLSRV_FETCH_ASSOC) ) { 
                            if (($row["ilineprojectid"]!=2) and ($qtyrec[$i]>0)) {
                                $insertibtlines="
                                declare @ibtno as varchar(50)
                                set @ibtno=(select cast('IBT' as varchar(20))+cast(format(iNextNo,'000000') as varchar(20)) from _rtblrefbase where cRefType='NextWHIBTNo' and _rtblRefBase_iBranchID=0)

                                declare @ibtid as int
                                set @ibtid=(select IDWhseIBT from [dbo].[_etblWhseIBT] where cIBTNumber=@ibtno)

                                update _cplshipmentlines set rec_qty=(isnull(rec_qty,0))+$qtyrec[$i] where invoicelineid=$invlineid[$i]


                                insert into _etblwhseibtlines (iwhseIBTId, istockid,cReference,iProjectID,bIsSerialItem,bIsLotItem,iLotID,fQtyIssued,fAdditionalcost,fQtyReceived,fQtyDamaged,fQtyVariance,fQtyOverDelivered,fNewReceiveCost,fIssuedCost,iUnitsOfMeasureStockingID,iUnitsOfMeasureCategoryID,iUnitsOfMeasureID,fQtyRequired,fQtyApproved)
                                (select @ibtid,iStockCodeID, idInvoiceLines, ilineprojectid,0,0,0, $qtyrec[$i], $unitcst[$i],$qtyrec[$i],0,0,0,0,$unitcst[$i],$unitid[$i],$unitid[$i],$unitid[$i],$qtyrec[$i],$qtyrec[$i] from _btblinvoicelines where idinvoicelines=$invlineid[$i] and ilineprojectid<>2)

                                update _etblWhseIBT set fFixedAddCost=fFixedAddCost+$unitcst[$i] where IDWhseIBT=@ibtid

                                update _etblWhseIBTAddCosts set  fLineTotalExcl = fLineTotalExcl+$unitcst[$i], fLineTotalExclForeign=
                                fLineTotalExclForeign+$unitcst[$i] where iWhseIBTID=@ibtid";
                                sqlsrv_query($conn, $insertibtlines) or die(print_r( sqlsrv_errors(), true));
                                } else {

                                }}      
                                }
                                $chck="select ct from _cplchkwhother";
                                $valid=sqlsrv_query($conn, $chck) or die(print_r( sqlsrv_errors(), true));
                                    while( $row = sqlsrv_fetch_array( $valid, SQLSRV_FETCH_ASSOC) ) { 
                                        if (($row["ct"]>0 )) {
                                
                                //insert into _cplibts the ibt numbers created and the shipment no
                                $sql = "insert into _cplibts (ibtno,shipment_no) select cast('IBT' as varchar(20))+cast(format(iNextNo,'000000') as varchar(20)) as ibtno,$query from _rtblrefbase where cRefType='NextWHIBTNo' and _rtblRefBase_iBranchID=0"; 
                                sqlsrv_query($conn,$sql) or die(print_r( sqlsrv_errors(), true));

                                //update the next automatic ibt number
                                $updateibtno="update _rtblrefbase set inextno=inextno+1 from _rtblrefbase where cRefType='NextWHIBTNo' and _rtblRefBase_iBranchID=0";
                                sqlsrv_query($conn, $updateibtno) or die(print_r( sqlsrv_errors(), true));    
                            }}
           } else {

           }
 }     

 //update shipment as posted
 $update="update _cplshipmentmaster set posted=1 where id=$query";
 sqlsrv_query($conn, $update) or die(print_r( sqlsrv_errors(), true));
 
 //retrieve ibts to display
 $retrieve="select ibtno from _cplibts where shipment_no=$query";
 $string='has been created';
 $validate=sqlsrv_query($conn, $retrieve) or die(print_r( sqlsrv_errors(), true));
        while( $row = sqlsrv_fetch_array( $validate, SQLSRV_FETCH_ASSOC) ) { 
            echo  $row['ibtno'];
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