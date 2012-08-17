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
 * Define all the backup steps that will be used by the backup_decisiontree_activity_task
 */

/**
 * Define the complete decisiontree structure for backup, with file and id annotations
 */
class backup_decisiontree_activity_structure_step extends backup_activity_structure_step {

    protected function define_structure() {

        // Define each element separated
        $decisiontree = new backup_nested_element("decisiontree", array("id"), array("course", "name", "intro", "introformat", "timecreated", "timemodified"));
        $questions = new backup_nested_element("questions");
        $question = new backup_nested_element("question", array("id"), array("title", "text", "root", "numberofanswers", "decisiontree_id"));
        $answers = new backup_nested_element("answers");
        $answer = new backup_nested_element("answer", array("id"), array("question", "answer", "position"));
        $solutions = new backup_nested_element("solutions");
        $solution = new backup_nested_element("solution", array("id"), array("solutiontitle", "solution", "decisiontree_id"));
        $paths = new backup_nested_element("paths");
        $path = new backup_nested_element("path", array("id"), array("title", "path", "solution_id", "decisiontree_id"));
        
        // Build the tree
        $decisiontree->add_child($questions);
        $questions->add_child($question);
        $question->add_child($answers);
        $answers->add_child($answer);
        $decisiontree->add_child($solutions);
        $solutions->add_child($solution);
        $solution->add_child($paths);
        $paths->add_child($path);
        
        // Define sources
        $decisiontree->set_source_table("decisiontree", array("id" => backup::VAR_ACTIVITYID));
        $question->set_source_table("decisiontree_questions", array("decisiontree_id" => backup::VAR_ACTIVITYID));
        $answer->set_source_table("decisiontree_answers", array("question" => backup::VAR_PARENTID));
        $solution->set_source_table("decisiontree_solutions", array("decisiontree_id" => backup::VAR_ACTIVITYID));
        $path->set_source_table("decisiontree_paths", array("solution_id" => backup::VAR_PARENTID, "decisiontree_id" => backup::VAR_ACTIVITYID));
        
        // Define id annotations
        /* no need to annotate ids */

        // Define file annotations
        /* no need to annotate files */
        
        // Return the root element (decisiontree), wrapped into standard activity structure
        return $this->prepare_activity_structure($decisiontree);
    }
}
