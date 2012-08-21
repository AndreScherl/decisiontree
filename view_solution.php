<?php   // $Id: view_solution.php,v 1.0 2010/05/28 09:26:00
/**
 * This page prints a particular instance of decisiontree
 * 
 * @author 	Andre Scherl
 * @version $Id: view_solution.php,v 1.0 2010/05/28 09:26:00
 * @package decisiontree
 *
 * Copyright (C) 2011, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */

    require_once("../../config.php");
    require_once("lib.php");
    
    global $DB, $USER;

    $id = optional_param('id', 0, PARAM_INT); // Solution ID
    $did  = optional_param('did', 0, PARAM_INT);  // decisiontree ID
    
    if ($id) {
    	if (! $solution = $DB->get_record("decisiontree_solutions", array("id" => $id))) {
    		error("Question ID is not set");
    	}
    	if (! $decisiontree = $DB->get_record("decisiontree", array("id" => $solution->decisiontree_id))) {
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
            error("Course module is incorrect");
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

    $strdecisiontrees = get_string("modulenameplural", "decisiontree");
    $strdecisiontree  = get_string("modulename", "decisiontree");

    $PAGE->set_pagelayout("standard");
                  
    echo $OUTPUT->header();

/// Print the main part of the page

    if($solution){
    	$OUTPUT->box_start();
    	print format_text($solution->solution, FORMAT_HTML, $options=null, $course->id);
    	$OUTPUT->box_end();
    	
 		// the solution should be stored in the user model to work with ilms adaptation plugin
 		// so that's the following code (not needed to use only decision tree functions)
 		
 		// first check if ilms learner metadata table exists
 		$dbman = $DB->get_manager();
 		if($dbman->table_exists("block_user_preferences_learnermeta")) {
	 		// build the learner metadata object to update/store in database
	 		$lmdo = new object(); // LearnerMetaDataObject
	 		$lmdo->userid = $USER->id;
	 		$lmdo->timemodified = time();
	 		
	 		switch($solution->solutiontitle) {
	 			case "active-reflective-high":
	 				$lmdo->definitionid = $DB->get_field("block_user_preferences_learnermeta_definitions", "id", array("attribute" => "learningstyle_processing"));
	 				$lmdo->value = 0.1;
	 				break;
	 			case "active-reflective-neutral":
	 				$lmdo->definitionid = $DB->get_field("block_user_preferences_learnermeta_definitions", "id", array("attribute" => "learningstyle_processing"));
	 				$lmdo->value = 0.5;
	 				break;
	 			case "active-reflective-low":
	 				$lmdo->definitionid = $DB->get_field("block_user_preferences_learnermeta_definitions", "id", array("attribute" => "learningstyle_processing"));
	 				$lmdo->value = 0.9;
	 				break;
	 			case "sensing-intuitive-high":
	 				$lmdo->definitionid = $DB->get_field("block_user_preferences_learnermeta_definitions", "id", array("attribute" => "learningstyle_perception"));
	 				$lmdo->value = 0.1;
	 				break;
	 			case "sensing-intuitive-neutral":
	 				$lmdo->definitionid = $DB->get_field("block_user_preferences_learnermeta_definitions", "id", array("attribute" => "learningstyle_perception"));
	 				$lmdo->value = 0.5;
	 				break;
	 			case "sensing-intuitive-low":
	 				$lmdo->definitionid = $DB->get_field("block_user_preferences_learnermeta_definitions", "id", array("attribute" => "learningstyle_perception"));
	 				$lmdo->value = 0.9;
	 				break;
	 			case "visual-verbal-high":
	 				$lmdo->definitionid = $DB->get_field("block_user_preferences_learnermeta_definitions", "id", array("attribute" => "learningstyle_input"));
	 				$lmdo->value = 0.1;
	 				break;
	 			case "visual-verbal-neutral":
	 				$lmdo->definitionid = $DB->get_field("block_user_preferences_learnermeta_definitions", "id", array("attribute" => "learningstyle_input"));
	 				$lmdo->value = 0.5;
	 				break;
	 			case "visual-verbal-low":
	 				$lmdo->definitionid = $DB->get_field("block_user_preferences_learnermeta_definitions", "id", array("attribute" => "learningstyle_input"));
	 				$lmdo->value = 0.9;
	 				break;
	 			case "sequential-global-high":
	 				$lmdo->definitionid = $DB->get_field("block_user_preferences_learnermeta_definitions", "id", array("attribute" => "learningstyle_perspective"));
	 				$lmdo->value = 0.9;
	 				break;
	 			case "sequential-global-neutral":
	 				$lmdo->definitionid = $DB->get_field("block_user_preferences_learnermeta_definitions", "id", array("attribute" => "learningstyle_perspective"));
	 				$lmdo->value = 0.5;
	 				break;
	 			case "sequential-global-low":
	 				$lmdo->definitionid = $DB->get_field("block_user_preferences_learnermeta_definitions", "id", array("attribute" => "learningstyle_perspective"));
	 				$lmdo->value = 0.1;
	 				break;	
	 		}
	 		
	 		// push the learner meta data object to database
	 		if($learnerMeta = $DB->get_record("block_user_preferences_learnermeta", array("userid" => $lmdo->userid, "definitionid" => $lmdo->definitionid))) {
	 			// update
	 			$lmdo->id = $learnerMeta->id;
	 			$DB->update_record("block_user_preferences_learnermeta", $lmdo);
	 		} else {
	 			// insert
	 			$DB->insert_record("block_user_preferences_learnermeta", $lmdo);
	 		}
	 	}
    } else {
    	p("no solution found");
    }


/// Finish the page
    echo $OUTPUT->footer();

?>
