USE [CPSHOE]
GO

/****** Object:  StoredProcedure [dbo].[Paid_Invoices]    Script Date: 09/09/2023 12:15:25 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO











CREATE PROCEDURE [dbo].[Paid_Invoices]
	-- Add the parameters for the stored procedure here
	@batch@ as bigint
AS
BEGIN
    -- Insert statements for procedure here
	select top 10 (invoiceno) as invoiceno, (invoice_date ) as invoice_date,
	(invoiceAmt) as invoice_amt_excl,case when isnull(currencylink,0)=0 
	then case when withholding_amt-floor(withholding_amt)>0 then
	floor(withholding_amt)+1 else withholding_amt end 
	else (withholding_amt ) end
	as withholding_amt,(income_withheld_amt) as income_withheld_amt,
	a.amount_payable as amount_payable, 
	case when isnull(d.currencylink,0)=0 then 'KES' else CurrencyCode end curr
	from _cplsupplierwvat a join postap b on a.invoiceid=b.AutoIdx 
	join vendor c on c.dclink=b.accountlink 
	left join currency d on d.currencylink=c.iCurrencyID
	where batch=@batch@
END




GO


