<?php
                require "connect.php";
                $incwith = $_POST['incwith'];
                $Incl = $_POST['Incl'];
                $Vat_w=$_POST['Vat_w'];

                $amt_to_pay=$Incl-($Vat_w+$incwith);
                echo $amt_to_pay;
?>