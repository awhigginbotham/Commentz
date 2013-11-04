<?php
require_once 'Answer.class.php';
require_once 'DB.class.php';
//
// AnswerTools
// Support class for working with the answer table.
//
class AnswerTools {
    
    // get
    // Return an Answer object with the indicated id.
    //
	public function get($id)
	{
		$db = new DB();
		$result = $db->select('answer', "id = $id");
		
		return new Answer($result);
	}
    
    // getAllAnswersTo
    // Returns an associative array containing all of the answer records with 
    // the indicated toID (all answers to a particular person).
    //
    public function getAllAnswersTo($toID) {
        $db = new DB();
        $whereClause = "toID='" . $toID . "' and users.id=fromID and questionID=question.ID";
        $rows = $db->select2("fromID, toID, questionID, answer.text as answerText, question.text as questionText",
            "answer, users, question",$whereClause, "questionID, lastName");
            
        return($rows);
    }

    // getAllAnswersTo
    // Returns an associative array containing all of the answer records with 
    // the indicated toID (all answers from a particular person).
    //
   public function getAllAnswersFrom($fromID) {
        $db = new DB();
        $whereClause = "fromID='" . $fromID . "' and users.id=toID and questionID=question.ID";
        $rows = $db->select2("fromID, toID, questionID, answer.text as answerText, question.text as questionText",
            "answer, users, question",$whereClause, "questionID, lastName");
            
        return($rows);
    }

    // getAnswers
    // Returns an associative array containing all of the answer records with 
    // the indicated fromID and toID. If either is not supplied, it is not
    // used to restrict which records are returned.
    //
   public function getAnswers($fromID = "", $toID = "") {
        $answers = array();

        // Construct the where clause from supplied fromID and toID.
        $db = new DB();
        if ($fromID != "")
           $whereClause = "fromID = '$fromID'";
        else
           $whereClause = "";
        if ($toID != "") {
            if ($whereClause != "")
                $whereClause .= " and ";
           $whereClause .= "toID = '$toID'";
        }
        
        $rows = $db->select2("id","answer",$whereClause);
        
        // Process the resulting answer.
        if ($db->numRows == 0)
            return $answers;
        elseif ($db->numRows == 1) {
            $answers[0] = $rows["id"];
        }
        else {
            $i = 0;
            foreach($rows as $row) {
                $answers[$i++] = $row["id"];
            }
        }

        return($answers);
    }
}
?>