@mod @mod_workshop
Feature: Workshop remember collapsed/expanded sections in view page.
  In order to keep the last state of collapsed/expanded sections in view page
  As an user
  I need to be able to choose collapsed/expanded, and after refresh the page it will display collapsed/expanded I chose before.

  Background:
    Given the following "users" exist:
      | username | firstname | lastname | email                |
      | teacher1 | Teacher   | 1        | teacher1@example.com |
      | student1 | Student   | 1        | student1@example.com |
    And the following "courses" exist:
      | fullname | shortname |
      | Course1  | c1        |
    And the following "course enrolments" exist:
      | user     | course | role           |
      | teacher1 | c1     | editingteacher |
      | student1 | c1     | student        |
    And the following "activities" exist:
      | activity | name       | intro                  | course | idnumber  |
      | workshop | Workshop 1 | Workshop 1 description | c1     | workshop1 |

  @javascript
  Scenario: Check section in view page can be remembered.
    Given I log in as "admin"
    When I am on "Course1" course homepage
    And I follow "Workshop 1"
    Then I should see "Setup phase"
    And "#workshop-viewlet-intro.collapsibleregion.collapsed" "css_element" should not exist
    # Check sections in Setup phase.
    When I click on "Description" "link"
    And "#workshop-viewlet-intro.collapsibleregion.collapsed" "css_element" should exist
    And I reload the page
    Then "#workshop-viewlet-intro.collapsibleregion.collapsed" "css_element" should exist
    # Check in Submission phase.
    When I change phase in workshop "Workshop 1" to "Submission phase"
    Then "#workshop-viewlet-instructauthors.collapsibleregion.collapsed" "css_element" should not exist
    And "#workshop-viewlet-ownsubmission.collapsibleregion.collapsed" "css_element" should not exist
    And "#workshop-viewlet-allsubmissions.collapsibleregion.collapsed" "css_element" should not exist
    When I click on "Instructions for submission" "link"
    And I click on "Your submission" "link"
    And I click on "Workshop submissions report" "link"
    And "#workshop-viewlet-instructauthors.collapsibleregion.collapsed" "css_element" should exist
    And "#workshop-viewlet-ownsubmission.collapsibleregion.collapsed" "css_element" should exist
    And "#workshop-viewlet-allsubmissions.collapsibleregion.collapsed" "css_element" should exist
    And I reload the page
    Then "#workshop-viewlet-instructauthors.collapsibleregion.collapsed" "css_element" should exist
    And "#workshop-viewlet-ownsubmission.collapsibleregion.collapsed" "css_element" should exist
    And "#workshop-viewlet-allsubmissions.collapsibleregion.collapsed" "css_element" should exist
    # Check in Assessment phase.
    When I change phase in workshop "Workshop 1" to "Assessment phase"
    # We already remembered this section in Submission phase (no need to test it again).
    Then "#workshop-viewlet-ownsubmission.collapsibleregion.collapsed" "css_element" should exist
    # There are some new sections.
    And "#workshop-viewlet-gradereport.collapsibleregion.collapsed" "css_element" should not exist
    And "#workshop-viewlet-instructreviewers.collapsibleregion.collapsed" "css_element" should not exist
    And "#workshop-viewlet-assignedassessments.collapsibleregion.collapsed" "css_element" should not exist
    When I click on "Workshop grades report" "link"
    And I click on "Instructions for assessment" "link"
    And I click on "Assigned submissions to assess" "link"
    And "#workshop-viewlet-gradereport.collapsibleregion.collapsed" "css_element" should exist
    And "#workshop-viewlet-instructreviewers.collapsibleregion.collapsed" "css_element" should exist
    And "#workshop-viewlet-assignedassessments.collapsibleregion.collapsed" "css_element" should exist
    And I reload the page
    Then "#workshop-viewlet-gradereport.collapsibleregion.collapsed" "css_element" should exist
    And "#workshop-viewlet-instructreviewers.collapsibleregion.collapsed" "css_element" should exist
    And "#workshop-viewlet-assignedassessments.collapsibleregion.collapsed" "css_element" should exist
    # Check Grading evaluation phase.
    When I change phase in workshop "Workshop 1" to "Grading evaluation phase"
    # Old sections already kept the collapsed.
    Then "#workshop-viewlet-gradereport.collapsibleregion.collapsed" "css_element" should exist
    And "#workshop-viewlet-ownsubmission.collapsibleregion.collapsed" "css_element" should exist
    # Workshop toolbox is collapsed by default.
    And "#workshop-viewlet-cleargrades.collapsibleregion.collapsed" "css_element" should exist
    When I click on "Workshop toolbox" "link"
    And "#workshop-viewlet-cleargrades.collapsibleregion.collapsed" "css_element" should not exist
    And I reload the page
    Then "#workshop-viewlet-cleargrades.collapsibleregion.collapsed" "css_element" should not exist
    # Check Closed phase.
    When I change phase in workshop "Workshop 1" to "Closed"
    # We already collapsed Grade report in Assessment phase.
    Then "#workshop-viewlet-gradereport.collapsibleregion.collapsed" "css_element" should exist
    # We already collapsed Your submission in Submission phase.
    And "#workshop-viewlet-ownsubmission.collapsibleregion.collapsed" "css_element" should exist
    # New section Your Grades.
    And "#workshop-viewlet-yourgrades.collapsibleregion.collapsed" "css_element" should not exist
    When I click on "Your grades" "link"
    And "#workshop-viewlet-yourgrades.collapsibleregion.collapsed" "css_element" should exist
    And I reload the page
    Then "#workshop-viewlet-yourgrades.collapsibleregion.collapsed" "css_element" should exist

