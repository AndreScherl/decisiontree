<?php   // $Id: edit_question_form.php,v 1.0 2010/05/28 13:39:00
/**
 * This page prints a particular instance of decisiontree
 * 
 * @author 	Andre Scherl
 * @version $Id: answer_options_form.php,v 1.0 2010/05/28 13:39:00
 * @package decisiontree
 *
 * Copyright (C) 2011, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */

    require_once("$CFG->libdir/formslib.php");
    
    class answer_options_form extends moodleform {
    	
	    function definition() {
			global $DB;
			
	    	$id = optional_param('id', 0, PARAM_INT); // Question ID
	    	$did = optional_param('did', 0, PARAM_INT); // Decision Tree ID
	    	
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
		            error("Course module is incorrect");
		        }
		        if (! $course = $DB->get_record("course", array("id" => $decisiontree->course))) {
		            error("Course is misconfigured");
		        }
		        if (! $cm = get_coursemodule_from_instance("decisiontree", $decisiontree->id, $course->id)) {
		            error("Course Module ID was incorrect");
		        }
		        if (! $question = $DB->get_record("decisiontree_questions", array("root" => 1, "decisiontree_id" => $decisiontree->id))) {
		        	redirect("edit_question.php?did=$decisiontree->id");
		        	error("No Question ID set AND no existing root question");
		        }
		    }
	    	
	    	
	    	// answers
	    	if(! $answers = $DB->get_records("decisiontree_answers", array("question" => $question->id), "position")) {
	    		error("Database Error");
	    	}
	    
	        $mform =& $this->_form; // Don't forget the underscore!	        
	      
	        
	        // answer of questions
	        $mform->addElement('hidden', 'numberofanswers');
        	$mform->setType('numberofanswers', PARAM_INT);
        	
        	// question id
	        $mform->addElement('hidden', 'id');
        	$mform->setType('id', PARAM_INT);
        	
        	// question title
	        $mform->addElement('hidden', 'title');
        	$mform->setType('title', PARAM_INT);
        	
        	// decisiontree id
	        $mform->addElement('hidden', 'decisiontree_id');
        	$mform->setType('decisiontree_id', PARAM_INT);
        	
        	// print the question text
        	$mform->addElement('html', $question->text);
        	
	        // set answer options
	        foreach($answers as $answer) {
				$mform->addElement('radio', 'answer', ' ',$answer->answer, $answer->position);
	        }

			// buttons
	        $this->add_action_buttons($cancel = false, $submitlabel=get_string('decisiontree_next', 'decisiontree'));
	    }    
    }
?>
