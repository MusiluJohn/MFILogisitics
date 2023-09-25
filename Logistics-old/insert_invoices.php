<?php
include('config2.php');


{
 $CI_NO =  $_POST['CI_NO'];
 $CI_Quantity =  $_POST['CI_Quantity'];
 $Final_Item_Code =  $_POST['Final_Item_Code'];
 $Pickup_NO =  $_POST['Pickup_No'];
 $CI_Date =  $_POST['CI_Date'];
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

          

 $query = "INSERT INTO [CPL_Sage International Invoice] (Line_ID, Int_Order_ID, CI_NO, CI_Date, CI_Quantity, Final_Item_Code, Pickup_NO)
 VALUES('$Line_ID', '$order', '$CI_NO', '$CI_Date', '$CI_Quantity' , '$Final_Item_Code', '$Pickup_NO ')";
 
 if(sqlsrv_query($conn, $query))
 {
    
  
 
    echo ($query2); 


 
 }
 Else{
     echo 'YOU HAVE AN ERROR';
 
}
 }
?>