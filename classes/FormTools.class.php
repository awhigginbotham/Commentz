<?php
require_once 'Form.class.php';
require_once 'DB.class.php';
//
// FormTools
// Support class for working with the form table.
//
class FormTools {

    // get
    // Return an Form object with the indicated id.
    //
	public function get($id)
	{
		$db = new DB();
		$result = $db->select('form', "id = $id");
		
		return new Form($result);
	}

    // showFormList
    // Creates a list of anchor tags with GET param formID set to the id of each form. Where each
    // anchor links to is controlled by the function input parameter $whereTo.
    //
    public function showFormList($whereTo = "#") {
        $db = new DB();   
        $rows = $db->select("form");
            
        if ($db->numRows == 0)
            echo "There are no forms.";
        elseif ($db->numRows == 1) {
            echo '<a href="' . $whereTo . '?formID=' . $rows["id"] . '" class="list-group-item form-control">' . $rows["title"] . "</a>";
        }
        else {
            foreach($rows as $row) {
                echo '<a href="' . $whereTo . '?formID=' . $row["id"] . '" class="list-group-item form-control">' . $row["title"] . "</a>";
            }
        }
    }
    
    // showFormQuestions
    // Creates a list of anchor tags with GET param questionID set to the id of each question for the. 
    // Which form and the question each anchor links to is controlled by the function input parameters
    // $formID and $whereTo.
    //
    public function showFormQuestions($formID, $whereTo = "#") {
        $db = new DB();   
        $rows = $db->select2("question.id, question.title","formQuestions,form, question", "formID=$formID and form.id=formID and question.id=questionID");
            
       if ($db->numRows == 0)
            echo "There are no questions on this form yet.";
        elseif ($db->numRows == 1) {
            echo '<h2>Questions:</h2>';
            echo '<a href="' . $whereTo . '?questionID=' . $rows["id"] . '" class="list-group-item form-control">' . $rows["title"] . "</a>\n";
        }
        else {
            echo '<h2>Questions:</h2>';
            foreach($rows as $row) {
                echo '<a href="' . $whereTo . '?questionID=' . $row["id"] . '" class="list-group-item form-control">' . $row["title"] . "</a>\n";
            }
        }
        echo "<br />\n";
    }
}
?>