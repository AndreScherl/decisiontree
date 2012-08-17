<?php //$Id: mod_form.php,v 1.2.2.3 2009/03/19 12:23:11 mudrd8mz Exp $

/**
 * This file defines the main decisiontree configuration form
 * It uses the standard core Moodle (>1.8) formslib. For
 * more info about them, please visit:
 *
 * http://docs.moodle.org/en/Development:lib/formslib.php
 *
 * The form must provide support for, at least these fields:
 *   - name: text element of 64cc max
 *
 * Also, it's usual to use these fields:
 *   - intro: one htmlarea element to describe the activity
 *            (will be showed in the list of activities of
 *             decisiontree type (index.php) and in the header
 *             of the decisiontree main page (view.php).
 *   - introformat: The format used to write the contents
 *             of the intro field. It automatically defaults
 *             to HTML when the htmleditor is used and can be
 *             manually selected if the htmleditor is not used
 *             (standard formats are: MOODLE, HTML, PLAIN, MARKDOWN)
 *             See lib/weblib.php Constants and the format_text()
 *             function for more info
 *
 * Copyright (C) 2011, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */

///// Hierfür gibt es sicher auch noch eine sauberer Lösung 
	echo "<script type=\"text/javascript\" src=\"$CFG->wwwroot/mod/decisiontree/script.js\"></script>";

require_once($CFG->dirroot.'/course/moodleform_mod.php');

class mod_decisiontree_mod_form extends moodleform_mod {

    function definition() {

        global $COURSE;
        global $CFG;
        global $DB;
        
        $mform =& $this->_form;

//-------------------------------------------------------------------------------
    /// Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));

    /// Adding the standard "name" field
        $mform->addElement('text', 'name', get_string('decisiontreename', 'decisiontree'), array('size'=>'64'));
        $mform->setType('name', PARAM_TEXT);
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');

    /// Adding the required "intro" field to hold the description of the instance
        $mform->addElement('htmleditor', 'intro', get_string('decisiontreeintro', 'decisiontree'));
        $mform->setType('intro', PARAM_RAW);
       // $mform->addRule('intro', get_string('required'), 'required', null, 'client');
        $mform->setHelpButton('intro', array('writing', 'richtext'), false, 'editorhelpbutton');

    /// Adding "introformat" field
        $this->add_intro_editor();

//-------------------------------------------------------------------------------
    /// Adding the rest of decisiontree settings, spreeading all them into this fieldset
    /// or adding more fieldsets ('header' elements) if needed for better logic
       // $mform->addElement('static', 'label1', 'decisiontreesetting1', 'Your decisiontree fields go here. Replace me!');

       // $mform->addElement('header', 'decisiontreefieldset', get_string('decisiontreefieldset', 'decisiontree'));
       // $mform->addElement('static', 'label2', 'decisiontreesetting2', 'Your decisiontree fields go here. Replace me!');
       
//--------------------------------------------------------------------------------
	/// Custom Controls for Decistion Tree
		// add a button to edit paths
		$cmid = optional_param('update', 0, PARAM_INT); // get the decision tree course module id
	
		if($cmid) {
			if(! $coursemodule = $DB->get_record("course_modules", array("id" => $cmid))) {
				error("No Decision Tree Found");
			}
			$mform->addElement('header', 'pathheader', get_string('decisiontree_pathandsolution', 'decisiontree'));
			$mform->addElement('button', 'editpath', get_string('decisiontree_editpath', 'decisiontree'), "onclick=\"editPath('$CFG->wwwroot',$coursemodule->instance)\"");
			$mform->addElement('button', 'editquestions', get_string('decisiontree_questionsedit', 'decisiontree'), "onclick=\"editQuestion('$CFG->wwwroot',$coursemodule->instance)\"");
		} 

//-------------------------------------------------------------------------------
        // add standard elements, common to all modules
        $this->standard_coursemodule_elements();
//-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
        $this->add_action_buttons();

    }
}

?>