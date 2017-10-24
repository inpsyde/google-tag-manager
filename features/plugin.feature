Feature: Activate and deactivate the plugin successfully.

  Background:
	Given I am logged in as an admin

  @javascript
  Scenario: I can see the plugin
	And I am on "/wp-admin/plugins.php"
	Then I should see "Inpsyde Google Tag Manager"

  @javascript
  Scenario: I can activate the plugin
	Given The plugin "inpsyde-google-tag-manager" is deactivated
	And I am on "/wp-admin/plugins.php"
	And I click the "[data-slug='inpsyde-google-tag-manager'] .activate a" element
	Then I should see an status message that says "Plugin activated."

  @javascript
  Scenario: I can deactivate the plugin
	Given The plugin "inpsyde-google-tag-manager" is activated
	And I am on "/wp-admin/plugins.php"
	And I click the "[data-slug='inpsyde-google-tag-manager'] .deactivate a" element
	Then I should see an status message that says "Plugin deactivated."