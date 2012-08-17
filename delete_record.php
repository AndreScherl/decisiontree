<?php  // $Id: edit_answer.php,v 1.0 2010/05/28 09:26:00
/**
 * This page prints a particular instance of decisiontree
 * 
 * @author 	Andre Scherl
 * @version $Id: edit_answer.php,v 1.0 2010/05/28 09:26:00
 * @package decisiontree
 *
 * Copyright (C) 2011, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */
	require_once("../../config.php");
    require_once("lib.php");
	
	global $DB;
	
    $id = optional_param('id', 0, PARAM_INT); // Course Module ID, or
    $table = optional_param('table', 0, PARAM_TEXT);  // decisiontree ID
    $did = optional_param('did', 0, PARAM_INT);

    if($id && $table && $did) {
    	echo "delete... ";
    	$DB->delete_records($table, array("id" => $id));
	    if($table == "decisiontree_questions") {
	    	$DB->delete_records("decisiontree_answers", array("question" => $id));
	    }
    	echo "Okay";
    }else{
    	error("Missing parameter");
    }
    
    if($table == "decisiontree_questions" || $table == "decisiontree_answers") {
    	redirect('edit_question.php?did='.$did);
    }else{
    	redirect('edit_path.php?did='.$did);
    }
    
?>
