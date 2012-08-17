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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/decisiontree/backup/moodle2/restore_decisiontree_stepslib.php'); // Because it exists (must)

/**
 * decisiontree restore task that provides all the settings and steps to perform one
 * complete restore of the activity
 */
class restore_decisiontree_activity_task extends restore_activity_task {

    /**
     * Define (add) particular settings this activity can have
     */
    protected function define_my_settings() {
        // No particular settings for this activity
    }

    /**
     * Define (add) particular steps this activity can have
     */
    protected function define_my_steps() {
        // Choice only has one structure step
        $this->add_step(new restore_decisiontree_activity_structure_step('decisiontree_structure', 'decisiontree.xml'));
    }

    /**
     * Define the contents in the activity that must be
     * processed by the link decoder
     */
    static public function define_decode_contents() {
        $contents = array();

        //$contents[] = new restore_decode_content('decisiontree', array('intro'), 'decisiontree');
        

        return $contents;
    }

    /**
     * Define the decoding rules for links belonging
     * to the activity to be executed by the link decoder
     */
    static public function define_decode_rules() {
        $rules = array();

        return $rules;

    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * decisiontree logs. It must return one array
     * of {@link restore_log_rule} objects
     */
    static public function define_restore_log_rules() {
        $rules = array();

        $rules[] = new restore_log_rule('decisiontree', 'add', 'view.php?id={course_module}', '{decisiontree}');
        $rules[] = new restore_log_rule('decisiontree', 'update', 'view.php?id={course_module}', '{decisiontree}');
        $rules[] = new restore_log_rule('decisiontree', 'view', 'view.php?id={course_module}', '{decisiontree}');
        $rules[] = new restore_log_rule('decisiontree', 'add category', 'editcategories.php?id={course_module}', '{decisiontree_category}');
        $rules[] = new restore_log_rule('decisiontree', 'edit category', 'editcategories.php?id={course_module}', '{decisiontree_category}');
        $rules[] = new restore_log_rule('decisiontree', 'delete category', 'editcategories.php?id={course_module}', '{decisiontree_category}');
        $rules[] = new restore_log_rule('decisiontree', 'add entry', 'view.php?id={course_module}&mode=entry&hook={decisiontree_entry}', '{decisiontree_entry}');
        $rules[] = new restore_log_rule('decisiontree', 'update entry', 'view.php?id={course_module}&mode=entry&hook={decisiontree_entry}', '{decisiontree_entry}');
        $rules[] = new restore_log_rule('decisiontree', 'delete entry', 'view.php?id={course_module}&mode=entry&hook={decisiontree_entry}', '{decisiontree_entry}');
        $rules[] = new restore_log_rule('decisiontree', 'approve entry', 'showentry.php?id={course_module}&eid={decisiontree_entry}', '{decisiontree_entry}');
        $rules[] = new restore_log_rule('decisiontree', 'view entry', 'showentry.php?eid={decisiontree_entry}', '{decisiontree_entry}');

        return $rules;
    }

    /**
     * Define the restore log rules that will be applied
     * by the {@link restore_logs_processor} when restoring
     * course logs. It must return one array
     * of {@link restore_log_rule} objects
     *
     * Note this rules are applied when restoring course logs
     * by the restore final task, but are defined here at
     * activity level. All them are rules not linked to any module instance (cmid = 0)
     */
    static public function define_restore_log_rules_for_course() {
        $rules = array();

        $rules[] = new restore_log_rule('decisiontree', 'view all', 'index.php?id={course}', null);

        return $rules;
    }
}
