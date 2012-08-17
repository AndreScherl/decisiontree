<?php 
/*	
 * Copyright (C) 2011, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */
	require_once("../../config.php");
    require_once("lib.php");
    
    global $DB;

	// Die Funktion serialisiert den Pfad des Users, um ihn mit den Pfaden der Datenbank zu vergleichen.
	function serialize_path($path, $separator) {
		foreach($path as $pathitem) {
			if($pathstring) {
				$pathstring = $pathstring.$separator.$pathitem;
			}else{
				$pathstring = $pathitem;
			}
		}
		return $pathstring;
	}
	
	// Die Funktion liefert den Titel der nächsten Frage entlang des Pfades zurück.
	function get_next_question($userpath, $databasepath, $delimiter) {
		$userpatharray = explode($delimiter, $userpath);
		$databasepatharray = explode($delimiter, $databasepath);
		
		$i = count($userpatharray);
		
		$question = explode(":", $databasepatharray[$i]);
		
		return $question[0];
	}
	
	function get_solution($path) {
		global $DB;
		$rec = $DB->get_record("decisiontree_paths", array("path" => $path));
		return $rec->solution_id;
	}
	
	
///// Berechnung der nächsten Frage

	if($SESSION->userpath) {
    	$userpath = $SESSION->userpath;
    	array_push($userpath, $_POST['title'].":".$_POST['answer']);
    }else{
    	$userpath = array();
    	$userpath[0] = $_POST['title'].":".$_POST['answer'];
    }
    $SESSION->userpath = $userpath;
    
    $serializedpath = serialize_path($userpath, "-");
   	$possible_path = current($DB->get_records_sql("SELECT path FROM {decisiontree_paths} WHERE path LIKE '%$serializedpath%' LIMIT 1"));
   	
   	if($serializedpath != $possible_path->path) {
   		$nextquestiontitle = get_next_question($serializedpath, $possible_path->path, "-");
   		$rec = $DB->get_record("decisiontree_questions", array("title" => $nextquestiontitle, "decisiontree_id" => $_POST['decisiontree_id']));
		redirect("view_question.php?id={$rec->id}");
   	}else{
   		redirect("view_solution.php?id=".get_solution($serializedpath));
   	}
   	
   	
?>