<?php 
    include('functions.php');
  $db = mysqli_connect('localhost', 'root', '', 'multi_login');
 if (isset($_POST['submit'])){
	$email=mysqli_real_escape_string($conn, $_POST['email']);
	$query = "SELECT * FROM users WHERE email='$email'";
    $run = mysqli_query($conn, $query);
    if(mysqli_num_rows($run)>0){
       $row=mysqli_fetch_array($run);
       $db_email=$run['email'];
       $db_id=row['id'];
       $token=uniqid(md5(time())); //This is a random token
       $query="INSERT INTO password_reset(id, email, token) VALUES(NULL, '$email', 'token')";
       if(mysqli_query($conn, $query)){
       $to = $db_email;
       $subject = "Password reset link";
       $message = "Click <a href='https://phpMyAdmin.com/reset.php?token=$token'>here</a> to reset your password.";
       $headers = "MIME-Version: 1.0". "\r\n";
       $headers.="Content-type:text/html;charset=UTF-8"."\r\n";
       $headers.='From:<multi_login@multi_login.com>'."\r\n";
      //.. mail($to,$subject, $message, $headers);
       $msg ="<div class='alert alert-success'>Password rest link has been sent to the email,</div>";
   }
}else{
$msg="<div class='alert alert-danger'>User not found.</div>";
	
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
		<h2>Forgot Password</h2>
	</div>
	<form method="post" action="login.php">
		   <?php echo display_error(); ?>

		<div class="input-group">
			<label>Enter Email</label>
			<input type="email" name="email" class="form-control" >
		</div>
		<?php if(isset($msg)){echo $msg;}?>
		<div class="input-group">
		<button type="submit" class="btn" name="submit">Submit</button>
		</div>
		
	</form>
</body>
</html>

