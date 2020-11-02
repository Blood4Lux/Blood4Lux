<?php 
   session_start();

 // connect to database
   $db = mysqli_connect('localhost', 'root', '', 'multi_login');

// variable declaration
   $username = "";
   $email    = "";
   $errors   = array(); 


// call the register() function if register_btn is clicked
   if (isset($_POST['register_btn'])) {
	          register();
}

// call the login() function if register_btn is clicked
   if (isset($_POST['login_btn'])) {
	           login();
}

// log user out if logout button clicked
   if (isset($_GET['logout'])) {
	           session_destroy();
	           unset($_SESSION['user']);
	           header("location: ../login.php");
}

if (isset($_POST['forget_password_btn'])) {
	           forget_password();
}

if (isset($_POST['rest_btn'])) {
	           reset();
}

// REGISTER USER
function register(){
	// call these variables with the global keyword to make them available in function
	     global $db, $errors; //.. $username, $email;

	// receive all input values from the form. Call the e() function
    // defined below to escape form values
	     $username    =  mysqli_real_escape_string ($db, $_POST['username']);
	     $email       =  mysqli_real_escape_string ($db, $_POST['email']);
	     $password_1  =  mysqli_real_escape_string ($db, $_POST['password_1']);
	     $password_2  =  mysqli_real_escape_string ($db, $_POST['password_2']);

	// form validation: ensure that the form is correctly filled
	    if (empty($username)) { 
		array_push($errors, "Username is required"); 
	}
	    if (empty($email)) { 
		array_push($errors, "Email is required"); 
	}
	    if (empty($password_1)) { 
		array_push($errors, "Password is required"); 
	}
	    if ($password_1 != $password_2) {
		array_push($errors, "The two passwords does not match");
	}

  // first check the database to make sure 
  // a user does not already exist with the same username and/or email
       $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
       $result = mysqli_query($db, $user_check_query);
       $user = mysqli_fetch_assoc($result);
  
       if ($user) { // if user exists
       if ($user['username'] === $username) { array_push($errors, "Username already exists"); }

       if ($user['email'] === $email) { array_push($errors, "email already exists");
    }
  }


// register user if there are no errors in the form
	   if (count($errors) == 0) {
		$password = md5($password_1);//encrypt the password before saving in the database


		if (isset($_POST['user_type'])) {
                    $user_type = mysqli_real_escape_string($db, $_POST['user_type']);
                    $query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', '$user_type', '$password')";
			
			        mysqli_query($db, $query);
			        $_SESSION['success']  = "New user successfully created!!";
			        header('location: home.php');
		}
		else{
			        $query = "INSERT INTO users (username, email, user_type, password) 
					  VALUES('$username', '$email', 'user', '$password')";
			        mysqli_query($db, $query);

			// get id of the created user
			       $logged_in_user_id = mysqli_insert_id($db);

			       $_SESSION['user'] = getUserById($logged_in_user_id); // put logged in user in session
			       $_SESSION['success']  = "BLOOD SAVES LIVES!, Registration successful";
			       header('location: index.php');				
		}
	}
}

// return user array from their id
function getUserById($id){
	       global $db;
	       $query = "SELECT * FROM users WHERE id=" . $id;
	       $result = mysqli_query($db, $query);

	       $user = mysqli_fetch_assoc($result);
	       return $user;
}

// LOGIN USER
function login(){
	       global $db, $username, $errors;

	// grap form values
	       $username = mysqli_real_escape_string($db, $_POST['username']);
	       $password = mysqli_real_escape_string ($db, $_POST['password']);

	// make sure form is filled properly
	      if (empty($username)) {
		              array_push($errors, "Username is required");
	}
	      if (empty($password)) {
		             array_push($errors, "Password is required");
	}

	// attempt login if no errors on form
	      if (count($errors) == 0) {
		              $password = md5($password);

		              $query = "SELECT * FROM users WHERE username='$username' AND password='$password' LIMIT 1";
		              $results = mysqli_query($db, $query);

		  if (mysqli_num_rows($results) == 1) { // user found
			// check if user is admin or user
			         $logged_in_user = mysqli_fetch_assoc($results);
			         if ($logged_in_user['user_type'] == 'admin') {

				                 $_SESSION['user'] = $logged_in_user;
				                 $_SESSION['success']  = "Blood Saves Lives! Welcome Admin";
				                 header('location: admin/home.php');		  
			        }else{
				                 $_SESSION['user'] = $logged_in_user;
				                 $_SESSION['success']  = "Blood Saves Lives";

				                header('location: index.php');
			}
		}else {
			        array_push($errors, "Wrong username or password combination");
		}
	}
}

 function isLoggedIn()
{
	        if (isset($_SESSION['user'])) {
		                return true;
	        }else{
		                return false;
	}
}

function isAdmin(){
	       if (isset($_SESSION['user']) && $_SESSION['user']['user_type'] == 'admin' ) {
		              return true;
	      }else{
		              return false;
	}
}

// escape string
function e($val){
	        global $db;
	        return mysqli_real_escape_string($db, trim($val));
}

function display_error() {
	        global $errors;

	       if (count($errors) > 0){
		               echo '<div class="error">';
			                      foreach ($errors as $error){
				                            echo $error .'<br>';
			                     }
		               echo '</div>';
	}
}	
?>