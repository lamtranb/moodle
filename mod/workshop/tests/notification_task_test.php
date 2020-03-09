<?php
// This file is part of Moodle - https://moodle.org/
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
 * Workshop notification test.
 *
 * @package    mod_workshop
 * @copyright  2020 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

global $CFG;

require_once($CFG->dirroot . '/mod/workshop/lib.php');

use mod_workshop\task\notification_task;

/**
 * Workshop notification test.
 *
 * @package    mod_workshop
 * @copyright  2020 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_workshop_notification_task_testcase extends advanced_testcase {

    /** @var stdClass Course. */
    private $course;

    /** @var workshop Workshop instance. */
    private $workshop;

    /** @var stdClass Course module. */
    private $cm;

    /** @var stdClass Workshop database record. */
    private $workshopdbrecord;

    /**
     * Setup for workshop notification tests.
     */
    protected function setUp() {
        $this->resetAfterTest();
        $this->setAdminUser();
        $this->course = $this->getDataGenerator()->create_course();
        $this->workshopdbrecord = $this->getDataGenerator()->create_module('workshop', [
                'course' => $this->course,
                'name' => 'Test Workshop'
        ]);
        $this->cm = get_coursemodule_from_instance('workshop', $this->workshopdbrecord->id);
        $this->workshop = new workshop($this->workshopdbrecord, $this->cm, $this->course);
    }

    /**
     * Workshop provider for test_assessment_notification_field.
     *
     * @return array
     */
    public function workshop_provider() {
        return [
                [workshop::PHASE_SETUP, 0],
                [workshop::PHASE_SUBMISSION, 0],
                [workshop::PHASE_ASSESSMENT, 1],
                [workshop::PHASE_EVALUATION, 0],
                [workshop::PHASE_CLOSED, 0]
        ];
    }

    /**
     * Test switching phase turn on/off assessmentnotification field.
     *
     * @dataProvider workshop_provider
     */
    public function test_assessment_notification_field($phase, $value) {
        $this->workshop->switch_phase($phase);
        $this->assertEquals($value, $this->workshop->assessmentnotification);
    }

    /**
     * Test notify reviewers have submissions to assess when in assessment phase.
     */
    public function test_assessment_phase_reviewer_notify() {
        global $DB;

        // Already check assessmentnotification = 1 when in assessment phase. Just switch to assessment phase.
        $this->workshop->switch_phase(workshop::PHASE_ASSESSMENT);

        // Execute the cron.
        ob_start();
        cron_setup_user();
        $cron = new notification_task();
        $cron->execute();
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertContains('Assessment phase notification of workshop started ...', $output);
        $this->assertContains('Processing assessment notification 1 workshop(s) ...', $output);
        $this->assertContains("Workshop {$this->workshop->id}: notifications sent.", $output);
        $this->assertContains('Assessment phase notification of workshop done ...', $output);
        // Check flag assessmentnotification is turn off now.
        $this->assertEquals(0, $DB->get_field('workshop', 'assessmentnotification', ['id' => $this->workshop->id]));
    }
}
