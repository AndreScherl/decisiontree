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

require_once($CFG->dirroot . '/mod/decisiontree/backup/moodle2/backup_decisiontree_stepslib.php');

/**
 * decisiontree backup task that provides all the settings and steps to perform one
 * complete backup of the activity
 */
class backup_decisiontree_activity_task extends backup_activity_task {

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
        $this->add_step(new backup_decisiontree_activity_structure_step('decisiontree_structure', 'decisiontree.xml'));
    }

    /**
     * Code the transformations to perform in the activity in
     * order to get transportable (encoded) links
     */
    static public function encode_content_links($content) {
        // vorerst nichts
    	return $content;
    }
}
