USE [Skanem]
GO

/****** Object:  Table [dbo].[CPL_SageUsers]    Script Date: 2023/03/18 09:50:53 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO

CREATE TABLE [dbo].[CPL_SageUsers](
	[UserCode] [int] IDENTITY(1,1) NOT NULL,
	[UserName] [varchar](50) NULL,
	[Password] [varchar](100) NULL,
	[FirstName] [varchar](50) NULL,
	[LastName] [varchar](50) NULL,
	[Grp] [varchar](50) NULL
) ON [PRIMARY]
GO


