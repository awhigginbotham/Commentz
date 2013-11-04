<?php
  function showNavbar($user,$active = "") {
    echo '	<nav class="navbar navbar-default" role="navigation">' . "\n";
    echo '	  <!-- Brand and toggle get grouped for better mobile display -->' . "\n";
    echo '	  <div class="navbar-header">' . "\n";
    echo '		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">' . "\n";
    echo '		  <span class="sr-only">Toggle navigation</span>' . "\n";
    echo '		  <span class="icon-bar"></span>' . "\n";
    echo '		  <span class="icon-bar"></span>' . "\n";
    echo '		  <span class="icon-bar"></span>' . "\n";
    echo '		</button>' . "\n";
    echo '		<a class="navbar-brand" href="#">Commentz</a>' . "\n";
    echo '	  </div>' . "\n";
    echo '		' . "\n";
    echo '		  <!-- Collect the nav links, forms, and other content for toggling -->' . "\n";
    echo '		  <div class="collapse navbar-collapse navbar-ex1-collapse">' . "\n";
    echo '			<ul class="nav navbar-nav">' . "\n";
    if ($active == "To")
      echo '			  <li class="active"><a href="index.php">To</a></li>' . "\n";
    else
      echo '			  <li><a href="index.php">To</a></li>' . "\n";
    if ($active == "From")
        echo '			  <li class="active"><a href="from.php">From</a></li>' . "\n";
      else
        echo '			  <li><a href="from.php">From</a></li>' . "\n";      
    if ($user->userPriv == 'A') {
    if ($active == "Reports")
        echo '        <li class="active"><a href="reports.php">Reports</a></li>' . "\n";
    else
        echo '        <li><a href="reports.php">Reports</a></li>' . "\n";
	}
    if ($active == "Settings")
        echo '        <li class="active"><a href="settings.php">Settings</a></li>' . "\n";
    else
        echo '        <li><a href="settings.php">Settings</a></li>' . "\n";  
    if ($user->userPriv == 'A') {
    	  echo '           <ul class="nav navbar-nav navbar-right">' . "\n"; 
          echo '			  <li class="dropdown">' . "\n"; 
          echo '			  <a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>' . "\n"; 
          echo '			  <ul class="dropdown-menu">' . "\n"; 
          echo '			     <li><a href="users.php">Users</a></li>' . "\n"; 
          echo '			     <li><a href="forms.php">Forms</a></li>' . "\n"; 
          echo '			     <li><a href="questions.php">Questions</a></li>' . "\n"; 
          echo '			  </ul>' . "\n"; 
          echo '			  </li>' . "\n"; 
          echo '			  </ul>' . "\n"; 
    }
    echo '			</ul>' . "\n";
    echo '			<ul class="nav navbar-nav navbar-right">' . "\n";
    echo '			  <li style="margin-top: 15; margin-right: 10">Welcome ' . "$user->firstName" . '!</li>' . "\n";
    echo '			  <li><a class="btn btn-default" href="logout.php">Log Out</a></li>' . "\n";
    echo '			</ul>' . "\n";
    echo '		  </div><!-- /.navbar-collapse -->' . "\n";
    echo '		</nav>' . "\n";
  }
?>
