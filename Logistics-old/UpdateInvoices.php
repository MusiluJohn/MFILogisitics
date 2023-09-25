<?php

include('config2.php');
session_start();

if (isset($_POST['id'])){
    
    
    $value = $_POST['value'];
    $column = $_POST['column'];
    $id = $_POST['id'];
    
    $fname = $_SESSION['fname'];
    $lname = $_SESSION['lname'];

        

    

    $sql = "update CPL_SageInternationalInvoice
    set ".$column." = '".$value."'
    
     where CPL_SageInternationalInvoice.ID = $id
     ";
    echo($sql);
    $params = array($column,$value,$id);
    $stmt = sqlsrv_query( $conn, $sql, $params);
			if( $stmt === false ) {
				die( print_r( sqlsrv_errors(), true));
			}
    

    else{
        $sql2 = "update CPL_SageInternationalInvoice set Agent = '$fname' + ' ' +  '$lname'
        , DateTimeStamp = getdate() 
        where CPL_SageInternationalInvoice.ID = $id ";
    
    
    $stmt2 = sqlsrv_query( $conn, $sql2);
            if( $stmt2 === false ) {
                die( print_r( sqlsrv_errors(), true));
    }
}}





?>