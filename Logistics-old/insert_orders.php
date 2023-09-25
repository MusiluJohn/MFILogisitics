<?php
include('config2.php');


{
 $MIPO_NO =  $_POST['MIPO_NO'];
 $MIPO_Quantity =  $_POST['MIPO_Quantity'];
 $PI_Num =  $_POST['PI_Num'];
 $PI_Date =  $_POST['PI_Date'];
 $PO_NO =  $_POST['PO_NO'];
 $Item_Code =  $_POST['Item_Code'];


 $Line = "select Line_ID from CPL_SagePoLine where PO_NO = '$PO_NO' and Stock_Ordercode = '$Item_Code' ";
 
 $result = sqlsrv_query($conn,$Line);
 if( $result === false ) {
    die( print_r( sqlsrv_errors(), true));
}

// Make the first (and in this case, only) row of the result set available for reading.
if( sqlsrv_fetch( $result) === false) {
    die( print_r( sqlsrv_errors(), true));
}
$Line_ID = sqlsrv_get_field( $result, 0);

  
          

 $query = "INSERT INTO CPL_Sage_International_Order(Line_ID,MIPO_NO, MIPO_Quantity, PI_NO, PI_Date) 
 VALUES('$Line_ID', '$MIPO_NO', '$MIPO_Quantity', '$PI_Num', '$PI_Date')";
 
 if(sqlsrv_query($conn, $query))
 {
    $Order_id = "select int_order_id from CPL_Sage_International_Order where MIPO_NO = '$MIPO_NO' and Line_ID = '$Line_ID' ";

    $result = sqlsrv_query($conn,$Order_id);
 if( $result === false ) {
    die( print_r( sqlsrv_errors(), true));
}

// Make the first (and in this case, only) row of the result set available for reading.
if( sqlsrv_fetch( $result) === false) {
    die( print_r( sqlsrv_errors(), true));
}
$order = sqlsrv_get_field( $result, 0);

  
  $query2 = "INSERT INTO [CPL_Sage International Invoice](Line_ID,Int_Order_ID) 
 VALUES('$Line_ID', '$order')";

if(sqlsrv_query($conn, $query2))
{
    echo ('Row Inserted Successfully'); 
}


 
 }
 Else{
     echo 'YOU HAVE AN ERROR';
 
}
 }
?>