<?php
	require_once 'User.class.php';
	require_once 'DB.class.php';

class UserTools {

	//Log the user in. First checks to see if the 
	//username and password match a row in the database.
	//If it is successful, set the session variables
	//and store the user object within.
	public function login($username, $password)
	{

		$hashedPassword = md5($password);
		$result = mysql_query("SELECT * FROM users WHERE username = '$username' AND password = '$hashedPassword'");

		if(mysql_num_rows($result) == 1)
		{
			$loggedUser = new User(mysql_fetch_assoc($result));
			$_SESSION["user"] = serialize($loggedUser);
			$_SESSION["userID"] = $loggedUser->id;
			$_SESSION["login_time"] = time();
			$_SESSION["logged_in"] = 1;
			return(true);
		}
		else{
			return false;
		}
	}
	
	//Log the user out. Destroy the session variables.
	public function logout() {
		unset($_SESSION["user"]);
		unset($_SESSION["userID"]);
		unset($_SESSION["login_time"]);
		unset($_SESSION["logged_in"]);
		session_destroy();
	}

	//Check to see if a username exists.
	//This is called during registration to make sure all user names are unique.
	public function checkUsernameExists($username) {
		$result = mysql_query("select id from users where username='$username'");
    	if(mysql_num_rows($result) == 0)
    	{
			return false;
	   	}else{
	   		return true;
		}
	}
	
	// getUsers
	// Return an associative array of users keyed by their IDs. The contents
	// of each array entry is a two-element array containing the user's firstName 
	// and lastName.
	//
	public function getUsers($showAll = false) {
		$db = new DB();
		if ($showAll)
			$rows = $db->select2("*","users","","userPriv, lastName");
		else
			$rows = $db->select2("*","users","userPriv='S'","lastName");
		
     	if ($db->numRows == 0)
			return(null);
		else {
			foreach($rows as $row) {
				$users[$row['id']] = array($row['firstName'], $row['lastName']);
			}
		}
		return($users);
	}

	// showUserPopup
	// Generates the HTML for a popup menu showing user's first and last names. The
	// value of each menu item is the id of the indicated user. The $showAll parameter
	// indicates whether administrators should be shown as well as students.
	//
	public function showUserPopup($userID, $showAll = false) {
		$db = new DB();
		if ($showAll)
			$rows = $db->select2("id, firstName,lastName","users","","userPriv, lastName");
		else
			$rows = $db->select2("id, firstName,lastName","users","userPriv='S'","lastName");
		
		echo '<div class="btn-group">' . "\n";
		echo '  <select class="form-control" name="userPopup" id="userPopup">' . "\n";
		
       if ($db->numRows == 0)
            echo "";
        elseif ($db->numRows == 1) {
            echo '<option value="'. $rows["id"] . '">' . $rows["firstName"] . " " . $rows["lastName"] . "\n";
        }
        else {
            foreach($rows as $row) {
				if ($userID == $row["id"])
					echo '<option selected value="'. $row["id"] . '">' . $row["firstName"] . " " . $row["lastName"] . "\n";
				else
					echo '<option value="'. $row["id"] . '">' . $row["firstName"] . " " . $row["lastName"] . "\n";
            }
        }
		echo '   </select>' . "\n";
		echo '</div>' . "\n";
	}
	
	//get a user
	//returns a User object. Takes the users id as an input
	public function get($id)
	{
		$db = new DB();
		$result = $db->select('users', "id = $id");
		
		return new User($result);
	}
	
}

?>
