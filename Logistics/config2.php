<?php
if(!isset($_SESSION)) 
{ 
    session_start(); 
} 
$servername = "MUSILU" ;
$db=$_SESSION['db'];
//$connectioninfo = array( "Database"=>"$db", "UID"=>"sa", "PWD"=>"P@ssw0rd123$");
$connectioninfo = array( "Database"=>"$db", "UID"=>"sa", "PWD"=>"john");
$conn = sqlsrv_connect( $servername, $connectioninfo);

if ($conn) {

}else{
echo "Connection Failed<br/>";
die(print_r( sqlsrv_errors(), true));

}
?>