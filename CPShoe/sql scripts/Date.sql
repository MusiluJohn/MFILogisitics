USE [CPSHOE]
GO

/****** Object:  StoredProcedure [dbo].[DATE]    Script Date: 2023/08/27 17:36:57 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

ALTER PROCEDURE [dbo].[DATE]
	-- Add the parameters for the stored procedure here
	@account@ as varchar(70),
	@fromdate@ as date,
	@todate@ as date
AS
BEGIN
    -- Insert statements for procedure here
	select top 1 format(cast(payment_date as date),'dd/MM/yyyy') as payment_date
	from _cplsupplierwvat a join postap b on a.invoiceid=b.AutoIdx 
	join vendor c on c.dclink=b.accountlink 
	left join currency d on d.currencylink=c.iCurrencyID
	where c.account=@account@
	and format((b.TxDate),'yyyy-MM-dd') between format(@fromdate@,'yyyy-MM-dd') and format(@todate@,'yyyy-MM-dd') and status='Yes'
END
GO


