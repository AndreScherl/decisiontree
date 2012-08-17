<?php

/**
 * This file keeps track of upgrades to the newmodule module
 *
 * Sometimes, changes between versions involve alterations to database
 * structures and other major things that may break installations. The upgrade
 * function in this file will attempt to perform all the necessary actions to
 * upgrade your older installation to the current version. If there's something
 * it cannot do itself, it will tell you what you need to do.  The commands in
 * here will all be database-neutral, using the functions defined in DLL libraries.
 *
 * @package   DASIS -> decision tree
 * @copyright 2010 Andre Scherl
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * xmldb_newmodule_upgrade
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_decisiontree_upgrade($oldversion) {

    global $DB;

    $dbman = $DB->get_manager(); // loads ddl manager and xmldb classes

    if ($oldversion < 2011080400) {
    	$table = new xmldb_table('decisiontree_paths');
    	$field = new xmldb_field('path', XMLDB_TYPE_CHAR, $precision=null, $unsigned=null, $notnull=false, $sequence=false, $default=null, $previous='title');
    	$field->setLength(255);
		$dbman->change_field_type($table, $field);
    }


    return true;
}
