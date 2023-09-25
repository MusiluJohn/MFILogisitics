<?php
include('config.php');


if (isset($_POST['id'])){

    
    $value = $_POST['value'];
    $column = $_POST['column'];
    $id = $_POST['id'];


    $quantity_query = " select Quantity_Ordered from SagePoLine where Line_ID in (select line_id from Sage_International_Order where Int_Order_ID= $id)";
    $quantity = sqlsrv_query($conn, $quantity_query);

    if( $quantity  === false ) {
        die( print_r( sqlsrv_errors(), true));
    }
    
    // Make the first (and in this case, only) row of the result set available for reading.
    if( sqlsrv_fetch( $quantity ) === false) {
        die( print_r( sqlsrv_errors(), true));
    }
    $qty = sqlsrv_get_field( $quantity, 0);

    

    if ($value != ''){

        if ($column = 'Sage_International_Order.MIPO_Quantity' and $value > $qty){
            echo 'Invalid Quantity';

            
            echo($quantity_query);
        }

        else{

    $sql = "update Sage_International_Order
    set ".$column." = '".$value."'
    from
    Sage_International_Order
    INNER JOIN SagePoLine
     ON SagePoLine.Line_ID = Sage_International_Order.Line_ID
     where Sage_International_Order.Int_Order_ID = $id
     ";
    echo($sql);
    $params = array($column,$value,$id);
    $stmt = sqlsrv_query( $conn, $sql, $params);
			if( $stmt === false ) {
				die( print_r( sqlsrv_errors(), true));
			}
    
        }}
    else{

    }
}




?>