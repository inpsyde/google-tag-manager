Feature: I can see the settings page and configure options.

  Background:
	Given I am logged in as an admin
	Given The plugin "google-tag-manager" is activated
	Given I am on "/wp-admin/options-general.php?page=inpsyde-google-tag-manager"

  @javascript
  Scenario: I can see the plugin settings page
	And I should see "Google Tag Manager"

  @javascript
  Scenario: I can change the dataLayer-settings
	And I fill in "dataLayer_gtm_id" with "GTM-1234"
	And I fill in "dataLayer_datalayer_name" with "test_name"
	And I select "enabled" from "dataLayer_auto_insert_noscript"
	And I click the "#submit" element
	Then I should see "Die neuen Einstellungen wurden erfolgreich gespeichert." in the ".updated" element

  @javascript
  Scenario: The GTM-ID is validated and produces an error message if invalid
	And I fill in "dataLayer_gtm_id" with "test"
	And I click the "#submit" element
	Then I should see "Die neuen Einstellungen wurden gespeichert, es traten aber bei einigen Fehler auf. Bitte scrolle nach unten um die Fehler zu sehen." in the ".error" element
	Then I should not see text matching "gtm_id: The input test does not match against pattern /^GTM-[A-Z0-9]+$/."
	
