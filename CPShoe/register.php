<html>
<title>

</title>
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">
<link rel="stylesheet" href="assets/css/styles.min.css">
<head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<center>
<img src="mfi logo.jpg">
<h2>CAPWELL GL EXPORT REPORT</h2>
</center>
</head>
<body>
<center>
<div class="row justify-content-center">
<div class="demo-heading pull">
</div>
<div class="login-form ">
<h4>Registration:</h4>
<form method="post" action="">
<div class="form-group">
<input name="f_name" id="f_name" type="text" class="form-control" placeholder="Enter FirstName" autofocus="" required>
</div>
<div class="form-group">
<input name="l_name" id="l_name" type="text" class="form-control" placeholder="Enter LastName" autofocus="" required>
</div>
<div class="form-group">
<input name="name" id="name" type="text" class="form-control" placeholder="Enter Username" autofocus="" required>
</div>
<div class="form-group">
<input type="password" class="form-control" name="pwd" placeholder="Enter Password">
</div>
<div class="form-group">
<input type="password" class="form-control" name="pwd_confirm" placeholder="confirm Password">
</div>
<div class="form-group">
<button type="submit" name="login" class="btn btn-info">Register</button>
</div>
</form>
<div class="form-group">
<a href="index.php">Login</a>
</div>
</div>
</div>
</center>
<?php
include("connect.php");
if (isset($_POST['login'])){
$f_name=$_POST['f_name'];
$l_name=$_POST['l_name'];
//$group=$_POST['group'];
$name=$_POST['name'];
$password=$_POST['pwd'];
$password_confirm=$_POST['pwd_confirm'];
$password_hash= password_hash($password, PASSWORD_DEFAULT);

if ($password == $password_confirm){



$sqlQuery = "insert into cpl_sageusers (username,password,FirstName,LastName)
values('$name','$password_hash','$f_name','$l_name')";
sqlsrv_query($conn, $sqlQuery) or die(print_r( sqlsrv_errors(), true));
echo "<script>alert('$f_name successfully created. Log in to continue');window.location = 'index.php';</script>";
}
else {
    echo "<script>alert('Passwords do not match!');</script>";
}
}
?>
</body>