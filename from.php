<?php
	// from.php
	// Allows users to see comments to them from
	// other students. 
	//
	require_once 'includes/global.inc.php';
	require_once 'classes/Answer.class.php';
	require_once 'classes/AnswerTools.class.php';
	
	//check to see if they're logged in
	if(!isset($_SESSION['logged_in'])) {
		header("Location: login.php");
	}
	
	$aTool = new AnswerTools();
	
	//get the user object from the session
	$userID = $_SESSION["userID"];
	$uTool = new UserTools();
	$user = $uTool->get($userID);
    $fromID = "";
	$fromUser = null;
	//
	// See who is selected from the user popup menu. This is
	// who we want to see comments from.
	//	
	if (isset($_POST['fromID'])) {
        $fromID = $_POST['fromID'];
	}
	else { // If no one is selected, select the first one in the menu.
		$db = new DB();
		$rows = $db->select2("id","users","","userPriv, lastName");
		$fromID = $rows[0]["id"];
	}
	$fromUser = $uTool->get($fromID);
?>

<html>
	<head>
	   <meta charset="utf-8">
	   <meta name="viewport" content="width=device-width, initial-scale=1.0">
	   <meta name="User login." content="">
	   <meta name="Dr. Brown" content="">
	   <link rel="shortcut icon" href="images/favicon.png">
       
		<title>Commentz From:</title>
       
	   <!-- Bootstrap core CSS -->
	   <link href="css/bootstrap.css" rel="stylesheet">
       
	   <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	   <!--[if lt IE 9]>
	     <script src="../../assets/js/html5shiv.js"></script>
	     <script src="../../assets/js/respond.min.js"></script>
	   <![endif]-->
	   <script src="//code.jquery.com/jquery-latest.min.js"></script>
	   <script>
		$(function() {
			$("#userPopup").change(function() {
				$("#fromID").val(this.value);
				$("#answerForm").submit();
			});
		})
	   </script>
	</head>
	<body>
	<?php
		showNavbar($user,"From");
	?>
	<div class="container">
		<h2>Commentz From:</h2>
		<form id="answerForm" class="form-horizontal" action="from.php" method="post">
			<?php
				// Show a popup with the users to see comments from.
				//
				$uTools = new UserTools();
				$uTools->showUserPopup($fromID, true);
				// Hidden field containing the id of the person that we want to see comments from.
				echo '<input id="fromID" name="fromID" type="hidden" value="' . $fromID . '">';

				// Show selected user site link.
				echo '<br><br><label for="link">Site Link:</label>' . "\n";
				$link = $fromUser->link;
				if ($link == null) 
					echo "No link defined yet for this user.\n";
				else
					echo '<a id="link" href="' . $link . '" target="_BLANK">' . $link . '</a>' . "\n";

				// Show selected user blog link.
				echo '<br><br><label for="link">Blog Link:</label>' . "\n";
				$blog = $fromUser->blog;
				if ($blog == null) 
					echo "No blog link yet for this user.\n";
				else
					echo '<a id="link" href="' . $blog . '" target="_BLANK">' . $blog . '</a>' . "\n";
				echo "<br><br>\n";
				
				//
				// Get and then show the questions and answers (Note: answers are read-only).
				//
				$rows = $db->select2("questionID, question.title, text, type","form, formQuestions, question", "form.title='Exam 2' and formID=form.ID and questionID=question.ID");

				if ($db->numRows == 0)
					 echo "";
				 elseif ($db->numRows == 1) {
					 echo '<label for="question' . $rows["questionID"]. '">' . $rows["text"] . "</label>\n";
				 }
				 else {
				 	//
				 	// Show the questions and answers.
				 	//
					foreach($rows as $row) {
						echo '<label for="question' . $row["questionID"]. '">' . $row["text"] . "</label>\n";
						$whereClause =  "questionID=" . $row['questionID'] . " and fromID='" . $fromID . "' and toID='" . $userID . "'";
						$results = $db->select2("text","answer",$whereClause);
						if ($db->numRows == 0)
							$text = "";
						else
							$text = $results["text"];

						// Handle "rating" questions by showing a popup.
						if ($row["type"] == 'R') {
							echo '<select disabled name="question' . $row["questionID"] . '" id="question' . $row["questionID"] . '"class="form-control" >' . "\n";
							for($i = 1; $i <= 10; $i++) {
								if ($text == (string)$i)
									echo '  <option selected value="' . $i . '">' . $i . '</option>' . "\n";
								else
									echo '  <option value="' . $i . '">' . $i . '</option>' . "\n";
							}
							echo '</select><br>' . "\n";
						}
						// Handle regular text questions.
						else {
							echo '<textarea readonly name="question' . $row["questionID"] . '" id="question' . $row["questionID"] . '" class="form-control well-lg" rows="5" placeholder="' . $row["title"] . '" name="answer">' . $text . '</textarea></br>' . "\n";
						}
					}
				 }
			?>
		</form>
	</div>
	<?php
		echo $error;
	?>
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="js/bootstrap.js"></script>
	</body>
</html>