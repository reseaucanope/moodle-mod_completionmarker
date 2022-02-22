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
 * Plugin upgrade script
 *
 * @package   mod_completionmarker
 * @copyright 2021 Reseau-Canope
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die;

function xmldb_completionmarker_upgrade($oldversion) {
    global $DB;

    $dbman = $DB->get_manager();

    if($oldversion < 2019121200){
        $table = new xmldb_table('completionmarker');

        $newField = new xmldb_field('timemodified', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '0');

        if (!$dbman->field_exists($table, $newField)) {
            $dbman->add_field($table, $newField);
        }

        upgrade_mod_savepoint(true, 2019121200, 'completionmarker');
    }


    return true;
}
