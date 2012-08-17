<?php   // $Id: edit_question.php,v 1.0 2010/05/28 09:26:00
/**
 * This page prints a particular instance of decisiontree
 * 
 * @author 	Andre Scherl
 * @version $Id: edit_question.php,v 1.0 2010/05/28 09:26:00
 * @package decisiontree
 *
 * Copyright (C) 2011, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */

    require_once("../../config.php");
    require_once("lib.php");  
    
    global $DB;
    
	$id = optional_param('id', 0, PARAM_INT); // Question ID
	$did = optional_param('did', 0, PARAM_INT); // Decision Tree ID
	
	if ($id) {
	    if (! $question = $DB->get_record("decisiontree_questions", array("id" => $id))) {
	    	error("No Question ID set");
	    }	
	    if (! $decisiontree = $DB->get_record("decisiontree", array("id" => $question->decisiontree_id))) {
	        error("Decision tree is incorrect...");
	    }
	    if (! $course = $DB->get_record("course", array("id" => $decisiontree->course))) {
	    	error("Course is misconfigured");
	    }
	    if (! $cm = get_coursemodule_from_instance("decisiontree", $decisiontree->id, $course->id)) {
	        error("Course Module ID was incorrect");
	    }
	}else{
		if (! $decisiontree = $DB->get_record("decisiontree", array("id" => $did))) {
			error("Decision tree is incorrect");
		}
		if (! $course = $DB->get_record("course", array("id" => $decisiontree->course))) {
			error("Course is misconfigured");
		}
		if (! $cm = get_coursemodule_from_instance("decisiontree", $decisiontree->id, $course->id)) {
			error("Course Module ID was incorrect");
		}
	}
	  

    require_login($course->id);

    add_to_log($course->id, "decisiontree", "view", "view.php?id=$cm->id", "$decisiontree->id");

/// Print the page header

    if ($course->category) {
        $navigation = "<a href=\"../../course/view.php?id=$course->id\">$course->shortname</a> ->";
    } else {
        $navigation = '';
    }

    $strdecisiontrees = get_string("modulenameplural", "decisiontree");
    $strdecisiontree  = get_string("modulename", "decisiontree");

    print_header("$course->shortname: $decisiontree->name", "$course->fullname",
                 "$navigation <a href=index.php?id=$course->id>$strdecisiontrees</a> -> $decisiontree->name", 
                  "", "", true, update_module_button($cm->id, $course->id, $strdecisiontree), 
                  navmenu($course, $cm));

/// Print the main part of the page
	require_once 'edit_question_form.php';
    
    if($question->id){
		$form = new edit_question_form("edit_question.php?id=$question->id");
    }else{
    	$form = new edit_question_form("edit_question.php?did=$decisiontree->id");
    }
    
    $form->set_data(array(	'id'=>$question->id,
    						'decisiontree_id'=>$decisiontree->id));
   	$form->display();
    
   	
/// Database actions
   	// Insert/update the table decisiontree_questions by filling in the question data
    $questiondata = new object();
    $questiondata = $form->get_data();
    if($questiondata && ($question->title != $questiondata->title || $question->text != $questiondata->text || $question->numberofanswers != $questiondata->numberofanswers)) {
	    if($DB->record_exists("decisiontree_questions", array("id" => $question->id))){
	    	$DB->update_record("decisiontree_questions", $questiondata);
	    }else{
	    	$id = $DB->insert_record("decisiontree_questions", $questiondata, $returnid=true, $primarykey='id');
	    	$form->set_data(array('id'=>$id));
			redirect("edit_question.php?id=$id");
	    }
    }
    
    // Insert/update the table decisiontree_answers by filling in the answer data
    $tempanswer = new Object();
    $tempanswer->question = $question->id;
    
    $questiondataarray = get_object_vars($questiondata);
    
    for($i=1; $i<=$questiondata->numberofanswers; $i++){
    	$tempanswer->answer = $questiondataarray['answer'.$i];
    	$tempanswer->position = $i;
    	if($tempanswer->answer != $DB->get_field("decisiontree_answers", "answer", array("id" => $tempanswer->id))) {
		    if($tempanswer->id = $DB->get_field("decisiontree_answers", "id", array("question" => $id, "position" => $i))){
		    	$DB->update_record("decisiontree_answers", $tempanswer);
		    }else{
		    	$DB->insert_record("decisiontree_answers", $tempanswer, $returnid=true, $primarykey='id');
		    }
    	}
    	if($i==$questiondata->numberofanswers){
    		redirect("edit_question.php?id=$id");
    	}
    }
    
    
    // Delete the answers not needed in decistiontree_answers any more
    $select = "question = {$question->id} AND position > {$question->numberofanswers}";
    if($question){
    	$DB->delete_records_select("decisiontree_answers", $select);
    }
        
/// Finish the page
    print_footer($course);

?>
