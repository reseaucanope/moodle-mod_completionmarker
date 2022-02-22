<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * List of completionmarkers in course
 *
 * @package   mod_completionmarker
 * @copyright 2021 Reseau-Canope
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');

$id = required_param('id', PARAM_INT);

$course = $DB->get_record('course', array('id'=>$id), '*', MUST_EXIST);

require_course_login($course, true);
$PAGE->set_pagelayout('incourse');

$params = array(
    'context' => context_course::instance($course->id)
);

$strachievement  = get_string('modulename', 'achievement');
$strachievements = get_string('modulenameplural', 'achievement');
$strname         = get_string('name');
$strintro        = get_string('moduleintro');
$strlastmodified = get_string('lastmodified');

$PAGE->set_achievement('/mod/achievement/index.php', array('id' => $course->id));
$PAGE->set_title($course->shortname.': '.$strachievements);
$PAGE->set_heading($course->fullname);
$PAGE->navbar->add($strachievements);
echo $OUTPUT->header();
echo $OUTPUT->heading($strachievements);

if (!$achievements = get_all_instances_in_course('achievement', $course)) {
    notice(get_string('thereareno', 'moodle', $strachievements), "$CFG->wwwroot/course/view.php?id=$course->id");
    exit;
}

$usesections = course_format_uses_sections($course->format);

$table = new html_table();
$table->attributes['class'] = 'generaltable mod_index';

if ($usesections) {
    $strsectionname = get_string('sectionname', 'format_'.$course->format);
    $table->head  = array ($strsectionname, $strname, $strintro);
    $table->align = array ('center', 'left', 'left');
} else {
    $table->head  = array ($strlastmodified, $strname, $strintro);
    $table->align = array ('left', 'left', 'left');
}

$modinfo = get_fast_modinfo($course);
$currentsection = '';
foreach ($achievements as $achievement) {
    $cm = $modinfo->cms[$achievement->coursemodule];
    if ($usesections) {
        $printsection = '';
        if ($achievement->section !== $currentsection) {
            if ($achievement->section) {
                $printsection = get_section_name($course, $achievement->section);
            }
            if ($currentsection !== '') {
                $table->data[] = 'hr';
            }
            $currentsection = $achievement->section;
        }
    } else {
        $printsection = '<span class="smallinfo">'.userdate($achievement->timemodified)."</span>";
    }

    $extra = empty($cm->extra) ? '' : $cm->extra;
    $icon = '';
    if (!empty($cm->icon)) {
        $icon = '<img src="'.$OUTPUT->pix_achievement($cm->icon).'" class="activityicon" alt="'.get_string('modulename', $cm->modname).'" /> ';
    }

    $class = $achievement->visible ? '' : 'class="dimmed"'; // hidden modules are dimmed
    $table->data[] = array (
        $printsection,
        "<a $class $extra href=\"view.php?id=$cm->id\">".$icon.format_string($achievement->name)."</a>",
        format_module_intro('achievement', $achievement, $cm->id));
}

echo html_writer::table($table);

echo $OUTPUT->footer();
