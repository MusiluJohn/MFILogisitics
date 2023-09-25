USE [CPSHOE]
GO

/****** Object:  StoredProcedure [dbo].[batch_list]    Script Date: 09/09/2023 12:13:11 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO




CREATE PROCEDURE [dbo].[batch_list]
	-- Add the parameters for the stored procedure here
AS
BEGIN
    -- Insert statements for procedure here
	select distinct batch, c.name from _cplsupplierwvat a join postap b on a.invoiceid=b.autoidx
	join vendor c on c.dclink=b.AccountLink
END


GO


