<?php
	// reports.php
	// Show two reports. One report allows students to
    // see all of the comments to them from every other
    // student (ordered by question). The second report
    // allows them to see their comments to every other
    // student (also ordered by question).
    //
    // Clicking on a user name allows them to see either
    // all of that person's answers to them or all of their
    // answers to that person depending on which report is
    // being viewed (controlled via radio button).
    //
	require_once 'includes/global.inc.php';
	require_once 'classes/Answer.class.php';
	require_once 'classes/AnswerTools.class.php';
	
	//check to see if they're logged in
	if(!isset($_SESSION['logged_in'])) {
		header("Location: login.php");
	}
    $uTool = new UserTools();
    $aTool = new AnswerTools();
	
	//get the user object from the session
	$userID = $_SESSION["userID"];
    if ($userID == "") {
       echo "Lost userID SESSION variable...<br>";
       $uTool->logout();
       header("Location: login.php");
    }
	$user = $uTool->get($userID);

    // Which report? From or To?
    //
    if (isset($_POST['reportType']))
        $mode = $_POST['reportType'];
    else
        $mode = "from";
    if (isset($_POST['userPopup']))
        $actAsUID = $_POST['userPopup'];
    else
        $actAsUID = $userID;
?>

<html>
	<head>
	   <meta charset="utf-8">
	   <meta name="viewport" content="width=device-width, initial-scale=1.0">
	   <meta name="User login." content="">
	   <meta name="Dr. Brown" content="">
	   <link rel="shortcut icon" href="images/favicon.png">

		<title>Commentz To:</title>
       
	   <!-- Bootstrap core CSS -->
	   <link href="css/bootstrap.css" rel="stylesheet">
        <style>
            .level1 {
                padding: 9px 14px;
                margin-bottom: 14px;
                background-color: #f7f7f9;
                border: 1px solid #e1e1e8;
                border-radius: 4px;
            }       
            .level2 {
                padding: 9px 14px;
                margin-bottom: 14px;
                background-color: #fcfcfc;
                border: 1px solid #e1e1e8;
                border-radius: 4px;
            }       
            .level3 {
                padding: 9px 14px;
                margin-bottom: 14px;
                background-color: #f7f7f9;
                border: 1px solid #e1e1e8;
                border-radius: 4px;
            }       
            h4 {color: blue;}   
        </style>
       
	   <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	   <!--[if lt IE 9]>
	     <script src="../../assets/js/html5shiv.js"></script>
	     <script src="../../assets/js/respond.min.js"></script>
	   <![endif]-->
	   <script src="//code.jquery.com/jquery-latest.min.js"></script>
	   <script>
		$(function() {
            $("#fromReport").change( function() {
                $("#reportForm").submit();
            });
            $("#toReport").change( function() {
                $("#reportForm").submit();
            });
            $("#userPopup").change( function() {
                $("#reportForm").submit();                
            });
            $(".userLinks").click( function() {
                actAsUID = $(this).attr('data-user');
                $("#toID").val(actAsUID);
                $("#fromID").val(actAsUID);
                $("#report").submit();                
            });
		})
	   </script>
	</head>
	<body>
	<?php
		showNavbar($user,"Reports");
	?>
	<div class="container">
		<h2>Commentz Report:</h2>
        <form id="reportForm" method="post" action="reports.php">
            <div class="btn-group" data-toggle="buttons">
                <?php
                    // Set up the two types of reports From and To.
                    if ($mode == "from") {
                        echo '<label class="btn btn-primary active">' . "\n";
                        echo '<input type="radio" name="reportType" id="fromReport" value="from" checked> From:' . "\n";
                        echo '</label>' . "\n";
                        echo '<label class="btn btn-primary">' . "\n";
                        echo '<input type="radio" name="reportType" id="toReport" value="to"> To:' . "\n";
                        echo '</label>' . "\n";
                    }
                    else {
                        echo '<label class="btn btn-primary">' . "\n";
                        echo '<input type="radio" name="reportType" id="fromReport" value="from"> From:' . "\n";
                        echo '</label>' . "\n";
                        echo '<label class="btn btn-primary active">' . "\n";
                        echo '<input type="radio" name="reportType" id="toReport" value="to" checked> To:' . "\n";
                        echo '</label>' . "\n";
                   }
                ?>
            </div>
            <?php
                //
                // Allow administrators to act as any student.
                //
                if ($user->userPriv == 'A') {
                    echo "Act as: ";
                    $uTool->showUserPopup($actAsUID,true);
                }
            ?>
        </form> 
        <?php
            if ($mode == "to") {
                echo '<form id="report" method="post" action="index.php">' . "\n";
            }
            else {
                echo '<form id="report" method="post" action="from.php">' . "\n";
            }
            echo '   <input type="hidden" id="toID" name="toID">' . "\n";
            echo '   <input type="hidden" id="fromID" name="fromID">' . "\n";
            echo '   </form>' . "\n";
            echo '<div class="level1">' . "\n";
            $users = $uTool->getUsers(true);

            // Get the answers for this report.
            //
            if ($mode == "from")
                $answers = $aTool->getAllAnswersTo($actAsUID);
            else
               $answers = $aTool->getAllAnswersFrom($actAsUID);
            //
            // Build the report.
            //
            if ($answers != null) {
                echo '<div class="level2">' . "\n";
                $prevQuestionID = $answers[0]['questionID'];
                echo '<h4>' . $answers[0]['questionText'] . '</h4><br>' . "\n";
                //
                // Show each answer.
                //
                foreach($answers as $answer) {
                    // Handle breaks between different questions (since the reports are ordered by question).
                    if ($answer['questionID'] != $prevQuestionID) {
                        echo '</div><hr>' . "\n";
                        echo'<div class="level2">' . "\n";
                        echo '<h4>' . $answer['questionText'] . '</h4><br>' . "\n";
                        $prevQuestionID = $answer['questionID'];
                    }
                    if ($mode == "from")
                        $user = $users[$answer['fromID']];
                    else
                        $user = $users[$answer['toID']];  
                    //
                    // Make the user name a link that links either to all of the answers
                    // from or to this user depending on which report is being viewed. 
                    //                
                    if ($mode == "from")
                        echo '<button class="userLinks btn btn-primary btn-xs" data-user="' . $answer['fromID'] . '">' . $user[0] . " " . $user[1] . ": </button>"  . "<br>";
                    else
                        echo '<button class="userLinks btn btn-primary btn-xs" data-user="' . $answer['toID'] . '">' . $user[0] . " " . $user[1] . ": </button>"  . "<br>";
                    echo'<div class="level1">' . "\n";
                    echo $answer['answerText'];
                    echo '</div>' . "\n";
                }
                echo '</div><hr>' . "\n";
            }
         ?>
        </div>
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
