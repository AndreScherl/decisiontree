<?php  // $Id: lib.php,v 1.4 2006/08/28 16:41:20 mark-nielsen Exp $
/**
 * Library of functions and constants for module decisiontree
 *
 * @author 
 * @version $Id: lib.php,v 1.4 2006/08/28 16:41:20 mark-nielsen Exp $
 * @package decisiontree
 *
 * Copyright (C) 2011, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */


$decisiontree_CONSTANT = 7;     /// for example

/**
 * Given an object containing all the necessary data, 
 * (defined by the form in mod.html) this function 
 * will create a new instance and return the id number 
 * of the new instance.
 *
 * @param object $instance An object from the form in mod.html
 * @return int The id of the newly inserted decisiontree record
 **/
function decisiontree_add_instance($decisiontree) {
    global $DB;
    
    $decisiontree->timemodified = time();

    # May have to add extra stuff in here #
    
    return $DB->insert_record("decisiontree", $decisiontree);
}

/**
 * Given an object containing all the necessary data, 
 * (defined by the form in mod.html) this function 
 * will update an existing instance with new data.
 *
 * @param object $instance An object from the form in mod.html
 * @return boolean Success/Fail
 **/
function decisiontree_update_instance($decisiontree) {
	global $DB;
	
    $decisiontree->timemodified = time();
    $decisiontree->id = $decisiontree->instance;

    # May have to add extra stuff in here #

    return $DB->update_record("decisiontree", $decisiontree);
}

/**
 * Given an ID of an instance of this module, 
 * this function will permanently delete the instance 
 * and any data that depends on it. 
 *
 * @param int $id Id of the module instance
 * @return boolean Success/Failure
 **/
function decisiontree_delete_instance($id) {
	global $DB;
	
    if (! $decisiontree = $DB->get_record("decisiontree", array("id"=>$id))) {
        return false;
    }

    $result = true;

    # Delete any dependent records here #

    if (! $DB->delete_records("decisiontree", array("id" => $decisiontree->id))) {
        $result = false;
    }

    return $result;
}

/**
 * Return a small object with summary information about what a 
 * user has done with a given particular instance of this module
 * Used for user activity reports.
 * $return->time = the time they did it
 * $return->info = a short text description
 *
 * @return null
 * @todo Finish documenting this function
 **/
function decisiontree_user_outline($course, $user, $mod, $decisiontree) {
    return $return;
}

/**
 * Print a detailed representation of what a user has done with 
 * a given particular instance of this module, for user activity reports.
 *
 * @return boolean
 * @todo Finish documenting this function
 **/
function decisiontree_user_complete($course, $user, $mod, $decisiontree) {
    return true;
}

/**
 * Given a course and a time, this module should find recent activity 
 * that has occurred in decisiontree activities and print it out. 
 * Return true if there was output, or false is there was none. 
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function decisiontree_print_recent_activity($course, $isteacher, $timestart) {
    global $CFG;

    return false;  //  True if anything was printed, otherwise false 
}

/**
 * Function to be run periodically according to the moodle cron
 * This function searches for things that need to be done, such 
 * as sending out mail, toggling flags etc ... 
 *
 * @uses $CFG
 * @return boolean
 * @todo Finish documenting this function
 **/
function decisiontree_cron () {
    global $CFG;

    return true;
}

/**
 * Must return an array of grades for a given instance of this module, 
 * indexed by user.  It also returns a maximum allowed grade.
 * 
 * Example:
 *    $return->grades = array of grades;
 *    $return->maxgrade = maximum allowed grade;
 *
 *    return $return;
 *
 * @param int $decisiontreeid ID of an instance of this module
 * @return mixed Null or object with an array of grades and with the maximum grade
 **/
function decisiontree_grades($decisiontreeid) {
   return NULL;
}

/**
 * Must return an array of user records (all data) who are participants
 * for a given instance of decisiontree. Must include every user involved
 * in the instance, independient of his role (student, teacher, admin...)
 * See other modules as example.
 *
 * @param int $decisiontreeid ID of an instance of this module
 * @return mixed boolean/array of students
 **/
function decisiontree_get_participants($decisiontreeid) {
    return false;
}

/**
 * This function returns if a scale is being used by one decisiontree
 * it it has support for grading and scales. Commented code should be
 * modified if necessary. See forum, glossary or journal modules
 * as reference.
 *
 * @param int $decisiontreeid ID of an instance of this module
 * @return mixed
 * @todo Finish documenting this function
 **/
function decisiontree_scale_used ($decisiontreeid,$scaleid) {
    $return = false;

    //$rec = get_record("decisiontree","id","$decisiontreeid","scale","-$scaleid");
    //
    //if (!empty($rec)  && !empty($scaleid)) {
    //    $return = true;
    //}
   
    return $return;
}

//////////////////////////////////////////////////////////////////////////////////////
/// Any other decisiontree functions go here.  Each of them must have a name that 
/// starts with decisiontree_

function decisiontree_supports($feature) {
    switch($feature) {
        case FEATURE_BACKUP_MOODLE2:
        	return true;

        default: return null;
    }
}

?>
