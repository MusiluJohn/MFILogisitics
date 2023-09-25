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
            //Firstly, we need to "break" the date up by using the explode function.
            $dateExploded = explode("-", $value);

            //For the sake of clarity, lets assign our array elements to
            //named variables (day, month, year).
            $day = $dateExploded[0];
            $month = $dateExploded[1];
            $year = $dateExploded[2];

            //Our $dateExploded array should contain three elements.
            if(count($dateExploded) != 3){
            echo('Enter a valid date in dd-mm-yyyy format!');
            }
            
            //Finally, use PHP's checkdate function to make sure
            //that it is a valid date and that it actually occured.
            else if(!checkdate($month, $day, $year)){
                echo($value . ' is not a valid date!');
                }
            

            else{

        $sql = "update CPL_SageInternationalInvoice
        set ".$column." = CONVERT(varchar(10), CONVERT(date, '$value', 103), 120)
        
        from
        CPL_SageInternationalOrder
        INNER JOIN CPL_SagePoLine
         ON CPL_SagePoLine.LineID = CPL_SageInternationalOrder.LineID
         where CPL_SageInternationalInvoice.ID = $id
         ";
        
        
        $params = array($column,$value,$id);
        $stmt = sqlsrv_query( $conn, $sql, $params);
                if( $stmt === false ) {
                    die( print_r( sqlsrv_errors(), true));
                }
                else {

                    $sql2 = "update CPL_SageInternationalOrder set Agent = '$fname' + ' ' +  '$lname'
                    , DateTimeStamp = getdate() 
                    where CPL_SageInternationalInvoice.ID = $id ";
                }
                
                $stmt2 = sqlsrv_query( $conn, $sql2);
                        if( $stmt2 === false ) {
                            die( print_r( sqlsrv_errors(), true));
                        }
            
            
        }}
    
        
        
    
    else{
    
        echo('Invalid Quantity');
        echo($column); 
        

    }
}


?>