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
 * Workshop helper.
 *
 * @package    mod_workshop
 * @copyright  2020 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_workshop\local;

defined('MOODLE_INTERNAL') || die();

/**
 * Workshop helper.
 *
 * @package    mod_workshop
 * @copyright  2020 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class workshop_helper {

    /** @var string The collapsed/expanded state of "Example submissions" block preference key. */
    const PREF_BLOCK_ALLEXAMPLES = 'workshop_block_allexamples';

    /** @var string The collapsed/expanded state of "Workshop submissions report" block preference key. */
    const PREF_BLOCK_ALLSUBMISSIONS = 'workshop_block_allsubmissions';

    /** @var string The collapsed/expanded state of "Assessment form" block preference key. */
    const PREF_BLOCK_ASSESSMENTFORM = 'workshop_block_assessmentform';

    /** @var string The collapsed/expanded state of "Assigned submissions to assess" block preference key. */
    const PREF_BLOCK_ASSIGNEDASSESSMENTS = 'workshop_block_assignedassessments';

    /** @var string The collapsed/expanded state of "Workshop toolbox" block preference key. */
    const PREF_BLOCK_CLEARGRADES = 'workshop_block_cleargrades';

    /** @var string The collapsed/expanded state of "Conclusion" block preference key. */
    const PREF_BLOCK_CONCLUSION = 'workshop_block_conclusion';

    /** @var string The collapsed/expanded state of "Example submissions to assess" block preference key. */
    const PREF_BLOCK_EXAMPLES = 'workshop_block_examples';

    /** @var string The collapsed/expanded state of "Example submissions to assess" (fail) block preference key. */
    const PREF_BLOCK_EXAMPLESFAIL = 'workshop_block_examplesfail';

    /** @var string The collapsed/expanded state of "Workshop grades report" block preference key. */
    const PREF_BLOCK_GRADEREPORT = 'workshop_block_gradereport';

    /** @var string The collapsed/expanded state of "Instructions for submission" block preference key. */
    const PREF_BLOCK_INSTRUCTAUTHORS = 'workshop_block_instructauthors';

    /** @var string The collapsed/expanded state of "Instructions for assessment" block preference key. */
    const PREF_BLOCK_INSTRUCTREVIEWERS = 'workshop_block_instructreviewers';

    /** @var string The collapsed/expanded state of "Description" block preference key. */
    const PREF_BLOCK_INTRO = 'workshop_block_intro';

    /** @var string The collapsed/expanded state of "Overall feedback" block preference key. */
    const PREF_BLOCK_OVERALLFEEDBACK = 'workshop_block_overallfeedback';

    /** @var string The collapsed/expanded state of "Your submission" block preference key. */
    const PREF_BLOCK_OWNSUBMISSION = 'workshop_block_ownsubmission';

    /** @var string The collapsed/expanded state of "Published submissions" block preference key. */
    const PREF_BLOCK_PUBLICSUBMISSIONS = 'workshop_block_publicsubmissions';

    /** @var string The collapsed/expanded state of "Your grades" block preference key. */
    const PREF_BLOCK_YOURGRADES = 'workshop_block_yourgrades';

    /** @var string Number of allocated assessments the user prefer preference key. */
    const PREF_PERPAGE = 'workshop_perpage';

    /**
     * Get user preferences.
     *
     * @return array
     */
    public static function get_user_prefs() {
        return [
                self::PREF_BLOCK_ALLEXAMPLES,
                self::PREF_BLOCK_ALLSUBMISSIONS,
                self::PREF_BLOCK_ASSESSMENTFORM,
                self::PREF_BLOCK_ASSIGNEDASSESSMENTS,
                self::PREF_BLOCK_CLEARGRADES,
                self::PREF_BLOCK_CONCLUSION,
                self::PREF_BLOCK_EXAMPLES,
                self::PREF_BLOCK_EXAMPLESFAIL,
                self::PREF_BLOCK_GRADEREPORT,
                self::PREF_BLOCK_INSTRUCTAUTHORS,
                self::PREF_BLOCK_INSTRUCTREVIEWERS,
                self::PREF_BLOCK_INTRO,
                self::PREF_BLOCK_OVERALLFEEDBACK,
                self::PREF_BLOCK_OWNSUBMISSION,
                self::PREF_BLOCK_PUBLICSUBMISSIONS,
                self::PREF_BLOCK_YOURGRADES,
                self::PREF_PERPAGE
        ];
    }
}
