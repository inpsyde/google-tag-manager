Feature: I can see the "user"-settings page and configure options.

  Background:
    Given I am logged in as an admin
    Given The plugin "inpsyde-google-tag-manager" is activated
    Given I am on "/wp-admin/options-general.php?page=inpsyde-google-tag-manager"
    Given I click the "[href='#tab--userData']" element

  Scenario: I can see the user data settings section
    Then I should see "Write user data into the Google Tag Manager data layer."

  @javascript
  Scenario: I can disable user data usage
    And I select "disabled" from "userData_enabled"
    And I click the "#submit" element
    Then I should see "New settings successfully stored." in the ".updated" element

  @javascript
  Scenario: I can save an empty visitorRole
    And I fill in "userData_visitor_role" with ""
    And I click the "#submit" element
    Then the "userData_visitor_role" field should contain ""