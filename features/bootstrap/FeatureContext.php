<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use PaulGibbs\WordpressBehatExtension\Context\RawWordpressContext;

/**
 * Define application features from the specific context.
 */
class FeatureContext extends RawWordpressContext implements SnippetAcceptingContext {

	/**
	 * Initialise context.
	 *
	 * Every scenario gets its own context instance.
	 * You can also pass arbitrary arguments to the context constructor through behat.yml.
	 */
	public function __construct() {

		parent::__construct();
	}

	/**
	 * @Then I click the :arg1 element
	 */
	public function iClickElement( $element ) {

		$page = $this->getSession()
			->getPage();

		$findName = $page->find( "css", $element );
		if ( ! $findName ) {
			throw new Exception( $element . " could not be found" );
		} else {
			$findName->click();
		}
	}

	/**
	 * @Given /^The plugin "(?P<plugin>[^"]+)" is activated$/
	 * @And /^The plugin "(?P<plugin>[^"]+)" is activated$/
	 */
	public function thePluginIsActivated( $plugin ) {

		$this->getDriver()->wpcli('plugin', 'activate', [$plugin]);

	}

	/**
	 * @Given /^The plugin "(?P<plugin>[^"]+)" is deactivated$/
	 * @And /^The plugin "(?P<plugin>[^"]+)" is deactivated$/
	 */
	public function thePluginIsDeactivated( $plugin ) {

		$this->getDriver()->wpcli('plugin', 'deactivate', [$plugin]);
	}

}
