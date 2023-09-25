USE [CPSHOE]
GO

/****** Object:  StoredProcedure [dbo].[DATE]    Script Date: 09/09/2023 12:14:40 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO




CREATE PROCEDURE [dbo].[DATE]
	-- Add the parameters for the stored procedure here
	@batch@ as bigint
AS
BEGIN
    -- Insert statements for procedure here
	select top 1 format(cast(payment_date as date),'dd/MM/yyyy') as payment_date
	from _cplsupplierwvat a join postap b on a.invoiceid=b.AutoIdx 
	join vendor c on c.dclink=b.accountlink 
	left join currency d on d.currencylink=c.iCurrencyID
	where a.batch=@batch@
END



GO


