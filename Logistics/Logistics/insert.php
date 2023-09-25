<?php
session_start();
include("config.php");
if(isset($_POST["submit"])) {
    print_r($_POST["users"]);
    foreach ($_POST["users"] as $key => $value){
        $id=$_POST["users"][$key];
        $update="insert into _cplshipmentlines(shipment_no,code, description,qty,amount,
        volume,unit_weight,scheme,Costcode,stkcode,active,invoicelineid,po_no,tot_amount,
        weight,clientid,grv_no,grv_qty,calc_duty,factor,rate)
        select '" .$_SESSION['shipment_no']. "', st.Code,st.Description_1 ,
        isnull(bl.fQtyLastProcess,0), isnull(fUnitPriceInclForeign,0),isnull(st.ufIIVolume,0)
        ,isnull(st.ufIIWeight,0),st.ucIIScheme,ce.Cost_Code,bl.iStockCodeID,'True',$id,Ordernum
        ,(isnull(bl.fQtyLastProcess,0)*isnull(fUnitPriceInclForeign,0)),(isnull(bl.fQtyLastProcess,0)*isnull(st.ufIIWeight,0))
        ,im.AccountID,invnumber,fquantity,1,ufIIImportFactor, (case when foreigncurrencyid=1 and isnull(fexchrateusd,0)<>0 then fexchrateusd when foreigncurrencyid=3 and isnull(fexchrateeur,0)<>0 then fexchrateeur else 1 end) 
        from _btblinvoicelines bl join StkItem st on 
        bl.istockcodeid=st.stocklink join invnum im on bl.iInvoiceID=im.AutoIndex 
        join _cplScheme ce on st.ucIIScheme=ce.Scheme
        join _cplcostmaster cr on ce.Cost_Code=cr.id  
        join _etblUserHistLink ek on ek.TableID=im.autoindex 
        join _rtblUserDict rt on ek.UserDictID=rt.idUserDict and uservalue='" .$_SESSION['shipment_no']. "'
        join _cplshipment v on v.cShipmentNo=ek.UserValue
        where bl.idinvoicelines=$id and rt.cFieldName='ucIDPOrdShipmentNo'";
        sqlsrv_query($conn, $update) or die(print_r( sqlsrv_errors(), true));
    }
        $insship="insert into _cplshipmentmaster (shipment_no,posted)
        values ('" .$_SESSION['shipment_no']. "',0)";
        sqlsrv_query($conn, $insship) or die(print_r( sqlsrv_errors(), true));
    }
        echo("<script>alert('Shipment successfully updated');</script>");
    header("Location:CostEstimateHome.php");
?>


