<?php
include("config.php");
$serial=$_POST['serial'];
for($i = 0; $i < count($serial); $i++){
$serialcheck="select distinct (case when serialnumber<>'' then 'This serial number exists'
else 'Serial validated successfully' end) as value
from serialmf where serialnumber='$serial[$i]'";
$params = array();
$options =  array( "Scrollable" => SQLSRV_CURSOR_KEYSET );			
$stmt = sqlsrv_query($conn,$serialcheck,$params,$options) or die(print_r( sqlsrv_errors(), true));		
if( $stmt === false) {
    die( print_r( sqlsrv_errors(), true) );
}
while( $row = sqlsrv_fetch_array( $stmt, SQLSRV_FETCH_ASSOC) ) {
    if ($row['value']){
        echo $row['value'];
    } else {
        echo 'The serial do not exists';
    }
} 
}
?>