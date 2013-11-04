<?php
require_once 'DB.class.php';
//
// User
// DB support class for the user table.
//
class User {

	// Properties
	public $id;
	public $username;
	public $hashedPassword;
	public $email;
	public $joinDate;
	public $firstName;
	public $lastName;
	public $link;
	public $blog;

	//Constructor is called whenever a new object is created.
	//Takes an associative array with the DB row as an argument.
	function __construct($data) {
		$this->id = (isset($data['id'])) ? $data['id'] : "";
		$this->username = stripslashes((isset($data['username'])) ? $data['username'] : "");
		$this->hashedPassword = (isset($data['password'])) ? $data['password'] : "";
		$this->email = stripslashes((isset($data['email'])) ? $data['email'] : "");
		$this->joinDate = (isset($data['join_date'])) ? $data['join_date'] : "";
		$this->firstName = stripslashes((isset($data['firstName'])) ? $data['firstName'] : "");
		$this->lastName = stripslashes((isset($data['lastName'])) ? $data['lastName'] : "");
		$this->userPriv = (isset($data['userPriv'])) ? $data['userPriv'] : "";
		$this->link = stripslashes((isset($data['link'])) ? $data['link'] : "");
		$this->blog = stripslashes((isset($data['blog'])) ? $data['blog'] : "");
	}

	public function save($isNewUser = false) {
		//create a new database object.
		$db = new DB();
		$username = mysql_real_escape_string($this->username);
		$email = mysql_real_escape_string($this->email);
		$firstName = mysql_real_escape_string($this->firstName);
		$lastName = mysql_real_escape_string($this->lastName);
		$link = mysql_real_escape_string($this->link);
		$blog = mysql_real_escape_string($this->blog);

		//if the user is already registered and we're
		//just updating their info.
		if(!$isNewUser) {
			//set the data array
			$data = array(
				"username" => "'$username'",
				"password" => "'$this->hashedPassword'",
				"email" => "'$email'",
				"firstName" => "'$firstName'",
				"lastName" => "'$lastName'",
				"link" => "'$link'",
				"blog" => "'$blog'"
			);
			
			//update the row in the database
			$db->update($data, 'users', 'id = '. $this->id);
		}else {
		//if the user is being registered for the first time.
			$data = array(
				"username" => "'$username'",
				"password" => "'$this->hashedPassword'",
				"email" => "'$email'",
				"link" => "'$link'",
				"blog" => "'$blog'",
				"firstName" => "'$firstName'",
				"lastName" => "'$lastName'",
				"join_date" => "'".date("Y-m-d H:i:s",time())."'"
			);
			
			$this->id = $db->insert($data, 'users');
			$this->joinDate = time();
		}

		return true;
	}
	
}

?>