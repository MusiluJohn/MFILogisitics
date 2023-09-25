<?php 
	require 'config2.php';

	if(isset($_POST['description'])) {

        $ITEM_CODE = $_POST['description'];
		

		$stmt = "SELECT Stkitem.description_1 
        FROM dbo.SagePoLine
        INNER JOIN dbo.CPL_Sage_International_Order
        ON SagePoLine.Line_ID = CPL_Sage_International_Order.Line_ID
        JOIN  dbo.stkitem
        ON CPL_SagePoLine.Stock_ID = stkitem.stocklink
         WHERE CPL_SagePoLine.Stock_Ordercode = '$ITEM_CODE'";

		$result = sqlsrv_query($conn,$stmt);
        if( $result === false ) {
            die( print_r( sqlsrv_errors(), true));
        }
        
        // Make the first (and in this case, only) row of the result set available for reading.
        if( sqlsrv_fetch( $result) === false) {
            die( print_r( sqlsrv_errors(), true));
        }
        $description = sqlsrv_get_field( $result, 0);

		
		echo json_encode($description);
	}

	

 ?>