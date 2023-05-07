<?php
session_start() ;
    if (session_status() == PHP_SESSION_ACTIVE) { session_destroy(); $_SESSION['user'] = ' ';}
    
    


   header("location:index.php");
?>