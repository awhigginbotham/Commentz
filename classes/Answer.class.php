<?php
require_once 'DB.class.php';
//
// Answer
// DB support class for the answer table.
//
class Answer {
	
	// Properties
    public $tableName = "answer";
	public $id;
	public $questionID;
	public $fromID;
	public $toID;
    public $text;
    public $createTime;
    public $modifyTime;

    // Constructor
    // Load object with whatever data is passed in.
    //
	function __construct($data) {
		$this->id = (isset($data['id'])) ? $data['id'] : "";
		$this->questionID = (isset($data['questionID'])) ? $data['questionID'] : "";
		$this->fromID = (isset($data['fromID'])) ? $data['fromID'] : "";
		$this->toID = (isset($data['toID'])) ? $data['toID'] : "";
		$this->text = stripslashes((isset($data['text'])) ? $data['text'] : "");
		$this->createTime = (isset($data['createTime'])) ? $data['createTime'] : "";
		$this->modifyTime = (isset($data['modifyTime'])) ? $data['modifyTime'] : "";
	}
	
	// loadData
	// Support function used in insert and modify to get data
	// ready to pass to the DB class for processing.
	//
	private function loadData() {
		$text = mysql_real_escape_string($this->text);
	    $data = array(
            "questionID" => "'$this->questionID'",
            "fromID" => "'$this->fromID'",
            "toID" => "'$this->toID'",
            "text" => "'$text'",
            "createTime" => "'$this->createTime'",
            "modifyTime" => "'$this->modifyTime'",
            );

		return($data);
	}

	// insert
	// insert a new record into the table.
	// Also record the creation/modification time.
	//
	public function insert() {
		$db = new DB();
        $data = $this->loadData();
		$data['createTime'] = "'" . gmdate("Y-m-d H:i:s") . "'";
		$data['modifyTime'] = "'" . gmdate("Y-m-d H:i:s") . "'";
		$this->id = $db->insert($data, $this->tableName);
		return true;
	}
    
    // update
    // update table with current data.
    // Also record the modification time.
    //
	public function update() {
		$db = new DB();		
        $data = $this->loadData();
		unset($data['createTime']);
		$data['modifyTime'] = "'" . gmdate("Y-m-d H:i:s") . "'";
		$db->update($data, $this->tableName, 'id='. $this->id);

		return true;
	}
	
	// modify
	// Either updates or inserts a record into the table
	// depending on whether or not the record already exists.
	//
	public function modify() {
		$db = new DB();
 		$whereClause =  "questionID=" . $this->questionID . " and fromID='" . $this->fromID . "' and toID='" . $this->toID . "'";
							
		$results = $db->select2("id","answer",$whereClause);
		if ($db->numRows == 0) {
			$this->insert();
		}
		else {
			$this->id = $results["id"];
			$this->update();
		}
	}
    
    // delete
    // Delete a record from the table.
    //
	public function delete() {
		$db = new DB();
		        
        $db->delete($this->tableName, 'id='.$this->id);

		return true;
	}
}

?>