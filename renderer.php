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
 * Completionmarker renderer
 *
 * @package   mod_completionmarker
 * @copyright 2021 Reseau-Canope
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class mod_completionmarker_renderer extends plugin_renderer_base{

    public function custom_content($cm){
        global $DB, $USER, $PAGE;

        $course = get_course($cm->course);
        $completion = new completion_info($course);

        if(!$completion->is_enabled()){
            return '<button class="btn btn-warning">'.get_string('errorcompletionnotenable', 'completionmarker').'</button>';
        }

        $completion = $DB->get_record('course_modules_completion', array('coursemoduleid' => $cm->id, 'userid'=>$USER->id));

        $label_completed = '<i class=\'fa fa-check-square completionmarker-icon\' aria-hidden=\'true\'></i> '.get_string('completed', 'completionmarker');
        $label_uncompleted = '<i class=\'fa fa-square completionmarker-icon\' aria-hidden=\'true\'></i> '. get_string('mark', 'completionmarker');

        if($completion && $completion->completionstate == 1){
            $label = $label_completed;
        }else{
            $label = $label_uncompleted;
        }

        $button = '<a href="#" class="mark">'.$label.'</a>';

        $PAGE->requires->js_call_amd('mod_completionmarker/completionmarker', 'init', array($label_completed, $label_uncompleted));

        return $button;
    }
}