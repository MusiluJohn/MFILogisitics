<?php
    require "connect_main.php";

        $items =$_POST['item'];
        $units=$_POST['id'];
        $sql = "update stkitem set iUOMDefPurchaseUnitID=$units ,iUOMDefSellUnitID=$units,iUOMStockingUnitID=$units
        where stocklink=$items";
        sqlsrv_query($conn, $sql) or die(print_r( sqlsrv_errors(), true));
        echo "success";

?>