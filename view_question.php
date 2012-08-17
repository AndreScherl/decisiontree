<?php   // $Id: view_question.php,v 1.0 2010/05/28 09:26:00
/**
 * This page prints a particular instance of decisiontree
 * 
 * @author 	Andre Scherl
 * @version $Id: view_question.php,v 1.0 2010/05/28 09:26:00
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
    $did  = optional_param('did', 0, PARAM_INT);  // decisiontree ID
    
    if ($id) {
    	if (! $question = $DB->get_record("decisiontree_questions", array("id" => $id))) {
    		error("Question ID is not set");
    	}
    	if (! $decisiontree = $DB->get_record("decisiontree", array("id" => $question->decisiontree_id))) {
            error("Decision Tree ID in Question Record is incorrect");
        }
    	if (! $course = $DB->get_record("course", array("id" => $decisiontree->course))) {
            error("Course is misconfigured");
        }
        if (! $cm = get_coursemodule_from_instance("decisiontree", $decisiontree->id, $course->id)) {
            error("Course Module ID was incorrect");
        }
    } else {
        if (! $decisiontree = $DB->get_record("decisiontree", array("id" => $did))) {
            print_error("Course module is incorrect");
        }
        if (! $course = $DB->get_record("course", array("id" => $decisiontree->course))) {
            print_error("Course is misconfigured");
        }
        if (! $cm = get_coursemodule_from_instance("decisiontree", $decisiontree->id, $course->id)) {
            print_error("Course Module ID was incorrect");
        }
        if (! $question = $DB->get_record("decisiontree_questions", array("root" => 1, "decisiontree_id" => $decisiontree->id))) {
        	redirect("edit_question.php?did={$decisiontree->id}");
        	print_error("No Question ID set AND no existing root question");
        }
    }

    require_login($course->id);

    add_to_log($course->id, "decisiontree", "view", "view.php?id={$cm->id}", $decisiontree->id);

/// Print the page header
    $strdecisiontrees = get_string("modulenameplural", "decisiontree");
    $strdecisiontree  = get_string("modulename", "decisiontree");

   	$PAGE->set_pagelayout("standard");
                  
    echo $OUTPUT->header();

/// Print the main part of the page
	require_once("answer_options_form.php");
	
    // das muss noch eleganter mit dem header-bearbeiten-button gelöst werden
    if($question->id){
    	print("<a href=edit_question.php?id=$question->id>Frage bearbeiten</a>");
    }

    if($question){
    	$OUTPUT->box_start();
    	
    	// print answer options form (with question text)
    	$answerOptions = new answer_options_form("calculate_question.php");
    	$answerOptions->set_data(array("id" => $question->id,
    									"title" => $question->title,
    									"decisiontree_id" => $decisiontree->id));
    	$answerOptions->display();
    	$OUTPUT->box_end();
    }else{
    	p("no question found: ");
    }
    
    if($question->root) {
    	$SESSION->userpath = "";
    }


/// Finish the page
    echo $OUTPUT->footer();

?>
