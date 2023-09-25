<?php
include('config2.php');
$limit = isset($_POST["limit-records"]) ? $_POST["limit-records"] : 5000;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$start = ($page - 1) * $limit;

if(isset($_GET['search']))
{

$filtervalues = $_GET['search'];	
$invoices = "SELECT
  SagePoLine.PO_NO
 ,SagePoLine.Stock_Ordercode
 ,SagePoLine.Quantity_Ordered
 ,Sage_International_Order.MIPO_NO
 ,Sage_International_Order.MIPO_Quantity
 ,Sage_International_Order.PI_NO
 ,ISNULL(Sage_International_Order.PI_Date, '2021-01-01') AS PI_Date
 ,[Sage International Invoice].ID
 ,[Sage International Invoice].CI_NO
 ,[Sage International Invoice].CI_Quantity
 ,ISNULL([Sage International Invoice].CI_Date, '2021-01-01') AS CI_Date
 ,[Sage International Invoice].Final_Item_Code
 ,[Sage International Invoice].Pickup_NO
 ,Stkitem.description_1
FROM dbo.SagePoLine
INNER JOIN dbo.Sage_International_Order
  ON SagePoLine.Line_ID = Sage_International_Order.Line_ID
INNER JOIN dbo.[Sage International Invoice]
  ON Sage_International_Order.Int_Order_ID = [Sage International Invoice].Int_Order_ID
  JOIN  dbo.stkitem
ON SagePoLine.Stock_ID = stkitem.stocklink
  WHERE SagePoLine.PO_NO LIKE '%$filtervalues%'
  ORDER BY SagePoLine.PO_NO
";
$sql_query="SELECT
SagePoLine.Line_ID
,SagePoLine.PO_NO
,SagePoLine.Stock_Ordercode
,SagePoLine.Quantity_Ordered
,Sage_International_Order.MIPO_NO
,Sage_International_Order.MIPO_Quantity
,Sage_International_Order.PI_NO
,ISNULL(Sage_International_Order.PI_Date,'2021-01-01') AS PI_Date
,SagePoLine.Stock_ID
,Stkitem.description_1
FROM dbo.SagePoLine
INNER JOIN dbo.Sage_International_Order
ON SagePoLine.Line_ID = Sage_International_Order.Line_ID
JOIN  dbo.stkitem
ON SagePoLine.Stock_ID = stkitem.stocklink
 WHERE SagePoLine.PO_NO LIKE '%$filtervalues%'
 ORDER BY SagePoLine.PO_NO 
  OFFSET $start ROWS FETCH FIRST $limit ROWS ONLY
";
$Item_dropdown = "
SELECT SagePoLine.Stock_Ordercode from dbo.SagePoLine
    INNER JOIN dbo.Sage_International_Order
    ON SagePoLine.Line_ID = Sage_International_Order.Line_ID
    JOIN  dbo.stkitem
    ON SagePoLine.Stock_ID = stkitem.stocklink
     WHERE SagePoLine.PO_NO LIKE '%$filtervalues%'
";
}

else{
  echo("Enter a Po Number");
}
// else{
//   $sql_query="SELECT
//   SagePoLine.Line_ID
//   ,SagePoLine.PO_NO
//   ,SagePoLine.Stock_Ordercode
//   ,SagePoLine.Quantity_Ordered
//   ,Sage_International_Order.MIPO_NO
//   ,Sage_International_Order.MIPO_Quantity
//   ,ISNULL(Sage_International_Order.MIPO_Date,'2021-01-01') AS MIPO_Date
//   ,SagePoLine.Stock_ID
//   ,Stkitem.description_1
//   FROM dbo.SagePoLine
//   INNER JOIN dbo.Sage_International_Order
//   ON SagePoLine.Line_ID = Sage_International_Order.Line_ID
//   JOIN  dbo.stkitem
//   ON SagePoLine.Stock_ID = stkitem.stocklink
//   ORDER BY SagePoLine.PO_NO 
//   OFFSET $start ROWS FETCH FIRST $limit ROWS ONLY
//   ";
  

// }
$sql_query2 = "SELECT  count(line_id) AS id from sagepoline";


$result = sqlsrv_query($conn,$sql_query);
$result1 = sqlsrv_query($conn,$sql_query2);
$items = sqlsrv_query($conn,$Item_dropdown);
$invoice = sqlsrv_query($conn,$invoices);
// 	$custCount = sqlsrv_fetch_array( $result1, SQLSRV_FETCH_ASSOC);
//   echo("$custCount");
// 	$total = $custCount[0]['id'];
// 	$pages = ceil( $total / $limit );

// 	$Previous = $page - 1; 
// 	$Next = $page + 1;

	
