<?php
    $serverName = "HPSERVER"; //serverName\instanceName
    $database = "CPSHOE";
    $connectionInfo = array( "Database"=>$database, "UID"=>"sa", "PWD"=>"P@ssw0rd");
    $conn = sqlsrv_connect( $serverName, $connectionInfo); 
    if ( $conn ) {
        $connStatus = "Connection established to SQL Server.<br />";
   }else{
        $connStatus = "Connection could not be established to SQL Server.<br />";
        die( print_r( sqlsrv_errors(), true));
    }
    //echo $connStatus;


?>