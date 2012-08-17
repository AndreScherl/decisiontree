<?php // $Id: index.php,v 1.5 2006/08/28 16:41:20 mark-nielsen Exp $
/**
 * This page lists all the instances of decisiontree in a particular course
 *
 * @author 
 * @version $Id: index.php,v 1.5 2006/08/28 16:41:20 mark-nielsen Exp $
 * @package decisiontree
 *
 * Copyright (C) 2011, Andre Scherl
 * You should have received a copy of the GNU General Public License
 * along with DASIS.  If not, see <http://www.gnu.org/licenses/>.
 */

/// Replace decisiontree with the name of your module

    require_once("../../config.php");
    require_once("lib.php");
    
    global $DB;

    $id = required_param('id', PARAM_INT);   // course

    if (! $course = $DB->get_record("course", array("id" => $id))) {
        error("Course ID is incorrect");
    }

    require_login($course->id);

    add_to_log($course->id, "decisiontree", "view all", "index.php?id=$course->id", "");


/// Get all required strings

    $strdecisiontrees = get_string("modulenameplural", "decisiontree");
    $strdecisiontree  = get_string("modulename", "decisiontree");


/// Print the header

    if ($course->category) {
        $navigation = "<a href=\"../../course/view.php?id=$course->id\">$course->shortname</a> ->";
    } else {
        $navigation = '';
    }

    print_header("$course->shortname: $strdecisiontrees", "$course->fullname", "$navigation $strdecisiontrees", "", "", true, "", navmenu($course));

/// Get all the appropriate data

    if (! $decisiontrees = get_all_instances_in_course("decisiontree", $course)) {
        notice("There are no decisiontrees", "../../course/view.php?id=$course->id");
        die;
    }

/// Print the list of instances (your module will probably extend this)

    $timenow = time();
    $strname  = get_string("name");
    $strweek  = get_string("week");
    $strtopic  = get_string("topic");

    if ($course->format == "weeks") {
        $table->head  = array ($strweek, $strname);
        $table->align = array ("center", "left");
    } else if ($course->format == "topics") {
        $table->head  = array ($strtopic, $strname);
        $table->align = array ("center", "left", "left", "left");
    } else {
        $table->head  = array ($strname);
        $table->align = array ("left", "left", "left");
    }

    foreach ($decisiontrees as $decisiontree) {
        if (!$decisiontree->visible) {
            //Show dimmed if the mod is hidden
            $link = "<a class=\"dimmed\" href=\"view.php?id=$decisiontree->coursemodule\">$decisiontree->name</a>";
        } else {
            //Show normal if the mod is visible
            $link = "<a href=\"view.php?id=$decisiontree->coursemodule\">$decisiontree->name</a>";
        }

        if ($course->format == "weeks" or $course->format == "topics") {
            $table->data[] = array ($decisiontree->section, $link);
        } else {
            $table->data[] = array ($link);
        }
    }

    echo "<br />";

    print_table($table);

/// Finish the page

    print_footer($course);

?>
