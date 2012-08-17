<?php 
/* 
 * Copyright (C) 2011, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */
	header('content-type: text/html; charset=utf-8');
	require_once("../../config.php");
    require_once("lib.php");
	
	$fragen = mysql_query("SELECT * FROM mdl_decisiontree_questions WHERE decisiontree_id =".$_GET['did']);
	while($frage = mysql_fetch_assoc($fragen)) {
		echo "<br />".$frage['title']."<br />";
		echo $frage['text'];
		
		$antworten = mysql_query("SELECT * FROM mdl_decisiontree_answers WHERE question = ".$frage['id']." ORDER BY position");
		while($antwort = mysql_fetch_assoc($antworten)) {
			echo "<br />".$antwort['position'].". ".$antwort['answer'];
		}
	echo "<br />";
	}

?>