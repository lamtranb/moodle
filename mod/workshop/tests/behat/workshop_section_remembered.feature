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
    Then "#workshop-viewlet-intro.collapsibleregion.collapsed" "css_element" should exist
    When I reload the page
    And I wait until the page is ready
    Then "#workshop-viewlet-intro.collapsibleregion.collapsed" "css_element" should exist
    # Check in Submission phase.
    When I change phase in workshop "Workshop 1" to "Submission phase"
    And I wait until the page is ready
    Then "#workshop-viewlet-instructauthors.collapsibleregion.collapsed" "css_element" should not exist
    And "#workshop-viewlet-ownsubmission.collapsibleregion.collapsed" "css_element" should not exist
    And "#workshop-viewlet-allsubmissions.collapsibleregion.collapsed" "css_element" should not exist
    When I click on "Instructions for submission" "link"
    And I click on "Your submission" "link"
    And I click on "Workshop submissions report" "link"
    Then "#workshop-viewlet-instructauthors.collapsibleregion.collapsed" "css_element" should exist
    And "#workshop-viewlet-ownsubmission.collapsibleregion.collapsed" "css_element" should exist
    And "#workshop-viewlet-allsubmissions.collapsibleregion.collapsed" "css_element" should exist
    When I reload the page
    And I wait until the page is ready
    Then "#workshop-viewlet-instructauthors.collapsibleregion.collapsed" "css_element" should exist
    And "#workshop-viewlet-ownsubmission.collapsibleregion.collapsed" "css_element" should exist
    And "#workshop-viewlet-allsubmissions.collapsibleregion.collapsed" "css_element" should exist
