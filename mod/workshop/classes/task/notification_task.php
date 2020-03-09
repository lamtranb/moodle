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
 * The main scheduled task for workshop notification.
 *
 * @package    mod_workshop
 * @copyright  2020 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_workshop\task;

defined('MOODLE_INTERNAL') || die();

/**
 * The main scheduled task for workshop notification.
 *
 * @package   mod_workshop
 * @copyright 2020 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class notification_task extends \core\task\scheduled_task {

    /** @var string Database flag for assessment notification. */
    const NOTIFICATION_ASSESSMENT_PHASE_FLAG = 'assessmentnotification';

    /**
     * Get a descriptive name for this task (shown to admins).
     *
     * @return string
     */
    public function get_name() {
        return get_string('notificationtask', 'mod_workshop');
    }

    /**
     * Run workshop notification cron.
     */
    public function execute() {
        global $CFG;

        mtrace('Notification of workshop started ...');

        require_once($CFG->dirroot . '/mod/workshop/locallib.php');

        // In assessment phase, notify reviewers have submissions to assess.
        $this->assessment_phase_notification();

        mtrace('Notification of workshop done ...');
    }

    /**
     * Send notification for workshops have assessment phase.
     */
    protected function assessment_phase_notification() {
        global $DB;
        $flagfield = self::NOTIFICATION_ASSESSMENT_PHASE_FLAG;

        mtrace('Assessment phase notification of workshop started ...');

        $workshops = $DB->get_records_select("workshop",
                "phase = :phase AND {$flagfield} = 1", ['phase' => \workshop::PHASE_ASSESSMENT]);

        if (!empty($workshops)) {

            mtrace('Processing assessment notification ' . count($workshops) . ' workshop(s) ... ', '');

            foreach ($workshops as $workshop) {
                $cm = get_coursemodule_from_instance('workshop', $workshop->id, $workshop->course, false, MUST_EXIST);
                $course = $DB->get_record('course', ['id' => $cm->course], '*', MUST_EXIST);
                $workshop = new \workshop($workshop, $cm, $course);
                $workshop->notify_reviewers_for_assessments();
                // Turn off field.
                $DB->set_field('workshop', $flagfield, 0, ['id' => $workshop->id]);
                mtrace("Workshop {$workshop->id}: notifications sent.");
            }
        }

        mtrace('Assessment phase notification of workshop done ...');
    }
}
