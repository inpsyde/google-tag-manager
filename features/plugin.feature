Feature: Activate and deactivate the plugin successfully.

  Background:
	Given I am logged in as an admin
	And I am on "/wp-admin/plugins.php"

  @javascript
  Scenario: I can activate the plugin
	Then I should see "Inpsyde Google Tag Manager"

  @javascript
  Scenario: I can activate the plugin
	Given I am on "/wp-admin/plugins.php"
	And The plugin "google-tag-manager" is deactivated
	And I click the "[data-slug='google-tag-manager'] .activate a" element
	Then I should see an status message that says "Plugin aktiviert."

  @javascript
  Scenario: I can deactivate the plugin
	Given I am on "/wp-admin/plugins.php"
	And The plugin "google-tag-manager" is activated
	And I click the "[data-slug='google-tag-manager'] .deactivate a" element
	Then I should see an status message that says "Plugin deaktiviert."