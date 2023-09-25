USE [CPSHOE]
GO

/****** Object:  StoredProcedure [dbo].[supplier_invoices]    Script Date: 2023/08/27 17:35:57 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO




ALTER PROCEDURE [dbo].[supplier_invoices]
	-- Add the parameters for the stored procedure here
	@account@ as varchar(70),
	@fromdate@ as date,
	@todate@ as date
AS
BEGIN
    -- Insert statements for procedure here
	select a.autoidx as auto_id, max(b.Name) as Supplier, max(b.Registration) as Pin, '' as PI_no,
	(Reference) as 'InvNo', format(max(TxDate),'yyyy-MM-dd') as 'InvDate', 16 as vat, round(max(a.Outstanding-(a.outstanding/1.16)),2) as Inv_Vat,
	round(max(a.outstanding),2) as Inc_amt,  
	case when isnull(max(invoiceno),'')='' and max(tax_amount)>0 then  
	case when max(isnull(b.iCurrencyID,0))=0 then round(max(a.Outstanding/1.16) * 0.02,0) 
	else round(max(a.Outstanding/1.16) * 0.02,2) end
	when max(s.withholding_amt)>0 
	then 
	case when max(isnull(b.iCurrencyID,0))=0 then round(max(s.withholding_amt),0)
	else max(s.withholding_amt) end
	 else 0 end as vat_w,
	round(max(a.Outstanding/1.16),2) as Inv_gr_tot, 
	round((case when max(Tax_Amount)=0 then sum(a.Outstanding/1.16) else 0 end),2) as nva,
	(case when isnull(max(invoiceno),'')=''  then round(max(a.Outstanding),2)-round(max(a.Tax_Amount),2) else max(amount_payable) end) as amt_payable, 
	case when isnull(max(invoiceno),'')=''  then format(getdate(),'yyyy-MM-dd') else (max(s.payment_date)) end as paymentdate, max(isnull(c.Description,'KES')) as Curr,
	max(b.dclink) as Supplier_id,
	case when isnull(max(invoiceno),'')=''  then 0 else max(s.income_withheld_amt) end as income_withheld,
	case when isnull(max(invoiceno),'')=''  then '' else max(s.status) end as 'Status'
	from postap a join vendor b
	on a.AccountLink=b.DCLink
	left join currency c on c.currencylink=b.iCurrencyID
	left join TrCodes t on t.idtrcodes=a.TrCodeID
	left join _cplsupplierwvat s on s.invoiceid=a.AutoIdx
	where a.Id in ('APTx') and code in ('JC','IN') and iModule=6
	and  b.Account=@account@ and format((a.txdate),'yyyy-MM-dd') between format(@fromdate@,'yyyy-MM-dd') and format(@todate@,'yyyy-MM-dd')
	group by a.autoidx,Reference
	union
	select a.autoindex as auto_id, max(b.Name) as Supplier, max(b.Registration) as Pin, '' as PI_no,
	(invnumber) as 'InvNo', format(max(invdate),'yyyy-MM-dd') as 'InvDate', 16 as vat, round(max(a.InvTotTax),2) as Inv_Vat,
	round(max(a.InvTotIncl),2) as Inc_amt, case when isnull(max(invoiceno),'')='' then round(max(invtotincl) * 0.02,2) when max(s.withholding_amt)=0 then max(s.withholding_amt) else 0 end as vat_w,round(max(invtotexcl),2) as Inv_gr_tot, 
	round((case when max(fQtyLastProcessLineTaxAmount)=0 then sum(d.fQtyLastProcessLineTotExcl) else 0 end),2) as nva,
	(case when isnull(max(invoiceno),'')='' then round(max(a.InvTotIncl),2)-round(max(a.InvTotTax),2) else max(amount_payable) end) as amt_payable,
	case when isnull(max(invoiceno),'')='' then format(getdate(),'yyyy-MM-dd') else (max(payment_date)) end as paymentdate, max(c.Description) as Curr,
	max(b.dclink) as Supplier_id,
	case when isnull(max(invoiceno),'')=''  then 0 else max(s.income_withheld_amt) end as income_withheld,
	case when isnull(max(invoiceno),'')=''  then '' else max(s.status) end as 'Status'
	from invnum a join vendor b
	on a.AccountID=b.DCLink
	join currency c on c.currencylink=b.iCurrencyID
	join _btblinvoicelines d on d.iInvoiceID=a.AutoIndex
	left join _cplsupplierwvat s on s.invoiceid=a.AutoIndex
	where doctype=5 and docstate=4 and DocFlag=2
	and  Account=@account@ and format((a.InvDate),'yyyy-MM-dd') between format(@fromdate@,'yyyy-MM-dd') and format(@todate@,'yyyy-MM-dd')
	group by a.AutoIndex,invnumber
END


GO


