<?php 
	// settings.php
	// Handle modification of user settings.
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
	
	//initialize php variables used in the form
	$email = $user->email;
	$link = $user->link;
	$message = "";
	
	//check to see that the form has been submitted
	if(isset($_POST['modify-settings'])) { 
		$user->email = $_POST['email'];
		$user->link = $_POST['link'];
		$user->blog = $_POST['blog'];
		$user->save();
	
		$message = "Settings Saved";
	}
?>


<html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.png">

    <title>Settings</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="includes/js/html5shiv.js"></script>
      <script src="includes/respond.min.js"></script>
    <![endif]-->
  </head>
	<body>
	<?php
		showNavbar($user,"Settings");
	?>
	<div class="container">
		<h2>Settings</h2>
		<form class="form-horizontal" action="settings.php" method="post">
			<label for="email">EMail:</label>
			<input type="email" id="email" class="form-control well-lg" placeholder="Email" autofocus name="email" required value="<?php echo $user->email; ?>" /><br>
			<label for="link">Website Link:</label>
			<input type="url" id="link" class="form-control well-lg" placeholder="Website URL" name="link" required value="<?php echo $user->link; ?>" /><br>
			<label for="blog">Blog Link:</label>
			<input type="url" id="blog" class="form-control well-lg" placeholder="Blog URL" name="blog" required value="<?php echo $user->blog; ?>" /><br>
			<button type="submit" class="btn btn-lg btn-primary btn-block" name="modify-settings">Modify</button>
			</form>
		<?php echo "<div>$message</div>"; ?>
	</div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.js"></script>
</body>
</html>
