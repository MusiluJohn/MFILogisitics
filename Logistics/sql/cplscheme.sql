USE [MFI-DS]
GO

/****** Object:  Table [dbo].[_cplScheme]    Script Date: 03/11/2023 2:26:53 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

SET ANSI_PADDING ON
GO

CREATE TABLE [dbo].[_cplScheme](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[Scheme] [varchar](50) NULL,
	[Cost_Code] [int] NULL,
	[calcbase] [int] NULL,
	[rate] [float] NULL,
	[vat] [float] NULL
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO


