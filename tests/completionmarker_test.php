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
 * Basic unit tests for mod_completionmarker
 *
 * @package    mod_completionmarker
 * @category   test
 * @copyright  2021 Reseau-Canope
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Class completionmarker_testcase.
 *
 * @package    mod_completionmarker
 * @category   test
 * @copyright  2021 Reseau-Canope
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class completionmarker_testcase extends advanced_testcase {

    /** @var stdClass $course New course created to hold the activity */
    protected $course = null;

    /** @var stdClass $completionmarker New wordcloud created */
    protected $completionmarker = null;

    protected function setUp(){
        global $CFG;

        $CFG->usetags = false; // Desactivate tag system.
        
        $this->resetAfterTest();
        
        // Create course.
        $this->course = $this->getDataGenerator()->create_course();

        $completionmarker = array(
            'course' => $this->course->id,
            'introformat' => '1',
        );
        
        $this->completionmarker = $this->getDataGenerator()->create_module('completionmarker', $completionmarker);
    }
    
    /**
     * Test function add_word()
     */
    public function test_default_settings(){
        global $DB;

        $cm = $DB->get_record('course_modules', array('id' => $this->completionmarker->cmid));

        // completion must be enabled by default
        $this->assertEquals($cm->completion, 1);
    }


}