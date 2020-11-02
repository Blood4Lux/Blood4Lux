<?php include('functions.php') ;
$db = mysqli_connect('localhost', 'root', '', 'multi_login');
if(isset($_GET['token'])){
    $token=mysqli_real_escape_string($conn, $_GET['token']);
	$query="SELECT * from password_reset where token='$token'";
	$results=mysqli_query($conn, $query);
	if(mysqli_num_rows($results)>0){
	   $row=mysqli_fetch_array($results);
	   $token=$row['token'];
	   $email=$row['email'];
}else{
	header("location:login.php");
}
}

if(isset($_POST['submit'])){
	$password=mysqli_real_escape_string($conn, $_POST['password']);
	$confirmpassword=mysqli_real_escape_string($conn, $_POST['confirmpassword']);
	$options=['cost=>11'];
	$hashed=password_hash($password, PASSWORD_BCRYPT, $options);
	if($password!=$confirmpassword){
		$msg="<div class='alert alert-danger'>Sorry, passwords didn't matched</div>";
	}elseif (strlen($password)<6) {
		$msg="<div class='alert alert-danger'>Password must be 6 characters long.</div>";	
	}else{
		$query="update users set password='$hashed'where email='$email'";
		mysqli_query($conn, $query);
		$query="delete from password_reset where email='$email'";
		mysqli_query($conn, $query);
		$msg="<div class='alert alert-success'>Password Updated.</div>";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Blood Bank Registration system PHP and MySQL</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="header">
		<h2>Reset Password</h2>
	</div>
	<form method="post" action="login.php">
		   <?php echo display_error(); ?>
		<div class="input-group">
			<label>Email</label>
			<input type="text" class="form-control" name=""value="<?php echo $email; ?>">
		</div>   

		<div class="input-group">
			<label>Password</label>
			<input type="password" class="form-control" name="password" >
		</div>
		<div class="input-group">
			<label>Confirm Password</label>
			<input type="password" class="form-control" name="confirmpassword">
		</div>
		<?php if(isset($msg)){echo $msg;}?>
		<div class="input-group">
			<button name="submit" class="btn">Reset Password</button>
		</div>
		
	</form>
</body>
</html>