<?php
session_start();
?>

<!-- <!DOCTYPE html> -->
<html lang="en">
<head>
	<title>Logistics</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<!-- <link rel="icon" type="image/png" href="images/icons/favicon.ico"/> -->
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<div class="login100-pic js-tilt" data-tilt>
					<img src="images/mfi logo.jpg" alt="IMG">
				</div>

				<form  method="post" action="">
					<span class="login100-form-title">
						User Login
					</span>

					<div class="wrap-input100 validate-input">
						<input class="input100" type="text" name="name" placeholder="UserName">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-user-circle" aria-hidden="true"></i>
							
						</span>
					</div>

					<div class="wrap-input100 validate-input" >
						<input class="input100" type="password" name="pwd" placeholder="Password">
						<span class="focus-input100"></span>
						<span class="symbol-input100">
							<i class="fa fa-lock" aria-hidden="true"></i>
						</span>
					</div>

					<div class="wrap-input100 validate-input" >
						<select class="input100"  name="database" placeholder="Database">
						
    <option value="rigatoni">MFI-DS</option>
  <option value="dave">MFI-TS</option>
  <option value="pumpernickel">MFI-MDS</option>
  <option value="reeses">MFI-TEST</option>
</select>
						<span class="focus-input100"></span>
						<span class="symbol-input100">
						<i class="fa fa-database" aria-hidden="true"></i>
						</span>
					</div>
					
					<div class="container-login100-form-btn">
						<button type="submit" name="login" class="login100-form-btn">
							Login
						</button>
					</div>

					<!-- <div class="text-center p-t-12">
						<span class="txt1">
							Forgot
						</span>
						<a class="txt2" href="#">
							Username / Password?
						</a>
					</div> -->

					<div class="text-center p-t-136">
						<a class="txt2" href="register.php">
							Create your Account
							<i class="fa fa-long-arrow-right m-l-5" aria-hidden="true"></i>
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	<script src="js/main.js"></script>
	<?php
include("config.php");
if (isset($_POST['login'])){
$name=$_POST['name'];
$password=$_POST['pwd'];
$sqlQuery = "select * FROM CPL_sageusers WHERE UserName='$name'";
$user=sqlsrv_query($conn, $sqlQuery);


    
    while( $rows = sqlsrv_fetch_array( $user, SQLSRV_FETCH_ASSOC) ) {
        $username = $rows['UserName'];
        $Pwd = $rows['Password'];
            
        if ( $name == $username and password_verify($password, $Pwd) ){
            
     $_SESSION['user']=$rows['UserName']; 
     $_SESSION['userid'] = $rows['id'];
     $_SESSION['fname'] = $rows['FirstName'];
     $_SESSION['lname'] = $rows['LastName'];
    header("Location:module.php");
    }
    else {
        echo('INVALID CREDENTIALS');
} 
}}
?>	
	

	
<!--===============================================================================================-->	
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/tilt/tilt.jquery.min.js"></script>
	<script >
		$('.js-tilt').tilt({
			scale: 1.1
		})
	</script>
<!--===============================================================================================-->
	

</body>

</html>