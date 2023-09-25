<?php
$servername = "MUSILU\MSSQLSERVER_2019" ;
//$connectioninfo = array( "Database"=>"MFI-DS", "UID"=>"sa", "PWD"=>"P@ssw0rd");
$connectioninfo = array( "Database"=>"MFI-DS", "UID"=>"sa", "PWD"=>"john");
$conn = sqlsrv_connect( $servername, $connectioninfo);

if ($conn) {

}else{
echo "Connection Failed<br/>";
die(print_r( sqlsrv_errors(), true));

}
?>