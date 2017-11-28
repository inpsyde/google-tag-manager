Feature: Activate and deactivate the plugin successfully.

  Background:
	Given I am logged in as an admin

  Scenario: I can see the plugin
	And I am on the plugins-page
	Then I should see "Inpsyde Google Tag Manager"

  @javascript
  Scenario: I can activate the plugin
	Given The plugin "inpsyde-google-tag-manager" is deactivated
	And I am on the plugins-page
	And I activate the plugin "inpsyde-google-tag-manager"
	Then I should see an status message that says "Plugin activated."

  @javascript
  Scenario: I can deactivate the plugin
	Given The plugin "inpsyde-google-tag-manager" is activated
	And I am on the plugins-page
	And I deactivate the plugin "inpsyde-google-tag-manager"
	Then I should see an status message that says "Plugin deactivated."