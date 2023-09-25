USE [CPSHOE]
GO

/****** Object:  StoredProcedure [dbo].[Paid_Invoices]    Script Date: 2023/08/27 17:37:38 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO





ALTER PROCEDURE [dbo].[Paid_Invoices]
	-- Add the parameters for the stored procedure here
	@account@ as varchar(70),
	@fromdate@ as date,
	@todate@ as date
AS
BEGIN
    -- Insert statements for procedure here
	select top 10 invoiceno, format(cast(invoice_date as date),'dd/MM/yyy') as invoice_date,
	format(invoiceAmt,'#,###.##') as invoice_amt_excl,case when isnull(currencycode,0)=0 
	then case when withholding_amt-floor(withholding_amt)>0 then
	floor(withholding_amt)+1 else withholding_amt end 
	else format(withholding_amt ,'#,###.##') end
	as withholding_amt,(income_withheld_amt) as income_withheld_amt,
	(case when len(round(round(amount_payable,2)-Round(floor(amount_payable),2),2))=4 then
	case when right(round(round(amount_payable,2)-Round(floor(amount_payable),2),2),1)<=5 then
	round(amount_payable,2) else round(amount_payable,1) end else amount_payable end) as amount_payable, 
	case when isnull(d.currencycode,0)=0 then 'KES' else CurrencyCode end curr
	from _cplsupplierwvat a join postap b on a.invoiceid=b.AutoIdx 
	join vendor c on c.dclink=b.accountlink 
	left join currency d on d.currencylink=c.iCurrencyID
	where c.account=@account@
	and format((b.TxDate),'yyyy-MM-dd') between format(@fromdate@,'yyyy-MM-dd') and format(@todate@,'yyyy-MM-dd') and status='Yes'
END



GO


