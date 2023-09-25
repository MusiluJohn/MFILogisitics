<?php
include('config2.php');


if (isset($_POST['id'])){

    
    $value = $_POST['value'];
    $column = $_POST['column'];
    $id = $_POST['id'];

    $quantity_column = 'CPL_SageInternationalOrder.MIPO_Quantity';
    $quantity_query = " select fquantity - fqtylastprocess from _btblinvoicelines where idinvoicelines in (select lineid from CPL_SageInternationalOrder where IntOrderID= $id)";
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
        $sql = "update CPL_SageInternationalOrder
        set ".$column." = '".$value."'
        from
        CPL_SageInternationalOrder
        INNER JOIN CPL_SagePoLine
         ON CPL_SagePoLine.lineid = CPL_SageInternationalOrder.lineid
         where CPL_SageInternationalOrder.IntOrderID = $id
         ";
       
        $params = array($column,$value,$id);
        $stmt = sqlsrv_query( $conn, $sql, $params);
                if( $stmt === false ) {
                    die( print_r( sqlsrv_errors(), true));
                }
        
                
        }
    
        
        
    
    else{
    
        echo('Enter Value for Quantity!');
        
        

    }
}


?>