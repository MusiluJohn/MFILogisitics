<?php
$id=$_GET['delete'];
require "connect.php";
$sql="delete from _cplsupplierwvat where invoiceid=$id";
sqlsrv_query($conn, $sql);
?>

<script>alert('Line successfully deleted'); <?php header('Location: Search_batch.php'); ?></script>
