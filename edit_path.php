<?php   // $Id: edit_path.php,v 1.0 2010/05/28 09:26:00
/**
 * This page prints a particular instance of decisiontree
 * 
 * @author 	Andre Scherl
 * @version $Id: edit_path.php,v 1.0 2010/05/28 09:26:00
 * @package decisiontree
 *
 * Copyright (C) 2011, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */

    require_once("../../config.php");
    require_once("lib.php");
	
	global $DB;
	
    $id = optional_param('id', 0, PARAM_INT); // Path ID
    $did  = optional_param('did', 0, PARAM_INT);  // Decision Tree ID
    $sid = optional_param('sid', -1, PARAM_INT); // Solution ID

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
	require_once 'edit_path_form.php';

    if($path->id){
    	if($path->solution_id >= 0) {
    		$form = new edit_path_form("edit_path.php?id=$path->id&sid=$path->solution_id");
    	}else{
    		$form = new edit_path_form("edit_path.php?id=$path->id");
    	}
    }else{
    	$form = new edit_path_form("edit_path.php?did=$decisiontree->id");
    }
    
    $form->set_data(array(	'decisiontree_id'=>$decisiontree->id,
    						'solution_id'=>$path->solution_id));
   	$form->display();
   	
/// Database actions
   	// Insert/update the table decisiontree_paths by filling in the path data
   	
    $pathdata = new object();
    $pathdata = $form->get_data();
  
    if($pathdata && ($path->title != $pathdata->title || $path->path != $pathdata->path || $pathdata->solutionselect)) {
    	// create a path data object because the pathdata data object holds also informations of solution. so update_record needs an identical data object to table structure
    	$path_dataobject = new object();
    	$path_dataobject->id = $path->id;
    	$path_dataobject->title = $pathdata->title;
    	$path_dataobject->path = $pathdata->path;
    	$path_dataobject->solution_id = $pathdata->solution_id;
    	$path_dataobject->decisiontree_id = $decisiontree->id;
    	
	    if($DB->record_exists("decisiontree_paths", array("id" => $path->id))){
	    	$DB->update_record("decisiontree_paths", $path_dataobject);
	    }else{
	    	$id = $DB->insert_record("decisiontree_paths", $path_dataobject, $returnid=true, $primarykey='id');
	    }
    }
    
    // Insert/update the table decisiontree_solutions by filling in the solution data
   	if($path->solution_id) {
		$solution = $DB->get_record("decisiontree_solutions", array("id" => $path->solution_id));
   	}
  
    if($pathdata && ($solution->solutiontitle != $pathdata->solutiontitle || $solution->solution != $pathdata->solution)) {
    	// look to if-loops upwards for reason of creating solution data object
    	$solution_dataobject = new object();
    	$solution_dataobject->id = $solution->id;
    	$solution_dataobject->solutiontitle = $pathdata->solutiontitle;
    	$solution_dataobject->solution = $pathdata->solution;
    	$solution_dataobject->decisiontree_id = $decisiontree->id;
    	
	    if($DB->record_exists("decisiontree_solutions", array("id" => $solution->id))){
	    	$DB->update_record("decisiontree_solutions", $solution_dataobject);
	    }else{
	    	$path->solution_id = $DB->insert_record("decisiontree_solutions", $solution_dataobject, $returnid=true, $primarykey='id');
	    	$DB->set_field("decisiontree_paths", "solution_id", $path->solution_id, array("id" => $path->id));
	    }
    }
    
    if ($pathdata) {
    	redirect("edit_path.php?id=$id");
    }
    
/// Finish the page
    //print_footer($course);
	echo $OUTPUT->footer();
?>
