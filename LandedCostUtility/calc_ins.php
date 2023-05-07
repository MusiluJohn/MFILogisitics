<?php
		//require_once("insert.php");
        include("config.php");
		  $conn = sqlsrv_connect( $servername, $connectioninfo);
          $query = $_POST['id'];
            $amt=$_POST['ins'] ?? 0;
            $rate=$_POST['rate'] ?? 0;
            $qtys="--get the sumation
            IF OBJECT_ID('tempdb..#tmpship') IS NOT NULL DROP TABLE #tmpship
            select invoicelineid,(code),max(costcode) as cc , (amount) as fob,max(qty) as qt, (sum(volume)) as tot, sum(unit_weight*qty) as totweight,(sum(amount*qty))as amt 
            into #tmpship
            from _cplshipmentlines join _cplshipmentmaster on _cplshipmentlines.shipment_no=
            _cplshipmentmaster.shipment_no join _cplcostmaster cr on _cplshipmentlines.costcode=cr.id
            where 
            _cplshipmentmaster.id=$query and cr.cost='InsuranceAmount'
            group by invoicelineid,code,costcode,amount";
            $amts="--get amts
            IF OBJECT_ID('tempdb..#tmpamts') IS NOT NULL DROP TABLE #tmpamts
            select distinct ts.invoicelineid, ts.code, cr.cost,ts.shipment_no,ts.qty,amount, (amount*qty) as tot,(case when max(cb.calcbase) in ('Volume') then (max(volume)/nullif(sum(#tmpship.tot),0)) when max(cb.calcbase)='weight' then (max(unit_weight*qty)/nullif(sum(totweight),0))  else 0 end) as vol_weigh_amts,
            case when max(cb.calcbase) =('FOB') then (((amount*qty))/(select nullif(sum(amt),0) from #tmpship))  else 0 end as fobamt, case when max(cb.calcbase) in ('#NA') then max(ts.cost) end as import
            into #tmpamts
            from _cplshipmentlines ts join _cplScheme ce on ts.costcode=ce.Cost_Code and ce.Scheme=ts.scheme  join _cplshipmentmaster ttr on ts.shipment_no=ttr.shipment_no
            join #tmpship on ts.costcode=#tmpship.cc join _cplcalcbase cb on ce.calcbase=cb.id
            join _cplcostmaster cr on ts.costcode=cr.id
            where ttr.id=$query and cr.cost='InsuranceAmount'
            group by ts.invoicelineid,cr.cost,ts.shipment_no,ts.qty,ts.code,amount
            order by ts.code";
            $rates="--get rates
            IF OBJECT_ID('tempdb..#rates') IS NOT NULL DROP TABLE #rates
            select invoicelineid,code,cost,$amt*case when (cost)='InsuranceAmount' then nullif(sum(vol_weigh_amts+fobamt),0) else nullif(0,0) end as swift
            into #rates
            from #tmpamts 
            group by invoicelineid,code,cost";
            $updates="--update _cplshipmentlines
            update _cplshipmentlines set cost= swift
            from _cplshipmentlines ts join #rates ty on ts.invoicelineid=ty.invoicelineid
            join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
            join _cplcostmaster cr on ts.costcode=cr.id
            where tr.id=cast($query as int)  and ts.active='True' and cr.cost='InsuranceAmount'";
        $close="update _cplshipmentlines set updated='True' from _cplshipmentlines ts join _cplshipmentmaster ttr 
        on ttr.shipment_no=ts.shipment_no where ttr.id=$query"; 
        $calcrate="update _cplshipmentlines set cost= (case when ce.calcbase=3 then cost*1 else cost*1 end) from _cplshipmentlines ts join _cplshipmentmaster tr on
        ts.shipment_no=tr.shipment_no join _cplScheme ce on ts.scheme=ce.Scheme and ts.costcode=ce.Cost_Code
        where tr.id=$query and ts.rate=1";
        $updaterate="update _cplshipmentlines set rate=$rate from _cplshipmentlines ts join _cplshipmentmaster tr on
        ts.shipment_no=tr.shipment_no 
        where tr.id=$query ";
        $updatetotals="IF OBJECT_ID('tempdb..#totals') IS NOT NULL DROP TABLE #totals
        select invoicelineid, code,stkcode,  round(sum(isnull(Cost,0))+((amount)*(qty)*max(rate)),2) as totcost 
        into #totals
        from _cplshipmentlines ts
        join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
        where tr.id=$query
        group by code,stkcode, invoicelineid,amount,qty
        
        update _cplshipmentlines set totals=tts.totcost from _cplshipmentlines ts  
        join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
        join #totals tts on ts.invoicelineid=tts.invoicelineid
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
        $duty="IF OBJECT_ID('tempdb..#duty') IS NOT NULL DROP TABLE #duty
        select distinct invoicelineid, cs.code, (max(amount*rate*qty)+
        sum(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0)else 0 end)+
        sum(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0) else 0 end))*
        max(DutyPercent) as duty
        into #duty
        from stkitem st
        join _CplHScode ce on st.cComponent=ce.HSCode join _cplshipmentlines cs
        on st.stocklink=cs.stkcode join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no
        join _cplcostmaster cr on cs.costcode=cr.id
        where tm.id=cast($query as int)
        group by invoicelineid,cs.code 
        
        update _cplshipmentlines set cost=duty from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #duty dt on cs.invoicelineid=dt.invoicelineid
        join _cplcostmaster cr on cs.costcode=cr.id
        where tm.id=cast($query as int) and cr.cost='Duty' and calc_duty=1";
        sqlsrv_query($conn, $duty) or die(print_r( sqlsrv_errors(), true));

        //excise duty
        $exciseduty="IF OBJECT_ID('tempdb..#exciseduty') IS NOT NULL DROP TABLE #exciseduty
        select distinct invoicelineid, cs.code, (max(amount*rate*qty)+
        sum(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0) else 0 end)+
        sum(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0) else 0 end))*
        0.20 as exciseduty,
        (max(amount*rate)+
        sum(case when cr.cost='InsuranceAmount' then isnull(cs.cost,0) else 0 end)+
        sum(case when cr.cost='OtherChargesOnAWBOrSea' then isnull(cs.cost,0) else 0 end)) as customs
        into #exciseduty
        from stkitem st
        join _CplHScode ce on st.cComponent=ce.HSCode join _cplshipmentlines cs
        on st.stocklink=cs.stkcode join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no
        join _cplcostmaster cr on cs.costcode=cr.id
        where tm.id=cast($query as int) 
        group by invoicelineid,cs.code
        
        --update excise duty
        update _cplshipmentlines set cost=exciseduty from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #exciseduty dt on cs.invoicelineid=dt.invoicelineid
        join _cplcostmaster cr on cs.costcode=cr.id
        where tm.id=cast($query as int) and cr.cost='EXCISE_DUTY'
        
        --update customs value
        update _cplshipmentlines set customs_value=customs from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #exciseduty dt on cs.invoicelineid=dt.invoicelineid
        where tm.id=cast($query as int)";
        sqlsrv_query($conn, $exciseduty) or die(print_r( sqlsrv_errors(), true));

        $vat="IF OBJECT_ID('tempdb..#vat') IS NOT NULL DROP TABLE #vat
        select distinct invoicelineid, cs.code,(max(case when costcode=3 then cost else 0 end)+
        max(case when costcode=1022 then cost else 0 end)+max(customs_value))
        *(max(ce.vat)/100) as vat
        into #vat
        from stkitem st
        join _cplshipmentlines cs
        on st.stocklink=cs.stkcode join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join _cplscheme ce on cs.scheme=ce.Scheme
        where tm.id=cast($query as int)
        group by invoicelineid,cs.code
        
        update _cplshipmentlines set vatonduty=b.vat from _cplshipmentlines a join
        #vat b on a.invoicelineid=b.invoicelineid";
        
        sqlsrv_query($conn, $vat) or die(print_r( sqlsrv_errors(), true));

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