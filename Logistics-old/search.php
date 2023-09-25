<?php
  require_once 'config2.php';

  if (isset($_POST['query'])) {
    $inpText = $_POST['query'];
    $sql = "SELECT DISTINCT top(10) PONO  FROM CPL_SagePoLine WHERE PONO LIKE '%$inpText%' ";
   
    $result = sqlsrv_query($conn, $sql);

    if ($result) {
      while($row = sqlsrv_fetch_array($result)){
      
        echo '<a href="#" class="list-group-item list-group-item-action border-1">' . $row['PONO'] . '</a>';
      }
    } else {
      echo '<p class="list-group-item border-1">No Record</p>';
    }
  }
?>