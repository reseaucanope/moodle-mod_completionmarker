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
 * Lib functions
 *
 * @package   mod_completionmarker
 * @copyright  2021 Reseau-Canope
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

/**
 * @uses FEATURE_IDNUMBER
 * @uses FEATURE_GROUPS
 * @uses FEATURE_GROUPINGS
 * @uses FEATURE_MOD_INTRO
 * @uses FEATURE_COMPLETION_TRACKS_VIEWS
 * @uses FEATURE_GRADE_HAS_GRADE
 * @uses FEATURE_GRADE_OUTCOMES
 * @param string $feature FEATURE_xx constant for requested feature
 * @return bool|null True if module supports feature, false if not, null if doesn't know
 */
function completionmarker_supports($feature) {
    switch($feature) {
        case FEATURE_IDNUMBER:                return false;
        case FEATURE_GROUPS:                  return false;
        case FEATURE_GROUPINGS:               return false;
        case FEATURE_MOD_INTRO:               return false;
        case FEATURE_COMPLETION_TRACKS_VIEWS: return false;
        case FEATURE_GRADE_HAS_GRADE:         return false;
        case FEATURE_GRADE_OUTCOMES:          return false;
        case FEATURE_MOD_ARCHETYPE:           return MOD_ARCHETYPE_RESOURCE;
        case FEATURE_BACKUP_MOODLE2:          return true;
        case FEATURE_NO_VIEW_LINK:            return true;
        
        default: return null;
    }
}

function completionmarker_get_coursemodule_info($coursemodule) {
    global $DB;

    $section = $DB->get_record('course_sections',array('id' => $coursemodule->section));
    $info = new cached_cm_info();
    $info->content = $section->summary;
    return $info;
}

function completionmarker_add_instance($data, $mform) {
    global $DB;

    $data->timemodified = time();
    $data->intro = '';
    $data->id = $DB->insert_record('completionmarker', $data);

    if (!(isset($data->completionunlocked) && $data->completionunlocked)) {
        // completion is enabled by default with this mod (it doesn't really make sense if it's disabled when course completion is is enabled back)
        $DB->set_field('course_modules', 'completion', 1, array('id' => $data->coursemodule));
    }

    return $data->id;
}

function completionmarker_update_instance($data, $mform) {
    global $DB;

    $data->id = $data->instance;
    $data->intro = '';
    $data->introformat = 1;
    $data->timemodified = time();

    $DB->update_record('completionmarker', $data);

    return true;
}

function completionmarker_delete_instance($id) {
    global $DB;
    
    $DB->delete_records('completionmarker', array('id'=>$id));
    
    return true;
}


function completionmarker_cm_info_view(cm_info $cm){
    global $PAGE;

    $renderer = $PAGE->get_renderer('mod_completionmarker');

    $cm->set_content($renderer->custom_content($cm));

}