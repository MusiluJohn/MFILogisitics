<?php
session_start();
?>
<html>
<title>

</title>
<link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,700,700i,600,600i">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/baguettebox.js/1.10.0/baguetteBox.min.css">
<link rel="stylesheet" href="assets/css/styles.min.css">
<!-- CSS only -->
<!-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous"> -->
<head>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
<link href="https://cdn.datatables.net/1.11.4/css/jquery.dataTables.min.css" rel="stylesheet">
<center>
<h2>C&P SHOE WITHHOLDING</h2>
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
include("connect.php");
if (isset($_POST['login'])){
$name=$_POST['name'];
$password=$_POST['pwd'];
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
    header("Location:main.php");
    }
    else {
        echo('INVALID CREDENTIALS');
} 
}}
?>
<html>