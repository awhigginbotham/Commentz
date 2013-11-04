<?php
require_once 'Question.class.php';
require_once 'DB.class.php';

class QuestionTools {
    
	public function get($id)
	{
		$db = new DB();
		$result = $db->select('question', "id = $id");
		
		return new Question($result);
	}
    
    public function showQuestionList($whereTo = "#") {
        $db = new DB();   
        $rows = $db->select("question");
            
        if ($db->numRows == 0)
            echo "There are no questions.";
        elseif ($db->numRows == 1) {
            echo '<a href="' . $whereTo . '?questionID=' . $rows["id"] . '" class="list-group-item form-control">' . $rows["title"] . "</a>";
        }
        else {
            foreach($rows as $row) {
                echo '<a href="' . $whereTo . '?questionID=' . $row["id"] . '" class="list-group-item form-control">' . $row["title"] . "</a>";
            }
        }
    }
}
?>