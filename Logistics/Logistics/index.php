<?php
session_start();
?>
<html>
<title>

</title>
<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="css/bootsrap2.css"/>
<!-- CSS only -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous"> -->
<head>
<center>
<img src="mfi logo.jpg">
<h2>MFI LOGISTICS APPLICATION</h2>
</center>
</head>
<body>
<center>
<div class="row justify-content-center">
<div class="demo-heading pull">
</div>
<div class="login-form ">
<h4>User Login:</h4>
<form method="post" action="">
<div class="form-group">
<!-- <?php if ($loginError ) { ?>  -->
<div class="alert alert-warning"><?php echo $loginError; ?></div>
<!-- <?php } ?>  -->
</div>
<div class="form-group">
<input name="name" id="name" type="text" class="form-control" placeholder="Username" autofocus="" required>
</div>
<div class="form-group">
<input type="password" class="form-control" name="pwd" placeholder="Password" required>
</div>
<div class="form-group">
<select type="database" class="form-control" name="database" id="database">
<option>MFI-DS</option><option>MFI-TS</option><option>MFI-MDS</option>
</select>
</div>
<div class="form-group">
<button type="submit" name="login" class="btn btn-info">Login</button>
</div>
</form>
<div class="form-group">

</div>
</div>
</div>
</center>
</body>
<?php

if (isset($_POST['login'])){
$name=$_POST['name'];
$password=$_POST['pwd'];
$db=$_POST['database'];
$_SESSION['db']=$db;
include("config.php");
$sqlQuery = "select * FROM CPL_sageusers WHERE UserName='$name'";
$user=sqlsrv_query($conn, $sqlQuery);


    
    while( $rows = sqlsrv_fetch_array( $user, SQLSRV_FETCH_ASSOC) ) {
        $username = $rows['UserName'];
        $Pwd = $rows['Password'];
            
        if ( $name == $username and password_verify($password, $Pwd) ){
            
     $_SESSION['user']=$username; 
     $_SESSION['userid'] = $rows['id'];
     $_SESSION['fname'] = $rows['FirstName'];
     $_SESSION['lname'] = $rows['LastName'];
     $_SESSION['group'] = $rows['Grp'];
    header("Location:module.php");
    }
    else {
        echo('INVALID CREDENTIALS');
} 
}}
?>
<html>