<?php
		//require_once("insert.php");
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
          $query = $_POST['id'];
            $amt=$_POST['otherawb'] ?? 0;
            $rate=$_POST['rate'] ?? 0;
            $qtys="--get the sumation
            IF OBJECT_ID('tempdb..#tmpship') IS NOT NULL DROP TABLE #tmpship
            select invoicelineid,(_cplshipmentlines.code),max(costcode) as cc , (amount) as fob,max(qty) as qt, (sum(volume)*167) as tot, sum(unit_weight*qty) as totweight,(sum(amount*qty))as amt, (case when  (sum(volume)*167)>sum(unit_weight*qty) then (sum(volume)*167) else sum(unit_weight*qty) end) as highesttot  
            into #tmpship
            from _cplshipmentlines join _cplshipmentmaster on _cplshipmentlines.shipment_no=
            _cplshipmentmaster.shipment_no join _cplcostmaster cr on _cplshipmentlines.costcode=cr.id
            join StkItem st on _cplshipmentlines.stkcode=st.StockLink
            where 
            _cplshipmentmaster.id=$query and cr.cost='OtherChargesOnAWBOrSea' and ServiceItem<>1
            group by invoicelineid,_cplshipmentlines.code,costcode,amount";
            $amts="--get amts
            IF OBJECT_ID('tempdb..#tmpamts') IS NOT NULL DROP TABLE #tmpamts
            select distinct ts.invoicelineid, ts.code, cr.cost,ts.shipment_no,ts.qty,amount, (amount*qty) as tot,
            (case when max(cb.calcbase) in ('Weight') then (case when max(volume)*167>max(weight) then max(volume)*167 else max(weight) end)
            /sum(highesttot)
            when
            max(cb.calcbase) in ('Volume') then (case when max(volume)*167>max(weight) then max(volume)*167 else max(weight) end)
            /sum(highesttot)
            else 0 end) as vol_weigh_amts,
            case when max(cb.calcbase) =('FOB') then (((amount*qty))/(select nullif(sum(amt),0) from #tmpship))  else 0 end as fobamt, case when max(cb.calcbase) in ('#NA') then max(ts.cost) end as import
            into #tmpamts
            from _cplshipmentlines ts join _cplScheme ce on ts.costcode=ce.Cost_Code and ce.Scheme=ts.scheme  join _cplshipmentmaster ttr on ts.shipment_no=ttr.shipment_no
            join #tmpship on ts.costcode=#tmpship.cc join _cplcalcbase cb on ce.calcbase=cb.id
            join _cplcostmaster cr on ts.costcode=cr.id join stkitem st on ts.stkcode=st.StockLink
            where ttr.id=$query and cr.cost='OtherChargesOnAWBOrSea' and serviceitem<>1
            group by ts.invoicelineid,cr.cost,ts.shipment_no,ts.qty,ts.code,amount
            order by ts.code";
            $rates="--get rates
            IF OBJECT_ID('tempdb..#rates') IS NOT NULL DROP TABLE #rates
            select invoicelineid,code,cost,$amt*case when (cost)='OtherChargesOnAWBOrSea' then nullif(sum(vol_weigh_amts+fobamt),0) else nullif(0,0) end as swift
            into #rates
            from #tmpamts 
            group by invoicelineid,code,cost";
            $updates="--update _cplshipmentlines
            update _cplshipmentlines set cost= swift,duty_modified_date=getdate()
            from _cplshipmentlines ts join #rates ty on ts.invoicelineid=ty.invoicelineid
            join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
            join _cplcostmaster cr on ts.costcode=cr.id join stkitem st on ts.stkcode=st.StockLink
            where tr.id=cast($query as int)  and ts.active='True' and cr.cost='OtherChargesOnAWBOrSea' and st.serviceitem<>1";
        $close="update _cplshipmentlines set updated='True' from _cplshipmentlines ts join _cplshipmentmaster ttr 
        on ttr.shipment_no=ts.shipment_no where ttr.id=$query"; 
        $calcrate="update _cplshipmentlines set cost= (case when ce.calcbase=3 then cost*1 else cost*1 end) from _cplshipmentlines ts join _cplshipmentmaster tr on
        ts.shipment_no=tr.shipment_no join _cplScheme ce on ts.scheme=ce.Scheme and ts.costcode=ce.Cost_Code
        where tr.id=$query and ts.rate=1";
        $updaterate="update _cplshipmentlines set rate=$rate from _cplshipmentlines ts join _cplshipmentmaster tr on
        ts.shipment_no=tr.shipment_no 
        where tr.id=$query ";
        $updatetotals="IF OBJECT_ID('tempdb..#totals') IS NOT NULL DROP TABLE #totals
          select code,stkcode, case when max(costcode) in (2,3,4,5,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,30,31,32,33) then round(sum(Cost)+(max(amount)*max(qty)*max(rate)),2) end as totcost 
          into #totals
          from _cplshipmentlines ts
          join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
          where tr.id=cast($query as int)
          group by code,stkcode
        
        update _cplshipmentlines set totals=tts.totcost from _cplshipmentlines ts  
        join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
        join #totals tts on ts.stkcode=tts.stkcode
        where tr.id=$query";
        sqlsrv_query($conn, $qtys)  or die(print_r( sqlsrv_errors(), true)) ;
        sqlsrv_query($conn, $amts) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_query($conn, $rates) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_query($conn, $updates) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_query($conn, $close) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_query($conn, $calcrate) or die(print_r( sqlsrv_errors(), true)); 
        //sqlsrv_query($conn, $updaterate) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_query($conn, $updatetotals) or die(print_r( sqlsrv_errors(), true));

        //duty
        $duty="IF OBJECT_ID('tempdb..#duty') IS NOT NULL DROP TABLE #duty;
         select distinct invoicelineid, cs.code, max(cme.scheme) as scheme,
     case when max(cmode)='AIR' then (max(amount*cs.rate*qty)+
         max(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0)else 0 end)+
         max(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0) else 0 end))
     when max(cmode)='SEA' then (max(amount*cs.rate*qty)+
         max(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0)else 0 end)+
         max(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0) else 0 end)) 
     + (max(case when cr.cost='FreightKsh' then isnull(cs.cost,0) else 0 end)) 
     else 0 end as customs,
         0 as duty
         into #duty
         from stkitem st
         join _cplshipmentlines cs
         on st.stocklink=cs.stkcode join _cplshipmentmaster tm on cs.shipment_no=tm.shipment_no 
         join _cplcostmaster cr on cs.costcode=cr.id
         join _cplScheme cme on cs.scheme=cme.Scheme
     join _cplshipment ct on cs.shipment_no=ct.cShipmentNo
         where tm.id=cast($query as int) and st.ServiceItem<>1
         group by invoicelineid,cs.code
        
        update #duty set duty =ce.rate from #duty join _cplscheme ce on #duty.scheme=ce.Scheme
        join _cplcostmaster cr 
        on ce.Cost_Code=cr.id where cost='duty' 
        
        update _cplshipmentlines set cost=((cast(duty as float)/100)*cast(customs as float)) from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #duty dt on cs.invoicelineid=dt.invoicelineid
        join _cplcostmaster cr on cs.costcode=cr.id
        where tm.id=cast($query as int) and cr.cost='Duty' and calc_duty=1";
        sqlsrv_query($conn, $duty) or die(print_r( sqlsrv_errors(), true));

        //excise duty
        $exciseduty="IF OBJECT_ID('tempdb..#exciseduty') IS NOT NULL DROP TABLE #exciseduty;
         select distinct invoicelineid, cs.code, max(cme.scheme) as scheme, case when max(cmode)='AIR' then (max(amount*cs.rate*qty)+
         max(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0)else 0 end)+
         max(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0) else 0 end))
     when max(cmode)='SEA' then (max(amount*cs.rate*qty)+
         max(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0)else 0 end)+
         max(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0) else 0 end)) 
     + (max(case when cr.cost='FreightKsh' then isnull(cs.cost,0) else 0 end)) 
     else 0 end as customs,
         0 as exciseduty
         into #exciseduty
         from stkitem st
        join _cplshipmentlines cs
         on st.stocklink=cs.stkcode join _cplshipmentmaster tm on cs.shipment_no=tm.shipment_no 
         join _cplcostmaster cr on cs.costcode=cr.id
         join _cplScheme cme on cs.scheme=cme.Scheme
         join _cplshipment ct on cs.shipment_no=ct.cShipmentNo
         where tm.id=cast($query as int) and st.ServiceItem<>1
         group by invoicelineid,cs.code
        
        update #exciseduty set exciseduty =isnull(ce.vat,0) from #exciseduty join _cplscheme ce on #exciseduty.scheme=ce.Scheme
        join _cplcostmaster cr 
        on ce.Cost_Code=cr.id where cost='EXCISE_DUTY'
        
        --update excise duty
        update _cplshipmentlines set cost=((cast(exciseduty as float)/100)*cast(customs as float)) from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #exciseduty dt on cs.invoicelineid=dt.invoicelineid
        join _cplcostmaster cr on cs.costcode=cr.id
        where tm.id=cast($query as int) and cr.cost='EXCISE_DUTY'
        
        --update customs value
        update _cplshipmentlines set customs_value=customs from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #exciseduty dt on cs.invoicelineid=dt.invoicelineid
        where tm.id=cast($query as int)";
        sqlsrv_query($conn, $exciseduty) or die(print_r( sqlsrv_errors(), true)); 
        
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

        $updaterailway="--update railway levy
        update _cplshipmentlines set cost=(0.02*cast(customs as float)) from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #duty dt on cs.invoicelineid=dt.invoicelineid
        join _cplcostmaster cr on cs.costcode=cr.id join stkitem st on cs.stkcode=st.StockLink
        where tm.id=cast($query as int) and cr.cost='RailwayLevy'   and st.ServiceItem<>1";

        $updategok="--update gok
        update _cplshipmentlines set cost=(0.035*cast(customs as float)) from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #duty dt on cs.invoicelineid=dt.invoicelineid
        join _cplcostmaster cr on cs.costcode=cr.id join stkitem st on cs.stkcode=st.StockLink
        where tm.id=cast($query as int) and cr.cost='GOK'   and st.ServiceItem<>1";

        sqlsrv_query($conn, $updaterailway) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_query($conn, $updategok) or die(print_r( sqlsrv_errors(), true));
        
    sqlsrv_close($conn);
?>