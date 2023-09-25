<?php
session_start();
include('config2.php');

$usercode = $_SESSION['user'];
$fname = $_SESSION['fname'];
$lname = $_SESSION['lname'];
if (isset($_POST['id'])){

    
    $value = $_POST['value'];
    $column = $_POST['column'];
    $id = $_POST['id'];
    $count=count($_POST["id"]);

    // $quantity_column = 'CPL_SageInternationalOrder.MIPO_Quantity';
    // $quantity_query = " select Quantity_Ordered from CPL_SagePoLine where LineID in (select line_id from CPL_SageInternationalOrder where IntOrderID= $id)";
    // $quantity = sqlsrv_query($conn, $quantity_query);

    // if( $quantity  === false ) {
    //     die( print_r( sqlsrv_errors(), true));
    // }
    
    // // Make the first (and in this case, only) row of the result set available for reading.
    // if( sqlsrv_fetch( $quantity ) === false) {
    //     die( print_r( sqlsrv_errors(), true));
    // }
    // $qty = sqlsrv_get_field( $quantity, 0);

    

    if ($value != ''){
        for($i=0;$i<$count;$i++){
        $sql = "update CPL_SageInternationalOrder
        set ".$column[$i]." =  '".$value[$i]."'
        from
        CPL_SageInternationalOrder
        INNER JOIN CPL_SagePoLine
         ON CPL_SagePoLine.LineID = CPL_SageInternationalOrder.LineID
         where CPL_SageInternationalOrder.IntOrderID = $id[$i]
         ";
        echo($sql);
        
        $params = array($column,$value,$id);
        $stmt = sqlsrv_query( $conn, $sql, $params);
        }
                if( $stmt === false ) {
                    die( print_r( sqlsrv_errors(), true));
                }
                else {

                    $sql2 = "update CPL_SageInternationalOrder set Agent = '$fname' + ' ' +  '$lname'
                    , DateTimeStamp = getdate() 
                    where CPL_SageInternationalOrder.IntOrderID = $id ";
                }
                
                $stmt2 = sqlsrv_query( $conn, $sql2);
                        if( $stmt2 === false ) {
                            die( print_r( sqlsrv_errors(), true));
                        }
            
            
        }
    
        
        
    
    else{
    
        echo('Invalid Quantity');
        echo($column); 
        

    }
}


?>