<?php

/**
 * @package		DASIS - decision tree
 * @subpackage 	backup-moodle2
 * @author		Andre Scherl
 * @version		1.0 - 02.09.2011
 *
 * Copyright (C) 2011, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Define all the restore steps that will be used by the restore_decisiontree_activity_task
 */

/**
 * Structure step to restore one decisiontree activity
 */
class restore_decisiontree_activity_structure_step extends restore_activity_structure_step {

    protected function define_structure() {

        $paths = array();
        
        $paths[] = new restore_path_element("decisiontree", "/activity/decisiontree");
        $paths[] = new restore_path_element("decisiontree_question", "/activity/decisiontree/questions/question");
        $paths[] = new restore_path_element("decisiontree_answer", "/activity/decisiontree/questions/question/answers/answer");
        $paths[] = new restore_path_element("decisiontree_solution", "/activity/decisiontree/solutions/solution");
        $paths[] = new restore_path_element("decisiontree_path", "/activity/decisiontree/solutions/solution/paths/path");
        
        // Return the paths wrapped into standard activity structure
        return $this->prepare_activity_structure($paths);
    }

    protected function process_decisiontree($data) {
        global $DB;

        $data = (object)$data;
        $oldid = $data->id;
        $data->course = $this->get_courseid();
        $data->timecreated = $this->apply_date_offset($data->timecreated);
        $data->timemodified = $this->apply_date_offset($data->timemodified);
        
        $newid = $DB->insert_record("decisiontree", $data);
        $this->apply_activity_instance($newid);
    }
    
    protected function process_decisiontree_question($data) {
	    global $DB;

    	$data = (object)$data;
        $oldid = $data->id;
        
        $data->decisiontree_id = $this->get_new_parentid("decisiontree");
        $newid = $DB->insert_record("decisiontree_questions", $data);
        $this->set_mapping("decisiontree_question", $oldid, $newid);
    }
    
    protected function process_decisiontree_answer($data) {
    	global $DB;
    
    	$data = (object)$data;
        $oldid = $data->id;
        
        $data->question = $this->get_new_parentid("decisiontree_question");
        $DB->insert_record("decisiontree_answers", $data);
    }
    
    protected function process_decisiontree_solution($data) {
    	global $DB;
    	
    	$data = (object)$data;
        $oldid = $data->id;
        
        $data->decisiontree_id = $this->get_new_parentid("decisiontree");
        $newid = $DB->insert_record("decisiontree_solutions", $data);
        $this->set_mapping("decisiontree_solution", $oldid, $newid);
    }
    
    protected function process_decisiontree_path($data) {
    	global $DB;
    	
    	$data = (object)$data;
        $oldid = $data->id;
        
        $data->decisiontree_id = $this->get_new_parentid("decisiontree");
        $data->solution_id = $this->get_new_parentid("decisiontree_solution");
        $DB->insert_record("decisiontree_paths", $data);
    }


    protected function after_execute() {
       // nothing to do yet
    }
}
