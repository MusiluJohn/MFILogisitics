USE [MFI-DS]
GO

/****** Object:  Table [dbo].[_cplShipment]    Script Date: 03/11/2023 2:27:31 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

SET ANSI_PADDING ON
GO

CREATE TABLE [dbo].[_cplShipment](
	[idShipment] [int] IDENTITY(1,1) NOT NULL,
	[cShipmentNo] [varchar](50) NOT NULL,
	[cMode] [varchar](20) NULL,
	[dETAPort] [datetime] NULL,
	[dETAOffice] [datetime] NULL,
	[fGrossWtKg] [float] NULL,
	[fVolumeCbm] [float] NULL,
	[iPackages] [int] NULL,
	[cCustomEntryNo] [varchar](80) NULL,
	[dCustomEntryDate] [datetime] NULL,
	[dCustomPassDate] [datetime] NULL,
	[cIDFNo] [varchar](80) NULL,
	[i20ft] [int] NULL,
	[i40ft] [int] NULL,
	[ilcl] [int] NULL,
	[ccagent] [varchar](20) NULL,
	[cstatus] [varchar](20) NULL,
	[dactualport] [datetime] NULL,
	[dshipmentdate] [datetime] NULL,
	[ccocno] [varchar](50) NULL,
	[detdorigin] [datetime] NULL,
	[cpaymentstatus] [varchar](10) NULL,
	[fotherchgsHOME] [float] NULL,
	[fexchrateUSD] [float] NULL,
	[fexchrateEUR] [float] NULL,
	[finsurancechgsHOME] [float] NULL,
	[fportchgsHOME] [float] NULL,
	[fagencyfeesHOME] [float] NULL,
	[fKEBSfeesHOME] [float] NULL,
	[ffreightchgsUSD] [float] NULL,
	[ffreightchgsEUR] [float] NULL,
	[cawbblno] [varchar](50) NULL,
	[MIPONo] [varchar](50) NULL,
	[MainSuppPINo] [varchar](50) NULL,
	[MainSuppPIDate] [date] NULL,
	[MainSuppCINo] [varchar](50) NULL,
	[MainSuppCIDate] [date] NULL,
	[MainSuppPickupNo] [varchar](50) NULL
) ON [PRIMARY]

GO

SET ANSI_PADDING OFF
GO


