<?php
require_once 'DB.class.php';
//
// Question
// DB support class for the question table.
//
class Question {

	// Properties
    public $tableName = "question";
	public $id;
	public $title;
	public $type;
	public $text;

    // Constructor
    // Load object with whatever data is passed in.
    //
	function __construct($data) {
		$this->id = (isset($data['id'])) ? $data['id'] : "";
		$this->title = stripslashes((isset($data['title'])) ? $data['title'] : "");
		$this->type = (isset($data['type'])) ? $data['type'] : "";
		$this->text = stripslashes((isset($data['text'])) ? $data['text'] : "");
	}
	
	// loadData
	// Support function used in insert and modify to get data
	// ready to pass to the DB class for processing.
	//
	private function loadData() {
		$title = mysql_real_escape_string($this->title);
		$text = mysql_real_escape_string($this->text);
	    $data = array(
            "title" => "'$title'",
            "type" => "'$this->type'",
            "text" => "'$text'");

		return($data);
	}

	// insert
	// insert a new record into the table.
	//
	public function insert() {
		$db = new DB();
        $data = $this->loadData();
        $this->id = $db->insert($data, $this->tableName);
		return true;
	}
    
    // update
    // update table with current data.
    //
	public function update() {
		$db = new DB();		
        $data = $this->loadData();
        $db->update($data, $this->tableName, 'id = '. $this->id);

		return true;
	}
    
    // delete
    // Delete a record from the table.
    //
	public function delete() {
		$db = new DB();
        $db->delete($this->tableName, 'id = '.$this->id);

		return true;
	}
}

?>