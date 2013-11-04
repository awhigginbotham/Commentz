<?php 
	// register.php
	// Register a user in the system to allow them
	// to login.
	//
	require_once 'includes/global.inc.php';

	//check to see if they're logged in
	if(!isset($_SESSION['logged_in'])) {
		header("Location: login.php");
	}
	
	//get the user object from the session
	$userID = $_SESSION["userID"];
	$uTool = new UserTools();
	$user = $uTool->get($userID);
	
	// This function is only available to administrators.
	if ($user->userPriv != 'A') {
		header("Location: login.php");
	}
	
	//initialize php variables used in the form
	$username = "";
	$password = "";
	$password_confirm = "";
	$error = "";
	$email = "";
	$firstName = "";
	$lastName = "";
	
	//check to see that the form has been submitted
	if(isset($_POST['submit-form'])) { 
	
		//retrieve the $_POST variables
		$username = $_POST['username'];
		$password = $_POST['password'];
		$password_confirm = $_POST['password-confirm'];
		$email = $_POST['email'];
		$firstName = $_POST['firstName'];
		$lastName = $_POST['lastName'];
	
		//initialize variables for form validation
		$success = true;
		$userTools = new UserTools();
		
		//validate that the form was filled out correctly
		//check to see if user name already exists
		if($userTools->checkUsernameExists($username))
		{
			$error .= "That username is already taken.<br/> \n\r";
			$success = false;
		}
	
		//check to see if passwords match
		if($password != $password_confirm) {
			$error .= "Passwords do not match.<br/> \n\r";
			$success = false;
		}
	
		if($success)
		{
			//prep the data for saving in a new user object
			$data['username'] = $username;
			$data['password'] = md5($password); //encrypt the password for storage
			$data['email'] = $email;
			$data['firstName'] = $firstName;
			$data['lastName'] = $lastName;
		
			//create the new user object
			$newUser = new User($data);
		
			//save the new user to the database
			$newUser->save(true);
		
			//log them in
			$userTools->login($username, $password);
		
			//redirect them to the main page
			header("Location: index.php");
			
		}
	
	}
	
	//If the form wasn't submitted, or didn't validate
	//then we show the registration form again
?>


<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="Register a user." content="">
    <meta name="Dr. Brown" content="">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>Evaluator Registration</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="signin.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>
<body>
   <div class="container">
        <h2 class="form-signin-heading" style="text-align: center">Evaluator Registration:</h2>

	<form class="form-signin" action="register.php" method="post">	
	<input type="text" class="form-control" placeholder="PSCC User Name" value="<?php echo $username; ?>" autofocus name="username" required/><br>
	<input type="text" class="form-control" placeholder="First Name" value="<?php echo $firstName; ?>" name="firstName" required/><br>
	<input type="text" class="form-control" placeholder="Last Name" value="<?php echo $lastName; ?>" name="lastName" required/><br>
	<input type="password" class="form-control" placeholder="Password" value="<?php echo $password; ?>" name="password" required/>
	<input type="password" class="form-control" placeholder="Password (confirm)" value="<?php echo $password; ?>" name="password-confirm" required/>
	<input type="text" class="form-control" placeholder="Email address" value="<?php echo $email; ?>" name="email" required/><br>
	<button type="submit" class="btn btn-lg btn-primary btn-block" value="Register" name="submit-form" />Register</button>
	</form>
	
	<?php print $error; ?>
</body>
</html>
