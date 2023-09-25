USE [MFI-DS]
GO

/****** Object:  Table [dbo].[_cplshipmentlines]    Script Date: 03/11/2023 2:29:48 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

SET ANSI_PADDING ON
GO

CREATE TABLE [dbo].[_cplshipmentlines](
	[id] [int] IDENTITY(1,1) NOT NULL,
	[shipment_no] [varchar](50) NULL,
	[code] [varchar](50) NULL,
	[description] [varchar](50) NULL,
	[qty] [float] NULL,
	[amount] [float] NULL,
	[volume] [float] NULL,
	[unit_weight] [float] NULL,
	[Cost] [float] NULL,
	[stkcode] [int] NULL,
	[scheme] [varchar](50) NULL,
	[costcode] [int] NULL,
	[active] [bit] NULL,
	[invoicelineid] [int] NULL,
	[rate] [float] NULL,
	[updated] [bit] NULL,
	[Totals] [float] NULL,
	[po_no] [varchar](50) NULL,
	[tot_amount] [float] NULL,
	[weight] [float] NULL,
	[clientid] [int] NULL,
	[tot_amount_kes] [float] NULL,
	[unit_amount_kes] [float] NULL,
	[factor] [float] NULL,
	[freight_for] [float] NULL,
	[customs_value] [float] NULL,
	[vatonduty] [float] NULL,
	[vatonhandling] [float] NULL,
	[correctfactor] [float] NULL,
	[grv_no] [varchar](50) NULL,
	[grv_qty] [float] NULL,
	[Calc_Duty] [bit] NULL,
	[rec_qty] [float] NULL,
	[duty_modified_date] [datetime] NULL,
	[excise_duty_modified_date] [datetime] NULL,
	[customs_modified_date] [datetime] NULL,
	[vat_modified_date] [datetime] NULL
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO


