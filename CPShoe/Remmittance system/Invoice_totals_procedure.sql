USE [CPSHOE]
GO

/****** Object:  StoredProcedure [dbo].[Invoice_Totals]    Script Date: 09/09/2023 12:14:59 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO










CREATE PROCEDURE [dbo].[Invoice_Totals]
	-- Add the parameters for the stored procedure here
	@batch@ as bigint
AS
BEGIN
    -- Insert statements for procedure here
	select  (sum(invoiceAmt)) as gross ,
	case when max(c.icurrencyid)>0 then sum(withholding_amt)
	else round(sum(withholding_amt),0) end as vat_w,
	(sum(income_withheld_amt)) as inc_withheld,
	case when len(round(round(sum(amount_payable),2)-Round(floor(sum(amount_payable)),2),2))=4 then
	case when right(round(round(sum(amount_payable),2)-Round(floor(sum(amount_payable)),2),2),1)<=5 then
	round(sum(amount_payable),2) else round(sum(amount_payable),1) end else sum(amount_payable) end as amt_paid 
	from _cplsupplierwvat a join postap b on a.invoiceid=b.AutoIdx 
	join vendor c on c.dclink=b.accountlink 
	where invoiceno in 
	(	select top 10 (invoiceno) 
	from _cplsupplierwvat a join postap b on a.invoiceid=b.AutoIdx 
	join vendor c on c.dclink=b.accountlink 
	left join currency d on d.currencylink=c.iCurrencyID
	where  batch=@batch@
	)
END








GO


