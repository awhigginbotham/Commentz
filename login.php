<?php
	// login.php
	// Provides a form for users to attempt to login to
	// the system. It uses UserTools to check credentials.
	//
	require_once 'includes/global.inc.php';
	
	$error = "";
	$username = "";
	$password = "";

	//check to see if they've submitted the login form
	if(isset($_POST['submit-login'])) { 
	
		$username = $_POST['username'];
		$password = $_POST['password'];
	
		$userTools = new UserTools();
		if($userTools->login($username, $password)){ 
			//successful login, redirect them to a page
			header("Location: index.php");
		}
		else{
			$error = "<h2 class=\"text-danger\" style=\"text-align: center\">Incorrect username or password. Please try again.</h2>";
		}
	}
?>

<html>
	<head>
	   <meta charset="utf-8">
	   <meta name="viewport" content="width=device-width, initial-scale=1.0">
	   <meta name="User login." content="">
	   <meta name="Dr. Brown" content="">
	   <link rel="shortcut icon" href="images/favicon.png">
       
	   <title>Commentz Login</title>
       
	   <!-- Bootstrap core CSS -->
	   <link href="css/bootstrap.css" rel="stylesheet">
       
	   <!-- Custom styles for this template -->
	   <link href="css/signin.css" rel="stylesheet">
       
	   <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	   <!--[if lt IE 9]>
	     <script src="../../assets/js/html5shiv.js"></script>
	     <script src="../../assets/js/respond.min.js"></script>
	   <![endif]-->
	 </head>
	<body>
    <h2 class="form-signin-heading" style="text-align: center">Commentz login:</h2>

	<form class="form-signin" action="login.php" method="post">	
	<input type="text" class="form-control" placeholder="PSCC User Name" value="<?php echo $username; ?>" autofocus name="username" /><br>
	<input type="password" class="form-control" placeholder="Password" value="<?php echo $password; ?>" name="password" />
	<button type="submit" class="btn btn-lg btn-primary btn-block" value="Register" name="submit-login" />Login</button>	
	</form>
	<?php
		echo $error."<br/>";
	?>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.js"></script>
</body>
</html>