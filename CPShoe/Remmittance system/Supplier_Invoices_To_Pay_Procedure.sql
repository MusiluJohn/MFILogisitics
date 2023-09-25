USE [CPSHOE]
GO

/****** Object:  StoredProcedure [dbo].[supplier_invoices_to_pay]    Script Date: 09/09/2023 12:17:17 PM ******/
SET ANSI_NULLS ON
GO

SET QUOTED_IDENTIFIER ON
GO















CREATE PROCEDURE [dbo].[supplier_invoices_to_pay]
	-- Add the parameters for the stored procedure here
	@id@ as bigint
AS
BEGIN
    -- Insert statements for procedure here
		select a.autoidx as auto_id, max(b.Name) as Supplier, max(b.Registration) as Pin, '' as PI_no,
	(Reference) as 'InvNo', format(max(TxDate),'dd/MM/yyyy') as 'InvDate', 16 as vat, case when max(tax_amount)=0 then 0 else 
	case when max(b.icurrencyid)=0 then 
	round(max(a.Outstanding-(a.outstanding/1.16)),2) 
	else 
	round(max(a.fForeignOutstanding-(a.fForeignOutstanding/1.16)),2)
	end
	end as Inv_Vat,
	
	case when max(b.icurrencyid)=0 then
	round(max(a.outstanding),2) 
	else 
	round(max(a.fForeignOutstanding),2)	
	end
	as Inc_amt,  
	
	case when max(tax_amount)>0 then  
		case when max(isnull(b.iCurrencyID,0))=0 then 
		
			case when ((max(a.Outstanding/1.16) * 0.02)-floor((max(a.Outstanding/1.16) * 0.02)))>0 
			then floor(((max(a.Outstanding/1.16) * 0.02)))+1
			else
			(round(max(a.Outstanding/1.16) * 0.02,0))
			end
		else ((max(a.fForeignOutstanding)/1.16) * 0.02) 
		end
	 else 0 end as vat_w,

	case when max(tax_amount)=0 then case when max(isnull(b.iCurrencyID,0))=0 then max(a.Outstanding) else max(a.fForeignOutstanding) end else case when max(isnull(b.iCurrencyID,0))=0 then round(max(a.Outstanding/1.16),2) else round(max(a.fForeignOutstanding/1.16),2) end end as Inv_gr_tot, 
	
	(case when max(Tax_Amount)=0 then case when max(isnull(b.iCurrencyID,0))=0 then sum(a.Outstanding) else sum(a.fforeignOutstanding) end  else 0 end) as nva,

			case when max(tax_amount)<>0 then
				(case when max(isnull(b.iCurrencyID,0))=0 then max(a.Outstanding) else max(a.fforeignOutstanding) end)-
					case when max(b.icurrencyid)=0 then 
						case when ((max(a.Outstanding/1.16) * 0.02)-floor((max(a.Outstanding/1.16) * 0.02)))>0 
						then floor(((max(a.Outstanding/1.16) * 0.02)))+1
						else
						(round(max(a.Outstanding/1.16) * 0.02,2))
						end 
					else round(max(a.fForeignOutstanding/1.16) * 0.02,2) end
				else
					(case when max(isnull(b.iCurrencyID,0))=0 then max(a.Outstanding) else max(a.fforeignOutstanding) end)
				end
	  as amt_payable, 

	format(getdate(),'yyyy-MM-dd') as paymentdate, 
	max(isnull(c.Description,'KES')) as Curr,
	max(b.dclink) as Supplier_id,
	0  as income_withheld,
	''  as 'Status',
	max(tr.TaxRate) as taxrate, max(a.fExchangeRate) as exchrate,
	'' as batch  
		from postap a join vendor b
	on a.AccountLink=b.DCLink
	left join currency c on c.currencylink=b.iCurrencyID
	left join TrCodes t on t.idtrcodes=a.TrCodeID
	left join taxrate tr on tr.idtaxrate=a.taxtypeid
	where a.Id in ('APTx') and t.code in ('JC','IN') and iModule=6
	and a.autoidx in (cast(@id@ as bigint)) 
	group by a.autoidx,Reference

END













GO


