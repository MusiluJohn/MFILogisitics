USE [Skanem]
GO

/****** Object:  View [dbo].[competencies]    Script Date: 2023/03/24 23:47:18 ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO




ALTER view  [dbo].[competencies]
as
--Insert statements go here
select [EmployeeName], max([PerformanceTemplate]) as [PerformanceTemplate] ,max([ReviewName]) as [ReviewName],
max([ReviewStartDate]) as [ReviewStartDate],max([ReviewEndDate]) as [ReviewEndDate],
[CompetencyArea] as [CompetencyArea],[CompetencyDescription] as [CompetencyDescription]
,max([FinalcompetencyRating]) as [FinalcompetencyRating], 
max(case when row_num=2 then Comment else '' end) as 'HODs Comments', 
max(case when row_num=2 then [ActualResultComment] else '' end) as 'HODs Actual Result Comments',
max(case when row_num=2 then RatingTypeRatingID  else FinalcompetencyRating end) as 'HODs Rating',
max(case when row_num=2 then '' else Comment end) as 'Employee Comments',
max(case when row_num=2 then '' else [ActualResultComment] end) as 'Employee Actual Result Comments',
max(case when row_num=2 then FinalcompetencyRating else RatingTypeRatingID end) as 'Employees Rating'
from (
select ROW_NUMBER() OVER (PARTITION BY CompetencyDescription,employeename ORDER BY EmployeeName,performanceTemplate) row_num,*
from (
select e.DisplayName as EmployeeName,(i.TemplateName) as PerformanceTemplate,
(b.ReviewName) as ReviewName 
,(b.CycleStartDate) as ReviewStartDate, (b.CycleEndDate) as ReviewEndDate, f.CompetencyAreaDescription as CompetencyArea,
f.CompetencyDescription as CompetencyDescription ,(a.ActualRating) as FinalcompetencyRating, g.[RatingTypeRatingID] ,(g.Comment) as Comment,(g.ActualResultComment) as ActualResultComment 
--into #temp
from Performance.ContractReviewRatingRel a 
join Performance.ContractReview b on a.ContractReviewID=b.ContractReviewID
join Performance.ContractHeader c on b.ContractHeaderID=c.ContractHeaderID 
join Employee.Employee d on c.EmployeeID=d.EmployeeID
join Entity.GenEntity e on e.GenEntityID=d.GenEntityID 
join performance.ContractCompetencyRel f on a.ContractCompetencyRelID=f.ContractCompetencyRelID
join Performance.ContractReviewReviewerRatingRel g on f.ContractCompetencyRelID=g.ContractCompetencyRelID 
join Performance.ContractTemplateHeader i on i.ContractTemplateHeaderID=c.ContractTemplateHeaderID
where  RatingType in (3) and a.ContractCompetencyRelID is not null 

group by DisplayName,CompetencyAreaDescription,CompetencyDescription,TemplateName,b.ReviewName,
CycleStartDate
,CycleEndDate,ActualRating, ActualResultComment,g.Comment,g.[RatingTypeRatingID])p)b
--where EmployeeName='Charles Gitonga'
group by EmployeeName,CompetencyArea,CompetencyDescription



GO


