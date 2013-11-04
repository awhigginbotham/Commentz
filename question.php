<?php
	// question.php
	// Handle insert, update and delete of
	// an individual question.
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
    
    $question = null;
    $questionID = "";
    $mode = "";
    
	//check to see that the form has been submitted
    if (isset($_POST['questionID'])) {
        $questionID = $_POST['questionID'];
		$data['id'] = $questionID;
        $data['title'] = $_POST['title'];
        $data['type'] = $_POST['type'];
        $data['text'] = $_POST['text'];
        $question = new Question($data);
    } // check for GET parameters, too
	elseif (isset($_GET['questionID'])) {
         $questionID = $_GET['questionID'];
		 $qTool = new QuestionTools();
		 $question = $qTool->get($questionID);
	}
		 
    
    //
    // Handle each mode.
    //
	if(isset($_POST['add-submit'])) {
        $question->insert();	// insert the record
        $mode = "modify";		// show the question for modification
	}
    elseif (isset($_POST['modify-submit'])) {
        $question->update();	// update the record
        $mode = "modify";		// show the question for modification
    }
    elseif (isset($_POST['delete-submit'])) {
        $question->delete();	// delete the record
        $question = new Question();
        $mode = "insert";		// now ready to insert again
	}
	elseif (isset($_GET['questionID'])) {
		$mode = "modify";		// otherwise, if we have a question, allow modification
	}
    else {
        $question = new Question();	// no question - just allow insertion of new question
        $mode = "insert";
    }
	
?>

<html>
	<head>
	   <meta charset="utf-8">
	   <meta name="viewport" content="width=device-width, initial-scale=1.0">
	   <meta name="Question" content="">
	   <link rel="shortcut icon" href="images/favicon.png">
       
		<title>Question</title>
       
	   <!-- Bootstrap core CSS -->
	   <link href="css/bootstrap.css" rel="stylesheet">
             
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
	<?php
		// first attempst at handling breadcrumbs... not working yet
		//
		if (strpos($_SERVER["HTTP_REFERER"],'questions') !== false) {
			echo '<li><a href="questions.php">Questions</a></li>';
		}
		elseif (strpos($_SERVER["HTTP_REFERER"],'form') !== false) {
			$start = strpos($_SERVER["HTTP_REFERER"],'form');
			echo '<li><a href="' . substr($_SERVER["HTTP_REFERER"],$start) . '">Form</a></li>';
		}
	?>
	<li class="active">Question</li>
	</ol>
	<div class="container">
       <form class="form-horizontal" action="question.php" method="post">
       <input type="hidden" name="questionID" value="<?php echo $question->id; ?>">
       <h2>Question Title:</h2>
       <input type="text" class="form-control well-lg" placeholder="Question Title" autofocus name="title" required value="<?php echo $question->title; ?>" />
        <h2>Question Type:</h2>
        <select class="form-control" name="type">
                <option value="R" <?php if ($question->type == "R") echo "selected"; ?> >Rating</option>
                <option value="C" <?php if ($question->type == "C") echo "selected"; ?> >Comment</option>
        </select>
         <h2>Question Text:</h2>
        <textarea class="form-control well-lg" rows="5" placeholder="Question Text" name="text" required><?php echo $question->text; ?></textarea></br>
        <?php
            //
            // If this is a new question (that isn't in the db yet), just show the Add button.
            // Otherwise, show Modify, Delete and New buttons.
            //
        	if ($mode == "insert")
                echo '<button type="submit" class="btn btn-lg btn-primary btn-block" value="add" name="add-submit" />Add</button>';
            else {
                echo '<button type="submit" class="btn btn-lg btn-primary btn-block" value="modify" name="modify-submit" />Modify</button>';
                echo '<button data-toggle="modal" href="#myModal" class="btn btn-lg btn-primary btn-block" value="delete">Delete</button>';
                echo '<button type="submit" class="btn btn-lg btn-primary btn-block" value="new" name="new-submit" />New</button>';
          }
           confirmDeleteModal("question");
        ?>
      
      </form>
        
        <?php print $error; ?>
	</div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.js"></script>
	</body>
</html>