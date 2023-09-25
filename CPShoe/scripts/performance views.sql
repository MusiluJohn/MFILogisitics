USE [Skanem]
GO

/****** Object:  View [dbo].[performance]    Script Date: 2023/03/24 23:35:07 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO





ALTER view  [dbo].[performance]
as

-- Insert statements for procedure here
select [Employee Name], max([Performance Template]) as [Performance Template] ,max([Review Name]) as [Review Name],
max([Review Start Date]) as [Review Start Date],max([Review End Date]) as [Review End Date],
[Key Performance Area] as [Key Performance Area],[Key Performance Indicator] as [Key Performance Indicator]
,max([Final Performance Rating]) as [Final Performance Rating], max(case when row_num=2 then Comments else '' end) as 
'HODs Comments', max(case when row_num=2 then [Actual Result Comment] else '' end) as 'HODs Actual Result Comments'
,max(case when row_num=2 then [RatingTypeRatingID] else [Final Performance Rating] end) as 'HOD Rating'
,max(case when row_num=2 then '' else Comments end) as 'Employee Comments',max(case when row_num=2 then '' 
else [Actual Result Comment] end) as 'Employee Actual Result Comments'
,max(case when row_num=2 then [Final Performance Rating] else [RatingTypeRatingID] end) as 'Employee Rating'
from (
select (e.DisplayName) as 'Employee Name',ROW_NUMBER() OVER (PARTITION BY f.KeyPerformanceIndicatorDescription ORDER BY e.DisplayName) row_num,
max(i.TemplateName) as 'Performance Template',max(b.ReviewName) as 'Review Name' 
,max(b.CycleStartDate) as 'Review Start Date', max(b.CycleEndDate) as 'Review End Date', 
f.KeyPerformanceAreaDescription as 'Key Performance Area',
f.KeyPerformanceIndicatorDescription as 'Key Performance Indicator',max (ActualRating) as 'Final Performance Rating',
max(g.[RatingTypeRatingID])as [RatingTypeRatingID], (g.Comment) as 'Comments',(g.ActualResultComment) as 'Actual Result Comment' 
from Performance.ContractReviewRatingRel a 
join [Performance].[ContractReview] b on a.ContractReviewID=b.ContractReviewID
join [Performance].[ContractHeader] c on b.ContractHeaderID=c.ContractHeaderID
join Employee.Employee d on c.EmployeeID=d.EmployeeID
join Entity.GenEntity e on e.GenEntityID=d.GenEntityID
join performance.ContractKeyPerformanceRel f on a.ContractKeyPerformanceRelID=f.ContractKeyPerformanceRelID
join Performance.ContractReviewReviewerRatingRel g on f.ContractKeyPerformanceRelID=g.ContractKeyPerformanceRelID
join Performance.ContractTemplateHeader i on i.ContractTemplateHeaderID=c.ContractTemplateHeaderID
where RatingType in (3) and 
a.ContractKeyPerformanceRelID  is not null 
group by KeyPerformanceAreaDescription,KeyPerformanceIndicatorDescription,DisplayName,g.Comment,g.ActualResultComment) p
group by [Employee Name],[Key Performance Area],[Key Performance Indicator]


GO


