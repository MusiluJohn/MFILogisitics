USE [CPSHOE]
GO

/****** Object:  Table [dbo].[_cplsupplierwvat]    Script Date: 09/09/2023 12:11:00 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

SET ANSI_PADDING ON
GO

CREATE TABLE [dbo].[_cplsupplierwvat](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[invoiceid] [int] NULL,
	[invoiceno] [varchar](50) NULL,
	[invoiceAmt] [float] NULL,
	[withholding_rate] [float] NULL,
	[withholding_amt] [float] NULL,
	[income_withheld_rate] [float] NULL,
	[income_withheld_amt] [float] NULL,
	[amount_payable] [float] NULL,
	[payment_date] [varchar](60) NULL,
	[type] [varchar](10) NULL,
	[status] [varchar](15) NULL,
	[pin_no] [varchar](60) NULL,
	[invoice_date] [varchar](60) NULL,
	[invoice_amt_excl] [float] NULL,
	[batch] [bigint] NULL
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO


