USE [CPSHOE]
GO

/****** Object:  StoredProcedure [dbo].[supplier]    Script Date: 2023/08/27 17:33:55 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO


ALTER PROCEDURE [dbo].[supplier] 
	-- Add the parameters for the stored procedure here
AS
BEGIN
	-- SET NOCOUNT ON added to prevent extra result sets from
	-- interfering with SELECT statements.
	SET NOCOUNT ON;

    -- Insert statements for procedure here
	select account,Name from vendor where DCLink in (select accountlink from PostAP) and iClassID=1
	order by Account
END
GO


