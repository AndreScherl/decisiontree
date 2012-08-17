<?php   // $Id: edit_question_form.php,v 1.0 2010/05/28 13:39:00
/**
 * This page prints a particular instance of decisiontree
 * 
 * @author 	Andre Scherl
 * @version $Id: edit_question_form.php,v 1.0 2010/05/28 13:39:00
 * @package decisiontree
 *
 * Copyright (C) 2011, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */
	
///// Hierfür gibt es sicher auch noch eine sauberer Lösung 
	echo "<script type=\"text/javascript\" src=\"script.js\"></script>";
    
	require_once("$CFG->libdir/formslib.php");
	
    class edit_question_form extends moodleform {
    
	    function definition() {
			global $DB;
			
	    	$id = optional_param('id', 0, PARAM_INT); // Question ID
	    	$did = optional_param('did', 0, PARAM_INT); // Decision Tree ID
			    	
			if ($id) {
				if (! $question = $DB->get_record("decisiontree_questions", array("id" => $id))) {
					error("No Question ID set");
				}	
				if (! $decisiontree = $DB->get_record("decisiontree", array("id" => $question->decisiontree_id))) {
					error("Decision tree is incorrect");
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
	  
	 		$mform =& $this->_form;
	 		
        	// choose the question to be edited
        	$questionsarray = $DB->get_records_sql("SELECT id, title FROM {decisiontree_questions} WHERE decisiontree_id = $decisiontree->id ORDER BY title");
        	$questions[0] = get_string("decisiontree_questionnew", "decisiontree");
        	foreach($questionsarray as $questionsitem) {
        		$questions[$questionsitem->id] = $questionsitem->title;
        	}
    		
        	$mform->addElement('select', 'questionselect', get_string('decisiontree_questiontoedit', 'decisiontree'), $questions, "onchange=\"objectSelected('questionselect', './edit_question.php',$decisiontree->id)\"");
        	$mform->setDefault('questionselect', $id);
        	$mform->addElement('html', '<div class="fitemtitle"></div><div class="felement"><a href=delete_record.php?table=decisiontree_questions&id='.$id.'&did='.$decisiontree->id.'>'.get_string('decisiontree_questiondelete', 'decisiontree').'</a></div>');
        	
	        // if the question exists show prefilled fields etc.
        	if($question->title) {
	        	
	        	// question title
		        $mform->addElement('text', 'title', get_string('decisiontree_questiontitle', 'decisiontree'), 'size="70"');
		        $mform->addRule('title', null, 'required', null, 'client');
		        $mform->setDefault('title', $question->title);
		        
		        // question text
		        $mform->addElement('htmleditor', 'text', get_string('decisiontree_questiontext', 'decisiontree'));
		        $mform->setType('text', PARAM_RAW);
				$mform->addRule('text', null, 'required', null, 'client');
				$mform->setDefault('text', $question->text);
				
				// is it the root question of the decision tree?
				$mform->addElement('checkbox', 'root', get_string('decisiontree_questionroot', 'decisiontree'));
				$mform->setDefault('root', $question->root);
				
				// number of answers
				$mform->addElement('text', 'numberofanswers', get_string('decisiontree_numberofanswers', 'decisiontree'), 'size="4"');
		        $mform->addRule('numberofanswers', null, 'required', null, 'client');
		        $mform->setDefault('numberofanswers', $question->numberofanswers);
		        
		        // Print the fields for answers
		        for($i=1; $i<=$question->numberofanswers; $i++){
		        	$mform->addElement('text', 'answer'.$i, get_string('decisiontree_answer', 'decisiontree')." ".$i, 'size="70"');
		        	$mform->setDefault('answer'.$i, $DB->get_field("decisiontree_answers", "answer", array("question" => $question->id, "position" => $i)));
		        }
		        
	        }else{ // just show the empty question fields
	        
		      	// question title
		        $mform->addElement('text', 'title', get_string('decisiontree_questiontitle', 'decisiontree'), 'size="70"');
		        $mform->addRule('title', null, 'required', null, 'client');
		        
		        // question text
		        $mform->addElement('htmleditor', 'text', get_string('decisiontree_questiontext', 'decisiontree'));
		        $mform->setType('text', PARAM_RAW);
				$mform->addRule('text', null, 'required', null, 'client');
				
				// is it the root question of the decision tree?
				$mform->addElement('checkbox', 'root', get_string('decisiontree_questionroot', 'decisiontree'));
				
				// number of answers
				$mform->addElement('text', 'numberofanswers', get_string('decisiontree_numberofanswers', 'decisiontree'), 'size="4"');
		        $mform->addRule('numberofanswers', null, 'required', null, 'client');
	        }
	        
	        
	        // buttons
	        $this->add_action_buttons($cancel = true, $submitlabel=null);
	        
///// hidden elements	        
	        // course module ID
        	$mform->addElement('hidden', 'id');
        	$mform->setType('id', PARAM_INT);
        	
        	// decision tree ID
        	$mform->addElement('hidden', 'decisiontree_id');
        	$mform->setType('decisiontree_id', PARAM_INT);
	    }    
    }
?>
