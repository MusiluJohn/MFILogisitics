<?php
include("config.php");
$conn = sqlsrv_connect( $servername, $connectioninfo);
$query = $_GET['SH'];
$results = array('error' => false, 'data' => '');
         $sql2="--get the sumation
         IF OBJECT_ID('tempdb..#tmpship') IS NOT NULL DROP TABLE #tmpship
         select costcode, (sum(volume)) as tot, sum(weight) as totweight,sum(amount) as amt 
         into #tmpship
         from _cplshipmentlines join _cplshipmentmaster on _cplshipmentlines.shipment_no=
         _cplshipmentmaster.shipment_no
         where 
         _cplshipmentmaster.id=$query
         group by costcode";

         //duty
         $duty="IF OBJECT_ID('tempdb..#duty') IS NOT NULL DROP TABLE #duty;
         select distinct invoicelineid, cs.code, max(cme.scheme) as scheme,
		 case when max(cmode) in ('AIR','Courier') then (max(amount*cs.rate*qty)+
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
         on ce.Cost_Code=cr.id where cost='duty'"; 

        //Calculate duty
        $updateduty="--update duty
        update _cplshipmentlines set cost=((cast(duty as float)/100)*cast(customs as float)) from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #duty dt on cs.invoicelineid=dt.invoicelineid
        join _cplcostmaster cr on cs.costcode=cr.id join stkitem st on cs.stkcode=st.StockLink
        where tm.id=cast($query as int) and cr.cost='Duty' and calc_duty=1  and st.ServiceItem<>1";

        $updaterailway="--update railway levy
        update _cplshipmentlines set cost=(0.015*cast(customs as float)) from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #duty dt on cs.invoicelineid=dt.invoicelineid
        join _cplcostmaster cr on cs.costcode=cr.id join stkitem st on cs.stkcode=st.StockLink
        where tm.id=cast($query as int) and cr.cost='RailwayLevy'   and st.ServiceItem<>1";

        $updategok="--update gok
        update _cplshipmentlines set cost=(0.025*cast(customs as float)) from _cplshipmentlines cs join _cplshipmentmaster tm
        on cs.shipment_no=tm.shipment_no join #duty dt on cs.invoicelineid=dt.invoicelineid
        join _cplcostmaster cr on cs.costcode=cr.id join stkitem st on cs.stkcode=st.StockLink
        where tm.id=cast($query as int) and cr.cost='GOK'   and st.ServiceItem<>1";

        $sql3="/*update _cplshipmentlines set cost=  (case when cb.calcbase='volume' then volume/nullif(tot,0)*ce.rate when cb.calcbase='weight' then weight/nullif(totweight,0)*ce.rate when cb.calcbase='FOB' then 
         ((amount*qty)/nullif(amt,0) * ce.rate) when cb.calcbase='N/A' then ts.cost else 0 end ) from	_cplshipmentlines ts join _cplScheme ce on ts.costcode=ce.Cost_Code and ce.Scheme=ts.scheme 
         join _cplshipmentmaster ttr on ts.shipment_no=ttr.shipment_no
         join #tmpship on ts.costcode=#tmpship.costcode
         join _cplcalcbase cb on ce.calcbase=cb.id
         join _cplcostmaster cr on ts.costcode=cr.id
         where ttr.id=cast($query as int) and active='True' and cr.cost not in ('GOK','RailwayLevy','Duty','EXCISE_DUTY') and isnull(updated,0)=0 */";

         $updatetotals="IF OBJECT_ID('tempdb..#totals') IS NOT NULL DROP TABLE #totals
          select code,stkcode, round(sum(Cost)+(max(amount)*max(qty)*max(rate)),2) as totcost 
          into #totals
          from _cplshipmentlines ts
          join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
          where tr.id=cast($query as int) and costcode in (2,3,4,5,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,30,31,32,33)
          group by code,stkcode

         update _cplshipmentlines set totals=tts.totcost from _cplshipmentlines ts  
         join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
         join #totals tts on ts.stkcode=tts.stkcode
         where tr.id=cast($query as int)";
         $actualfactor="IF OBJECT_ID('tempdb..#actualfactor') IS NOT NULL DROP TABLE #actualfactor
         select distinct invoicelineid,code,stkcode,round(((sum(ts.Cost)+(max(amount)*max(qty)*max(rate)))/(max(nullif(amount,0))*max(qty)*max(rate))),2) as factor
         into #actualfactor
         from _cplshipmentlines ts
         join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
         join _cplcostmaster cr on ts.costcode=cr.id
         where tr.id=cast($query as int)
         group by invoicelineid,code,stkcode
         
         update _cplshipmentlines set correctfactor=b.factor from _cplshipmentlines a join
         #actualfactor b on a.invoicelineid=b.invoicelineid";
         
         //Calculate Vat on duty
         $vatonduty="IF OBJECT_ID('tempdb..#vatonduty') IS NOT NULL DROP TABLE #vatonduty;
          select distinct invoicelineid, cs.code, ((max(case when (cr.cost)='duty' then isnull(cs.cost,0) end) +
          max(cs.customs_value))) as vat,0.00 as exciseduty,0.00 as dutyperc
          into #vatonduty
          from stkitem st
          join _cplshipmentlines cs
          on st.stocklink=cs.stkcode join _cplshipmentmaster tm on cs.shipment_no=tm.shipment_no 
          join _cplcostmaster cr on cs.costcode=cr.id
          join _cplScheme cme on cs.scheme=cme.Scheme
          join _cplshipment ct on cs.shipment_no=ct.cShipmentNo
          where tm.id=cast($query as int) and st.ServiceItem<>1
          group by invoicelineid,cs.code

          update #vatonduty set exciseduty= isnull(c.Excisepercent,0)  from #vatonduty ey join Stkitem ce on ey.code=ce.Code
          join _etblEUCommodity b on b.IDEUCommodity=ce.iEUCommodityID
          join _CplHScode c on c.HSCode=b.cEUCommodityCode
            
          update #vatonduty set dutyperc=rate from #vatonduty ey join _cplScheme ce on ey.scheme=ce.Scheme where ce.Cost_Code=3

          update _cplshipmentlines set cost=0.16 * (vat + (vat * exciseduty)) from _cplshipmentlines cs join _cplshipmentmaster tm
          on cs.shipment_no=tm.shipment_no join #vatonduty dt on cs.invoicelineid=dt.invoicelineid
          join _cplcostmaster cr on cs.costcode=cr.id join stkitem st on cs.stkcode=st.StockLink
          where tm.id=cast($query as int) and cr.cost='VATonDuty' and calc_duty=1  and st.ServiceItem<>1";

          //Calculate insurance

            $qtysins="--get the sumation
            IF OBJECT_ID('tempdb..#tmpship') IS NOT NULL DROP TABLE #tmpship
            select invoicelineid,(_cplshipmentlines.code),max(costcode) as cc , (amount) as fob,max(qty) as qt, (sum(volume)) as tot, sum(unit_weight*qty) as totweight,(sum(amount*qty))as amt, (case when  (sum(volume)*167)>sum(unit_weight*qty) then (sum(volume)*167) else sum(unit_weight*qty) end) as highesttot 
            into #tmpship
            from _cplshipmentlines join _cplshipmentmaster on _cplshipmentlines.shipment_no=
            _cplshipmentmaster.shipment_no join _cplcostmaster cr on _cplshipmentlines.costcode=cr.id
            join StkItem st on _cplshipmentlines.stkcode=st.StockLink
            where 
            _cplshipmentmaster.id=$query and cr.cost='InsuranceAmount' and ServiceItem<>1
            group by invoicelineid,_cplshipmentlines.code,costcode,amount";
            $amtsins="--get amts
            IF OBJECT_ID('tempdb..#tmpamts') IS NOT NULL DROP TABLE #tmpamts
            select distinct ts.invoicelineid, ts.code, cr.cost,ts.shipment_no,ts.qty,amount, (amount*qty) as tot,
            (case when max(cb.calcbase) in ('Weight') then (case when max(volume)*167>max(weight) then max(volume)*167 else max(weight) end)
            /nullif(sum(highesttot),0)
            when
            max(cb.calcbase) in ('Volume') then (case when max(volume)*167>max(weight) then max(volume)*167 else max(weight) end)
            /nullif(sum(highesttot),0)
            else 0 end) as vol_weigh_amts,
            case when max(cb.calcbase) =('FOB') then (((amount*qty))/(select nullif(sum(amt),0) from #tmpship))  else 0 end as fobamt, case when max(cb.calcbase) in ('#NA') then max(ts.cost) end as import
            into #tmpamts
            from _cplshipmentlines ts join _cplScheme ce on ts.costcode=ce.Cost_Code and ce.Scheme=ts.scheme  join _cplshipmentmaster ttr on ts.shipment_no=ttr.shipment_no
            join #tmpship on ts.costcode=#tmpship.cc join _cplcalcbase cb on ce.calcbase=cb.id
            join _cplcostmaster cr on ts.costcode=cr.id join stkitem st on ts.stkcode=st.StockLink
            where ttr.id=$query and cr.cost='InsuranceAmount' and serviceitem<>1
            group by ts.invoicelineid,cr.cost,ts.shipment_no,ts.qty,ts.code,amount
            order by ts.code";
            $ratesins="--get rates
            IF OBJECT_ID('tempdb..#rates') IS NOT NULL DROP TABLE #rates
            select invoicelineid,code,cost,max(b.finsurancechgsHOME)*case when (cost)='InsuranceAmount' then nullif(sum(vol_weigh_amts+fobamt),0) else nullif(0,0) end as amt
            into #rates
            from #tmpamts a join _cplShipment b on a.shipment_no=b.cShipmentNo
            group by invoicelineid,code,cost";
            $updatesins="--update _cplshipmentlines
            update _cplshipmentlines set cost= amt,duty_modified_date=getdate()
            from _cplshipmentlines ts join #rates ty on ts.invoicelineid=ty.invoicelineid
            join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
            join _cplcostmaster cr on ts.costcode=cr.id join stkitem st on ts.stkcode=st.StockLink
            where tr.id=cast($query as int)  and ts.active='True' and cr.cost='InsuranceAmount' and st.serviceitem<>1";

            //calculate agency fees

            $qtysagency="--get the sumation
            IF OBJECT_ID('tempdb..#tmpship') IS NOT NULL DROP TABLE #tmpship
            select invoicelineid,(_cplshipmentlines.code),max(costcode) as cc , (amount) as fob,max(qty) as qt, (sum(volume)*167) as tot, sum(unit_weight*qty) as totweight,(sum(amount*qty))as amt,  (case when  (sum(volume)*167)>sum(unit_weight*qty) then (sum(volume)*167) else sum(unit_weight*qty) end) as highesttot 
            into #tmpship
            from _cplshipmentlines join _cplshipmentmaster on _cplshipmentlines.shipment_no=
            _cplshipmentmaster.shipment_no join _cplcostmaster cr on _cplshipmentlines.costcode=cr.id
            join StkItem st on _cplshipmentlines.stkcode=st.StockLink
            where 
            _cplshipmentmaster.id=$query and cr.cost='AgencyFee' and ServiceItem<>1
            group by invoicelineid,_cplshipmentlines.code,costcode,amount";
            $amtsagency="--get amts
            IF OBJECT_ID('tempdb..#tmpamts') IS NOT NULL DROP TABLE #tmpamts
            select distinct ts.invoicelineid, ts.code, cr.cost,ts.shipment_no,ts.qty,amount, (amount*qty) as tot,
            (case when max(cb.calcbase) in ('Weight') then (case when max(volume)*167>max(weight) then max(volume)*167 else max(weight) end)
            /nullif(sum(highesttot),0)
            when
            max(cb.calcbase) in ('Volume') then (case when max(volume)*167>max(weight) then max(volume)*167 else max(weight) end)
            /nullif(sum(highesttot),0)
            else 0 end) as vol_weigh_amts,
            case when max(cb.calcbase) =('FOB') then (((amount*qty))/(select nullif(sum(amt),0) from #tmpship))  else 0 end as fobamt, case when max(cb.calcbase) in ('#NA') then max(ts.cost) end as import
            into #tmpamts
            from _cplshipmentlines ts join _cplScheme ce on ts.costcode=ce.Cost_Code and ce.Scheme=ts.scheme  join _cplshipmentmaster ttr on ts.shipment_no=ttr.shipment_no
            join #tmpship on ts.costcode=#tmpship.cc join _cplcalcbase cb on ce.calcbase=cb.id
            join _cplcostmaster cr on ts.costcode=cr.id join stkitem st on ts.stkcode=st.StockLink
            where ttr.id=$query and cr.cost='AgencyFee' and serviceitem<>1
            group by ts.invoicelineid,cr.cost,ts.shipment_no,ts.qty,ts.code,amount
            order by ts.code";
            $ratesagency="--get rates
            IF OBJECT_ID('tempdb..#rates') IS NOT NULL DROP TABLE #rates
            select invoicelineid,code,cost,max(fagencyfeesHOME)*case when (cost)='AgencyFee' then nullif(sum(vol_weigh_amts+fobamt),0) else nullif(0,0) end as amt
            into #rates
            from #tmpamts a join _cplShipment b on a.shipment_no=b.cShipmentNo
            group by invoicelineid,code,cost";
            $updatesagency="--update _cplshipmentlines
            update _cplshipmentlines set cost= amt
            from _cplshipmentlines ts join #rates ty on ts.invoicelineid=ty.invoicelineid
            join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
            join _cplcostmaster cr on ts.costcode=cr.id join stkitem st on ts.stkcode=st.StockLink
            where tr.id=cast($query as int)  and ts.active='True' and cr.cost='AgencyFee' and st.serviceitem<>1";

            //Calculate kes
            $updatetotals="IF OBJECT_ID('tempdb..#totals') IS NOT NULL DROP TABLE #totals
            select distinct code,stkcode,round(sum(isnull(Cost,0))+(max(amount)*max(qty)*(case when max(foreigncurrencyid)=1 then isnull(max(fexchrateusd),0) else isnull(max(fexchrateeur),0) end)),2) as totcost 
            into #totals
            from _cplshipmentlines ts
            join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
            join _cplshipment c on tr.shipment_no=c.cShipmentNo
            join invnum d on d.ordernum=ts.po_no
            join _etblUserHistLink ek on ek.TableID=d.autoindex 
            join _rtblUserDict rt on ek.UserDictID=rt.idUserDict
            where tr.id=$query and rt.cFieldName='ucIDPOrdShipmentNo'
            group by code,stkcode
            
            update _cplshipmentlines set totals=tts.totcost from _cplshipmentlines ts  
            join _cplshipmentmaster tr on ts.shipment_no=tr.shipment_no
            join #totals tts on ts.stkcode=tts.stkcode
            where tr.id=$query

            update _cplshipmentlines set rate=(case when isnull(fexchrateusd,0)<>0 then  fexchrateusd when isnull(fexchrateeur,0)<>0 then fexchrateeur else isnull(rate,1) end) from _cplshipmentlines ts join _cplshipmentmaster tr on
            ts.shipment_no=tr.shipment_no 
            join _cplshipment c on tr.shipment_no=c.cShipmentNo
            join invnum d on d.ordernum=ts.po_no
            join _etblUserHistLink ek on ek.TableID=d.autoindex 
            join _rtblUserDict rt on ek.UserDictID=rt.idUserDict
            where tr.id=$query"; 
        
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
            0.00 as exciseduty,cast(0.00 as float) as dutyperc
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
            
            update #exciseduty set dutyperc=rate from #exciseduty ey join _cplScheme ce on ey.scheme=ce.Scheme where ce.Cost_Code=3";
          /* //Update percentage on #exciseduty table as per scheme
            $upeduty="update #exciseduty set exciseduty =isnull(ce.vat,0) from #exciseduty join _cplscheme ce on #exciseduty.scheme=ce.Scheme
            join _cplcostmaster cr 
            on ce.Cost_Code=cr.id where cost='EXCISE_DUTY'"; */
      
            $updateexcise=" --update excise duty
            update _cplshipmentlines set cost=(exciseduty)*(cast(customs as float)+(cast(customs as float)*dutyperc/100)) from _cplshipmentlines cs join _cplshipmentmaster tm
            on cs.shipment_no=tm.shipment_no join #exciseduty dt on cs.invoicelineid=dt.invoicelineid
            join _cplcostmaster cr on cs.costcode=cr.id
            where tm.id=cast($query as int) and cr.cost='EXCISE_DUTY'";
            sqlsrv_query($conn, $exciseduty) or die(print_r( sqlsrv_errors(), true));
            sqlsrv_query($conn, $updateexcise) or die(print_r( sqlsrv_errors(), true));

         sqlsrv_query($conn, $updatetotals) or die(print_r( sqlsrv_errors(), true));   
         sqlsrv_query($conn,$qtysins) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$amtsins) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$ratesins) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$updatesins) or die(print_r( sqlsrv_errors(), true));      
         sqlsrv_query($conn,$qtysagency) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$amtsagency) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$ratesagency) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$updatesagency) or die(print_r( sqlsrv_errors(), true));   
         sqlsrv_query($conn,$sql2) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$duty) or die(print_r( sqlsrv_errors(), true));
        /* sqlsrv_query($conn,$upeduty) or die(print_r( sqlsrv_errors(), true)); */
         sqlsrv_query($conn,$updateduty) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$sql3) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$updaterailway) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$updategok) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$updatetotals) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$actualfactor) or die(print_r( sqlsrv_errors(), true));
         sqlsrv_query($conn,$vatonduty) or die(print_r( sqlsrv_errors(), true));
        sqlsrv_close($conn);
?>