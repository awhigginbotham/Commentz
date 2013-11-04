<?php
	// form.php
	// Handle insert, update and delete of
	// an individual form.
	//
	require_once 'includes/global.inc.php';
    require_once 'classes/Form.class.php';
	require_once 'classes/FormTools.class.php';
	
	// check to see if they're logged in
	if(!isset($_SESSION['logged_in'])) {
		header("Location: login.php");
	}
	
	// get the user object from the session
	$userID = $_SESSION["userID"];
	$uTool = new UserTools();
	$user = $uTool->get($userID);
	
	// this is an admin only screen.
	if ($user->userPriv != 'A') {
		header("Location: index.php");
	}
    
    $form = null;	// will contain the current form object
    $formID = "";	// the id of the current form
    $mode = "";		// which mode we are in: insert or modify
    
	//check to see that the form has been submitted
    if (isset($_POST['formID'])) {
        $formID = $_POST['formID'];
		$data['id'] = $formID;
        $data['title'] = $_POST['title'];
        if ($_POST['active'])
            $data['active'] = 1;
        else
            $data['active'] = 0;
        $form = new Form($data);
    } // check for GET parameters, too
	elseif (isset($_GET['formID'])) {
         $formID = $_GET['formID'];
		 $qTool = new FormTools();
		 $form = $qTool->get($formID);
	}
		 
    //
    // Handle each mode.
    //
	if(isset($_POST['add-submit'])) {
        $form->insert();	// insert the record
        $mode = "modify";	// show the form for modification
	}
    elseif (isset($_POST['modify-submit'])) {
        $form->update();	// update the record
        $mode = "modify";	// show the form for modification
    }
    elseif (isset($_POST['delete-submit'])) {
        $form->delete();	// delete the record
        $form = new Form();
        $mode = "insert";	// now ready to insert again
	}
	elseif (isset($_GET['formID'])) {
		$mode = "modify";	// otherwise, if we have a form, allow modification
	}
    else {
        $form = new Form();	// no form - just allow insertion of new form
        $mode = "insert";
    }
	
?>

<html>
	<head>
	   <meta charset="utf-8">
	   <meta name="viewport" content="width=device-width, initial-scale=1.0">
	   <meta name="Form" content="">
	   <link rel="shortcut icon" href="images/favicon.png">
       
		<title>Form</title>
       
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
	<li><a href="forms.php">Forms</a></li>
	<li class="active">Form</li>
	</ol>
	<div class="container">
       <form class="form-horizontal" action="form.php" method="post">
       <input type="hidden" name="formID" value="<?php echo $form->id; ?>">
       <h2>Form Title:</h2>
       <input type="text" class="form-control well-lg" placeholder="Form Title" autofocus name="title" required value="<?php echo $form->title; ?>" />
        <h2  style="display: inline">Active?</h2>
        <input type="checkbox" class="form-control" name="active" <?php if ($form->active == 1) echo 'checked' ?> /><br />
        <?php
            $fTool = new FormTools();
            //
            // If we are viewing an existing form, show the form's questions (if any).
            //
            if ($form->id != "") {
                $fTool->showFormQuestions($form->id,"question.php");
            }
            //
            // If this is a new form (that isn't in the db yet), just show the Add button.
            // Otherwise, show Modify, Delete and New buttons.
            //
            if ($mode == "insert")
                echo '<button type="submit" class="btn btn-lg btn-primary btn-block" value="add" name="add-submit" />Add</button>';
            else {
                echo '<button type="submit" class="btn btn-lg btn-primary btn-block" value="modify" name="modify-submit" />Modify</button>';
                echo '<button data-toggle="modal" href="#myModal" class="btn btn-lg btn-primary btn-block" value="delete">Delete</button>';
                echo '<button type="submit" class="btn btn-lg btn-primary btn-block" value="new" name="new-submit" />New</button>';
            }
            //
            // Code for a modal dialog shown on delete requests.
            //
           	confirmDeleteModal("form");
        ?>
		</form>
      	//
      	// Show errors (or messages).
      	// 
        <?php print $error; ?>
	</div>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="//code.jquery.com/jquery-latest.min.js"></script>
    <script src="js/bootstrap.js"></script>
	</body>
</html>