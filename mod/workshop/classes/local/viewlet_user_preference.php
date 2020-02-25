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
 * The user preference for collapsed/expanded sections in view page.
 *
 * @package    mod_workshop
 * @copyright  2020 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_workshop\local;

defined('MOODLE_INTERNAL') || die();

/**
 * The user preference for collapsed/expanded sections in view page.
 *
 * @package    mod_workshop
 * @copyright  2020 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class viewlet_user_preference {

    /** @var string The user preference prefix for collapsed/expanded sections in view page. */
    const WORKSHOP_VIEWLET_PREFIX = 'workshop_viewlet';

    /** @var string The user preference value for collapsible "Description" block. */
    const WORKSHOP_VIEWLET_INTRO = 'intro';

    /** @var string The user preference value for collapsible "Example submissions" block. */
    const WORKSHOP_VIEWLET_ALLEXAMPLES = 'allexamples';

    /** @var string The user preference value for collapsible "Instructions for submission" block. */
    const WORKSHOP_VIEWLET_INSTRUCTAUTHORS = 'instructauthors';

    /** @var string The user preference value for collapsible "Example submissions to assess" block. */
    const WORKSHOP_VIEWLET_EXAMPLES = 'examples';

    /** @var string The user preference value for collapsible "Your submission" block. */
    const WORKSHOP_VIEWLET_OWNSUBMISSION = 'ownsubmission';

    /** @var string The user preference value for collapsible "Workshop submissions report" block. */
    const WORKSHOP_VIEWLET_ALLSUBMISSIONS = 'allsubmissions';

    /** @var string The user preference value for collapsible "Workshop grades report" block. */
    const WORKSHOP_VIEWLET_GRADEREPORT = 'gradereport';

    /** @var string The user preference value for collapsible "Instructions for assessment" block. */
    const WORKSHOP_VIEWLET_INSTRUCTREVIEWERS = 'instructreviewers';

    /** @var string The user preference value for collapsible "Example submissions to assess" (fail) block. */
    const WORKSHOP_VIEWLET_EXAMPLESFAIL = 'examplesfail';

    /** @var string The user preference value for collapsible "Assigned submissions to assess" block. */
    const WORKSHOP_VIEWLET_ASSIGNEDASSESSMENTS = 'assignedassessments';

    /** @var string The user preference value for collapsible "Workshop toolbox" block. */
    const WORKSHOP_VIEWLET_CLEARGRADES = 'cleargrades';

    /** @var string The user preference value for collapsible "Conclusion" block. */
    const WORKSHOP_VIEWLET_CONCLUSION = 'conclusion';

    /** @var string The user preference value for collapsible "Your grades" block. */
    const WORKSHOP_VIEWLET_YOURGRADES = 'yourgrades';

    /** @var string The user preference value for collapsible "Published submissions" block. */
    const WORKSHOP_VIEWLET_PUBLICSUBMISSIONS = 'publicsubmissions';

    /** @var string Workshop preference metadata for collapsed/expanded sections in view page. */
    const WORKSHOP_VIEWLET_METADATA = 'workshop_viewlet_PREFNAME_ID';

    /** @var string Workshop preference metadata lang string for collapsed/expanded sections in view page. */
    const WORKSHOP_VIEWLET_METADATA_STRING = 'privacy:metadata:preference:viewlet';

    /**
     * Get all preferences.
     *
     * @param int $instanceid Instance field of course module.
     * @return array
     */
    public static function get_list(int $instanceid) {
        return [
                self::get_name(self::WORKSHOP_VIEWLET_INTRO, $instanceid),
                self::get_name(self::WORKSHOP_VIEWLET_ALLEXAMPLES, $instanceid),
                self::get_name(self::WORKSHOP_VIEWLET_INSTRUCTAUTHORS, $instanceid),
                self::get_name(self::WORKSHOP_VIEWLET_EXAMPLES, $instanceid),
                self::get_name(self::WORKSHOP_VIEWLET_OWNSUBMISSION, $instanceid),
                self::get_name(self::WORKSHOP_VIEWLET_ALLSUBMISSIONS, $instanceid),
                self::get_name(self::WORKSHOP_VIEWLET_GRADEREPORT, $instanceid),
                self::get_name(self::WORKSHOP_VIEWLET_INSTRUCTREVIEWERS, $instanceid),
                self::get_name(self::WORKSHOP_VIEWLET_EXAMPLESFAIL, $instanceid),
                self::get_name(self::WORKSHOP_VIEWLET_ASSIGNEDASSESSMENTS, $instanceid),
                self::get_name(self::WORKSHOP_VIEWLET_CLEARGRADES, $instanceid),
                self::get_name(self::WORKSHOP_VIEWLET_CONCLUSION, $instanceid),
                self::get_name(self::WORKSHOP_VIEWLET_YOURGRADES, $instanceid),
                self::get_name(self::WORKSHOP_VIEWLET_PUBLICSUBMISSIONS, $instanceid)
        ];
    }

    /**
     * Get preference name (plus instance id).
     *
     * @param string $prefname Preference name.
     * @param int $instanceid Instance field of course module.
     * @return string
     */
    public static function get_name(string $prefname, int $instanceid) {
        $a = new \stdClass();
        $a->prefix = self::WORKSHOP_VIEWLET_PREFIX;
        $a->prefname = $prefname;
        $a->instanceid = $instanceid;
        return get_string('workshop:userprefname', 'mod_workshop', $a);
    }

    /**
     * Get all instances has viewlet user preferences.
     *
     * @param int $userid User id
     * @return array
     */
    public static function get_ids(int $userid) {
        global $DB;
        $prefix = self::WORKSHOP_VIEWLET_PREFIX;
        $viewletsql = $DB->sql_like('name', ':name', false, false);
        $sql = "userid = :userid AND $viewletsql";
        $params = [
                'userid' => $userid,
                'name' => "%$prefix%",
        ];
        $prefs = $DB->get_fieldset_select('user_preferences', 'name', $sql, $params);
        $ids = [];
        if (!empty($prefs)) {
            foreach ($prefs as $pref) {
                list ($type, $instanceid) = self::extract($pref);
                if ($instanceid === null) {
                    continue;
                }
                $ids[] = $instanceid;
            }
        };
        return $ids;
    }

    /**
     * Get type and instance id of course module.
     *
     * @param string $pref Preference name.
     * @return array
     */
    public static function extract(string $pref) {
        $type = null;
        $instanceid = null;
        $prefix = self::WORKSHOP_VIEWLET_PREFIX;
        if (preg_match("/^{$prefix}_(\w+)_(\d+)$/", $pref, $matches)) {
            $type = $matches[1];
            $instanceid = $matches[2];
        }
        return [$type, $instanceid];
    }
}
