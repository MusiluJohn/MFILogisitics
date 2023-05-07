<html>
<title>

</title>
<link rel="stylesheet" type="text/css" href="css/bootstrap1.css"/>
<link rel="stylesheet" type="text/css" href="css/bootsrap2.css"/>
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
<select name="group" id="group" type="text" class="form-select" required>
    <option id="accounts" name="accounts">Accounts</option>
    <option id="warehouse" name="warehouse">Warehouse</option>
    <option id="admin" name="admin">Admin</option>
</select>
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
include("config2.php");
if (isset($_POST['login'])){
$f_name=$_POST['f_name'];
$l_name=$_POST['l_name'];
$group=$_POST['group'];
$name=$_POST['name'];
$password=$_POST['pwd'];
$password_confirm=$_POST['pwd_confirm'];
$password_hash= password_hash($password, PASSWORD_DEFAULT);

if ($password == $password_confirm){



$sqlQuery = "insert into cpl_sageusers (username,password,FirstName,LastName,Grp)
values('$name','$password_hash','$f_name','$l_name','$group')";
sqlsrv_query($conn, $sqlQuery) or die(print_r( sqlsrv_errors(), true));
echo "<script>alert('$f_name successfully created. Log in to continue');window.location = 'index.php';</script>";
}
else {
    echo "<script>alert('Passwords do not match!');</script>";
}
}
?>
</body>