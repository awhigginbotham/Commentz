<?php
    //
    // forms.php
    // Show the list of forms and allow selection for
    // modification or deletion. Also allows new forms
    // to be added (all transfer the user to form.php
    // which handles an individual form.
    //
	require_once 'includes/global.inc.php';
    require_once 'classes/Form.class.php';
    require_once 'classes/FormTools.class.php';

	//check to see if they're logged in
	if(!isset($_SESSION['logged_in'])) {
		header("Location: login.php");
	}
	
	//get the user object from the session
	$userID = $_SESSION["userID"];
	$uTool = new UserTools();
	$user = $uTool->get($userID);
	
	if ($user->userPriv != 'A') {
		header("Location: index.php");
	}
 ?>
 <html>
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="../../assets/ico/favicon.png">

    <title>Forms</title>

    <!-- Bootstrap core CSS -->
    <link href="css/bootstrap.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="spacelab.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="../../assets/js/html5shiv.js"></script>
      <script src="../../assets/js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
    <?php
		showNavbar($user);
	?>
 	<ol class="breadcrumb">
	<li><a href="index.php">Home</a></li>
	<li class="active">Forms</li>
	</ol>
   <div class="container">
        <h2>Forms:</h2>
        <div class="list-group">
        <?php
            // Show the list of forms.
            $qTools = new FormTools();
            $qTools->showFormList("form.php");
        ?>
        </div>
        <a href="form.php" class="btn btn-lg btn-primary btn-block" value="add" name="add-submit" />New</a>
    </div>
  </body>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.js"></script>
</html>