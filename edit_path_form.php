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
	
	class edit_path_form extends moodleform {
    
 	   function definition() {
		
		global $DB, $CFG;
		
	    $id = optional_param('id', 0, PARAM_INT); // Path ID
	    $did = optional_param('did', 0, PARAM_INT);  // Decision Tree ID
	    $sid = optional_param('sid', -1, PARAM_INT);  // Solution ID
	
	    if ($id) {
	
	    	if (! $path = $DB->get_record("decisiontree_paths", array("id" => $id))) {
	            error("Path ID was incorrect");
	        }
	    	
	    	if (! $decisiontree = $DB->get_record("decisiontree", array("id" => $path->decisiontree_id))) {
	            error("Decision Tree ID stored in decisiontree_paths is incorrect");
	        }
	        
	        if (! $course = $DB->get_record("course", array("id" => $decisiontree->course))) {
	            error("Course ID stored in decisiontree is incorrect");
	        }
	    	
	    	if (! $cm = get_coursemodule_from_instance("decisiontree", $decisiontree->id, $course->id)) {
	            error("Course Module ID was incorrect");
	        }
	    } else {
	        if (! $decisiontree = $DB->get_record("decisiontree", array("id" => $did))) {
	            error("Decision Tree ID is incorrect");
	        }
	        if (! $course = $DB->get_record("course", array("id" => $decisiontree->course))) {
	            error("Course is misconfigured");
	        }
	        if (! $cm = get_coursemodule_from_instance("decisiontree", $decisiontree->id, $course->id)) {
	            error("Course Module ID was incorrect");
	        }
	    }
	    
 	   if($sid >= 0){
	   		$path->solution_id = $sid;	
	   }
	    
 		$mform =& $this->_form;
	 		
 		$mform->addElement('header', 'pathheader', get_string('decisiontree_pathpreferences', 'decisiontree'));
	 		
       	// choose the path to be edited
       	$paths = $DB->get_records_menu("decisiontree_paths",  array("decisiontree_id" => $decisiontree->id), "title");
       	$paths[0] = get_string("decisiontree_pathnew", "decisiontree");
       	ksort($paths);
   		
       	$mform->addElement('select', 'pathselect', get_string('decisiontree_pathtoedit', 'decisiontree'), $paths, "onchange=\"objectSelected('pathselect', './edit_path.php', $decisiontree->id)\"");
       	$mform->setDefault('pathselect', $id);
       	$mform->addElement('html', '<div class="fitemtitle"></div><div class="felement"><a href=delete_record.php?table=decisiontree_paths&id='.$id.'&did='.$decisiontree->id.'>'.get_string('decisiontree_pathdelete', 'decisiontree').'</a></div>');
       	
        // if the path title exists show prefilled fields etc.
       	if($path->title) {
        	
        	// path title
	        $mform->addElement('text', 'title', get_string('decisiontree_pathtitle', 'decisiontree'), 'size="70"');
	        $mform->addRule('title', null, 'required', null, 'client');
	        $mform->setDefault('title', $path->title);
       	
       		// path
	        $mform->addElement('text', 'path', get_string('decisiontree_path', 'decisiontree'), 'size="70"');
	        $mform->addRule('path', null, 'required', null, 'client');
	        $mform->setDefault('path', $path->path);
	        
	        // solution for path
	        // choose the solution to be edited
	        $mform->addElement('header', 'solutionheader', get_string('decisiontree_solutionpreferences', 'decisiontree'));
	        
	        // get the solution out of database by path's solution_id
	        if($path->solution_id) {
	        	$solution = $DB->get_record("decisiontree_solutions", array("id" => $path->solution_id));
	        }

	        // get an array of solutions of the decision tree
	        /*$solutionssql = "SELECT {decisiontree_paths}.solution_id, {decisiontree_solutions}.solutiontitle
	        					FROM {decisiontree_solutions}, {decisiontree_paths}
	        					WHERE {decisiontree_paths}.decisiontree_id = $decisiontree->id
	        							AND {decisiontree_solutions}.id = {decisiontree_paths}.solution_id
	        							ORDER BY {decisiontree_solutions}.solutiontitle";*/
	       	
	        $solutionssql = "SELECT id, solutiontitle 
	       						FROM {decisiontree_solutions}
	       						WHERE decisiontree_id = $decisiontree->id
	       						ORDER BY solutiontitle";
        	$solutionsarray = $DB->get_records_sql($solutionssql);
        	$solutions[0] = get_string("decisiontree_solutionnew", "decisiontree");
        	foreach($solutionsarray as $solutionitem) {
        		$solutions[$solutionitem->id] = $solutionitem->solutiontitle;
        	}
        	
        	$mform->addElement('select', 'solutionselect', get_string('decisiontree_solutiontoedit', 'decisiontree'), $solutions, "onchange=\"solutionSelected($path->id)\"");
        	$mform->setDefault('solutionselect', $solution->id);
        	$mform->addElement('html', '<div class="fitemtitle"></div><div class="felement"><a href=delete_record.php?table=decisiontree_solutions&id='.$path->solution_id.'&did='.$decisiontree->id.'>'.get_string('decisiontree_solutiondelete', 'decisiontree').'</a></div>');
       		
        	// solution title
	        $mform->addElement('text', 'solutiontitle', get_string('decisiontree_solutiontitle', 'decisiontree'), 'size=70"');
	        $mform->addRule('solutiontitle', null, 'required', null, 'client');
	        $mform->setDefault('solutiontitle', $solution->solutiontitle);
	        
	        // solution text via html editor
	        $options = array(
							    'canUseHtmlEditor'=>'detect',
							    'rows'  => 10, 
							    'cols'  => 100, 
							    'width' => 0,
							    'height'=> 0, 
							    'course'=> 0,
							);
			$mform->addElement('htmleditor', 'solution', get_string('decisiontree_solutiontoedit', 'decisiontree'));
			$mform->addRule('solution', null, 'required', null, 'client');
	        $mform->setDefault('solution', $solution->solution);
	        
        }else{ // just show the empty fields
        
        	// path title
	        $mform->addElement('text', 'title', get_string('decisiontree_pathtitle', 'decisiontree'), 'size="70"');
	        $mform->addRule('title', null, 'required', null, 'client');
	        
	      	// path
	        $mform->addElement('text', 'path', get_string('decisiontree_path', 'decisiontree'), 'size="100"');
	        $mform->addRule('path', null, 'required', null, 'client');
        }
        
        $mform->closeHeaderBefore('id');
        
        
        // buttons
        $this->add_action_buttons($cancel = true, $submitlabel=null);
        
///// hidden elements	        
	                	
       	// decision tree ID
       	$mform->addElement('hidden', 'decisiontree_id');
       	$mform->setType('decisiontree_id', PARAM_INT);
       	
       	// solution ID of path object
       	$mform->addElement('hidden', 'solution_id');
       	$mform->setType('solution_id', PARAM_INT);
       	
   		}    
	}
?>
