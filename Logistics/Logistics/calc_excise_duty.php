<?php
      //require_once("insert.php");
      include("config.php");
        $conn = sqlsrv_connect( $servername, $connectioninfo);
        $query = $_POST['id'];
          $value=$_POST['excise'] ?? '';
          $rate=$_POST['rate'] ?? 0;

          if ($value=='yes'){
        //EXCISE DUTY FORMULAE
        $exciseduty="IF OBJECT_ID('tempdb..#exciseduty') IS NOT NULL DROP TABLE #exciseduty;
        select distinct invoicelineid, cs.code, max(cme.scheme) as scheme, case when max(cmode) in ('AIR','Courier') then (max(amount*cs.rate*qty)+
        max(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0)else 0 end)+
        max(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0) else 0 end))
        when max(cmode)='SEA' then (max(amount*cs.rate*qty)+
        max(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0)else 0 end)+
        max(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0) else 0 end)) 
        + (max(case when cr.cost='FreightKsh' then isnull(cs.cost,0) else 0 end)) 
        else 0 end as customs,
        0 as exciseduty,0 as dutyperc
        into #exciseduty
        from stkitem st
        join _cplshipmentlines cs
        on st.stocklink=cs.stkcode join _cplshipmentmaster tm on cs.shipment_no=tm.shipment_no 
        join _cplcostmaster cr on cs.costcode=cr.id
        join _cplScheme cme on cs.scheme=cme.Scheme
        join _cplshipment ct on cs.shipment_no=ct.cShipmentNo
        where tm.id=cast($query as int) and st.ServiceItem<>1
        group by invoicelineid,cs.code

        update #exciseduty set exciseduty= isnull(c.Excisepercent,0)  from #exciseduty ey join Stkitem ce on ey.code=ce.Code
        join _etblEUCommodity b on b.IDEUCommodity=ce.iEUCommodityID
        join _CplHScode c on c.HSCode=b.cEUCommodityCode
        where ce.Cost_Code=30";
      /* //Update percentage on #exciseduty table as per scheme
        $upeduty="update #exciseduty set exciseduty =isnull(ce.vat,0) from #exciseduty join _cplscheme ce on #exciseduty.scheme=ce.Scheme
        join _cplcostmaster cr 
        on ce.Cost_Code=cr.id where cost='EXCISE_DUTY'"; */
  
        $updateexcise=" --update excise duty
        update _cplshipmentlines set cost=((cast(exciseduty as float)/100))*(cast(customs as float)+(cast(customs as float)*dutyperc/100)) from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #exciseduty dt on cs.invoicelineid=dt.invoicelineid
        join _cplcostmaster cr on cs.costcode=cr.id
        where tm.id=cast($query as int) and cr.cost='EXCISE_DUTY'";
        sqlsrv_query($conn, $exciseduty) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_query($conn, $updateexcise) or die(print_r( sqlsrv_errors(), true));
          }else{
            $updateexcise2=" --update excise duty
            update _cplshipmentlines set cost=0 from _cplshipmentlines cs join _cplshipmentmaster tm
            on cs.shipment_no=tm.shipment_no 
            join _cplcostmaster cr on cs.costcode=cr.id
            where tm.id=cast($query as int) and cr.cost='EXCISE_DUTY'";
            sqlsrv_query($conn, $updateexcise2) or die(print_r( sqlsrv_errors(), true));
          }
    sqlsrv_close($conn);

?>