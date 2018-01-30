Feature: I can see the settings page and configure options.

  Background:
    Given I am logged in as an admin
    Given The plugin "inpsyde-google-tag-manager" is activated
    Given I am on "/wp-admin/options-general.php?page=inpsyde-google-tag-manager"

  Scenario: I can see the plugin settings page
    And I should see "Google Tag Manager"

  @javascript
  Scenario: I can change the dataLayer ID
    And I fill in "dataLayer_gtm_id" with "GTM-1234"
    And I fill in "dataLayer_datalayer_name" with "test_name"
    And I click the "#submit" element
    Then the "dataLayer_gtm_id" field should contain "GTM-1234"
    Then I should see "New settings successfully stored." in the ".updated" element

  @javascript
  Scenario: I can change the dataLayer name
    And I select "enabled" from "dataLayer_auto_insert_noscript"
    And I click the "#submit" element
    Then the "dataLayer_datalayer_name" field should contain "test_name"
    Then I should see "New settings successfully stored." in the ".updated" element

  @javascript
  Scenario: I can change the dataLayer name
    And I fill in "dataLayer_datalayer_name" with "test_name"
    And I click the "#submit" element
    Then the "dataLayer_datalayer_name" field should contain "test_name"
    Then I should see "New settings successfully stored." in the ".updated" element

  @javascript
  Scenario: The GTM-ID is validated and produces an error message if invalid
    And I fill in "dataLayer_gtm_id" with "test"
    And I click the "#submit" element
    Then I should see "New settings stored, but there are some errors. Please scroll down to have a look." in the ".error" element

  @javascript
  Scenario: The selected tab is active after clicking submit
    And I click the "[href='#tab--siteInfo']" element
    And I click the "#submit" element
    Then I should see the '#tab--siteInfo' tab is visible.
	
