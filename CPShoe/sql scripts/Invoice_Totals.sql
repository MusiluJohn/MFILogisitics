USE [CPSHOE]
GO

/****** Object:  StoredProcedure [dbo].[Invoice_Totals]    Script Date: 2023/08/27 17:37:16 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO




ALTER PROCEDURE [dbo].[Invoice_Totals]
	-- Add the parameters for the stored procedure here
	@account@ as varchar(70),
	@fromdate@ as date,
	@todate@ as date
AS
BEGIN
    -- Insert statements for procedure here
	select format(sum(invoiceAmt),'#,###.##') as gross ,
	case when max(c.icurrencyid)>0 then format(sum(withholding_amt), '#,###.##')
	else round(sum(withholding_amt),0) end as vat_w,
	(sum(income_withheld_amt)) as inc_withheld,
	case when len(round(round(sum(amount_payable),2)-Round(floor(sum(amount_payable)),2),2))=4 then
	case when right(round(round(sum(amount_payable),2)-Round(floor(sum(amount_payable)),2),2),1)<=5 then
	round(sum(amount_payable),2) else round(sum(amount_payable),1) end else sum(amount_payable) end as amt_paid 
	from _cplsupplierwvat a join postap b on a.invoiceid=b.AutoIdx 
	join vendor c on c.dclink=b.accountlink 
	where c.account=@account@
	and format((b.TxDate),'yyyy-MM-dd') between format(@fromdate@,'yyyy-MM-dd') and format(@todate@,'yyyy-MM-dd') and a.status='Yes'
END


GO


