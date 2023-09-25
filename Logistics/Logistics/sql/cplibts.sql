USE [MFI-DS]
GO

/****** Object:  Table [dbo].[_cplibts]    Script Date: 03/11/2023 2:26:27 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

SET ANSI_PADDING ON
GO

CREATE TABLE [dbo].[_cplibts](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[ibtno] [varchar](50) NULL,
	[shipment_no] [varchar](50) NULL
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO


