/* 
 * Copyright (C) 2011, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */
function objectSelected(element, path, did) {
	var id = document.getElementsByName(element)[0].value;
	
	if(id > 0){
		window.location.href=path+"?id="+id;
	}else{
		window.location.href=path+"?did="+did;
	}	
}

function solutionSelected(id) {
	var sid = document.getElementsByName('solutionselect')[0].value;
	
	window.location.href="./edit_path.php?id="+id+"&sid="+sid;
}

function editPath(wwwroot, did) {
	window.location.href=wwwroot+"/mod/decisiontree/edit_path.php?did="+did;
}

function editQuestion(wwwroot, did) {
	window.location.href=wwwroot+"/mod/decisiontree/edit_question.php?did="+did;
}