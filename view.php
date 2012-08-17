<?php 
/**
 * This page prints a particular instance of decisiontree
 * 
 * @author 	Andre Scherl
 * @version 1.1 - 14.03.2011
 * @package decisiontree
 *
 * Copyright (C) 2011, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */

    require_once("../../config.php");
    require_once("lib.php");

	global $DB, $OUTPUT; 

    $id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
    $a  = optional_param('a', 0, PARAM_INT);  // decisiontree ID

    if ($id) {
        if (! $cm = $DB->get_record("course_modules", array("id" => $id))) {
            error("Course Module ID was incorrect");
        }
    
        if (! $course = $DB->get_record("course", array("id" => $cm->course))) {
            error("Course is misconfigured");
        }
    
        if (! $decisiontree = $DB->get_record("decisiontree", array("id" => $cm->instance))) {
            error("Course module is incorrect");
        }

    } else {
        if (! $decisiontree = $DB->get_record("decisiontree", array("id" => $a))) {
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
	
	$OUTPUT->box_start();
	
    if($DB->record_exists('decisiontree', array('id' => $decisiontree->id))){
    	print($DB->get_field('decisiontree', 'intro', array('id' => $decisiontree->id)));
    	$start = "<br /><div style=\"text-align: center;\"><a href=./view_question.php?did=".$decisiontree->id.">TEST STARTEN</a></div>";
    	format_text($start, $format=FORMAT_HTML, $options=NULL, $courseid=$course->id );
    	print $start;
    }else{
    	p('Error in database: no content for choosen id');
    }

    $OUTPUT->box_end();

/// Finish the page
    echo $OUTPUT->footer();

?>
