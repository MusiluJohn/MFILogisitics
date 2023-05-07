<?php
include("config.php");
$serial=$_POST['serial'] ?? '';
$counter=$_POST['counter'];
for($i = 0; $i < count($serial); $i++){
$batchlines="--update the serial numbers
declare @count bigint

set @count=(select count(serialcounter) from SerialMF where SerialNumber='$serial[$i]')
update serialmf set SerialNumber=(case when @count=0 then '$serial[$i]' else Serialnumber end)
where SerialCounter=$counter[$i]";

sqlsrv_query($conn, $batchlines) or die(print_r( sqlsrv_errors(), true));    
}
?>