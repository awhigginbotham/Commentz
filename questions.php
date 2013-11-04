<?php
    //
    // questions.php
    // Show the list of questions and allow selection for
    // modification or deletion. Also allows new questions
    // to be added (all transfer the user to answer.php
    // which handles an individual answer.
    //
	require_once 'includes/global.inc.php';
    require_once 'classes/Question.class.php';
    require_once 'classes/QuestionTools.class.php';
	
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

    <title>Questions:</title>

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
	<li class="active">Questions</li>
	</ol>
   <div class="container">
        <h2>Questions:</h2>
        <div class="list-group">
        <?php
            // Show all the questions.
            $qTools = new QuestionTools();
            $qTools->showQuestionList("question.php");
        ?>
        </div>
        <a href="question.php" class="btn btn-lg btn-primary btn-block" value="add" name="add-submit" />New</a>
    </div>
  </body>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.js"></script>
</html>