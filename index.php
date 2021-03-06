<?php 
include('functions.php'); 

if (!isLoggedIn()) {
	        $_SESSION['msg'] = "You must log in first";
	        header('location: login.php'); 
}
if (isset($_GET['logout'])) {
  	session_destroy();
  	unset($_SESSION['username']);
  	header("location: login.php");
 }
?>
<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	<div class="header">
		<h2>Home Page</h2>
	</div>
	<div class="content">
		<!-- notification message -->
		<?php if (isset($_SESSION['success'])) : ?>
			     <div class="error success" >
				<h3>
					<?php 
						echo $_SESSION['success']; 
						unset($_SESSION['success']);
					?>
				</h3>
			</div>
		    <?php endif ?>

		<!-- logged in user information -->
		<div class="profile_info">
			<img src="images/user_profile.png"  >

			<div>
				<?php  if (isset($_SESSION['user'])) : ?>
					<p>Welcome! <strong><?php echo $_SESSION['user']['username']; ?></strong></p>

					<small>
						<i  style="color: #888;">(<?php echo ucfirst($_SESSION['user']['user_type']); ?>)</i> 
						<br>
						<p><a href="index.php?logout='1'" style="color: red;">logout</a></p>
					</small>

				<?php endif ?>
			</div>
		</div>
	</div>
</body>
</html> 