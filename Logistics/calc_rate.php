<?php
		//require_once("insert.php");
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
          $query = $_POST['id'];
          $rate=$_POST['rate'] ?? 0;
        $updaterate="update _cplshipmentlines set rate=$rate from _cplshipmentlines ts join _cplshipmentmaster tr on
        ts.shipment_no=tr.shipment_no 
        where tr.id=$query 
        update _cplshipment set fexchrateusd=(case when foreigncurrencyid=1 then $rate else 0 end), fexchrateeur=(case when foreigncurrencyid=3 then $rate else 0 end) from _cplshipment a join invnum b on a.cshipmentno=b.ucIDPOrdShipmentNo
        join _cplshipmentmaster c on b.ucIDPOrdShipmentNo=c.shipment_no where c.id=$query";
        $updatetotals="IF OBJECT_ID('tempdb..#totals') IS NOT NULL DROP TABLE #totals
        select code,stkcode,round(sum(isnull(Cost,0))+(max(amount)*max(qty)*max(rate)),2) as totcost 
        into #totals
        from _cplshipmentlines ts
        join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
        where tr.id=$query
        group by code,stkcode
        
        update _cplshipmentlines set totals=tts.totcost from _cplshipmentlines ts  
        join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
        join #totals tts on ts.stkcode=tts.stkcode
        where tr.id=$query"; 
        sqlsrv_query($conn, $updaterate) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_query($conn, $updatetotals) or die(print_r( sqlsrv_errors(), true));

        //actual factor
        $actualfactor="IF OBJECT_ID('tempdb..#actualfactor') IS NOT NULL DROP TABLE #actualfactor
         select distinct invoicelineid,code,stkcode,round(((sum(ts.Cost)+(max(amount)*max(qty)*max(rate)))/(max(amount)*max(qty)*max(rate))),2) as factor
         into #actualfactor
         from _cplshipmentlines ts
         join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
         join _cplcostmaster cr on ts.costcode=cr.id
         where tr.id=cast($query as int)
         group by invoicelineid,code,stkcode
         
         update _cplshipmentlines set correctfactor=b.factor from _cplshipmentlines a join
         #actualfactor b on a.invoicelineid=b.invoicelineid";

        sqlsrv_query($conn, $actualfactor) or die(print_r( sqlsrv_errors(), true));
    sqlsrv_close($conn);

?>