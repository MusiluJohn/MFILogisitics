USE [CPSHOE]
GO

/****** Object:  StoredProcedure [dbo].[crn_report_count]    Script Date: 09/09/2023 12:14:04 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO


	CREATE PROCEDURE [dbo].[crn_report_count]
	-- Add the parameters for the stored procedure here
	@batch@ as BIGINT
AS
BEGIN
    -- Insert statements for procedure here

	select count(autoidx) as cnt	from postap a join vendor b
	on a.AccountLink=b.DCLink

	left join TaxRate tr on tr.idTaxRate=a.TaxTypeID

	left join currency c on c.currencylink=b.iCurrencyID

	join (SELECT autoidx as creditnoteid, 
	cast((replace(replace(left(Split.a.value('.', 'NVARCHAR(MAX)'), charindex(';',Split.a.value('.', 'NVARCHAR(MAX)'))),'I=',''),';','')) as int) as allocated_to
	FROM
	(
		SELECT autoidx, CAST('<X>'+REPLACE(cast(callocs as varchar(max)), '|', '</X><X>')+'</X>' AS XML) AS String from postap 
	) AS A
	CROSS APPLY String.nodes('/X') AS Split(a)
	) d
	on a.autoidx=d.creditnoteid

	join(
	select a.autoidx as auto_id, max(b.Name) as Supplier, max(b.Registration) as Pin, '' as PI_no,
	(Reference) as 'InvNo', format(max(TxDate),'yyyy-MM-dd') as 'InvDate', 16 as vat, round(max(a.Tax_Amount),2) as Inv_Vat,
	round(max(a.Credit),2) as Inc_amt,  
	case when isnull(max(invoiceno),'')='' and max(tax_amount)>0 then  
	case when max(isnull(b.iCurrencyID,0))=0 then round(max(a.Credit-Tax_Amount) * 0.02,0) 
	else round(max(a.Credit-Tax_Amount) * 0.02,2) end
	when max(s.withholding_amt)>0 
	then 
	case when max(isnull(b.iCurrencyID,0))=0 then round(max(s.withholding_amt),0)
	else max(s.withholding_amt) end
	 else 0 end as vat_w,
	round(max(Credit-Tax_Amount),2) as Inv_gr_tot, 
	round((case when max(Tax_Amount)=0 then sum(Debit-Tax_Amount) else 0 end),2) as nva,
	(case when isnull(max(invoiceno),'')=''  then round(max(a.Credit),2)-round(max(a.Tax_Amount),2) else max(amount_payable) end) as amt_payable, 
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
	and  s.batch=@batch@
	group by a.autoidx,Reference
	)e
	on e.auto_id=d.allocated_to
	END




GO


