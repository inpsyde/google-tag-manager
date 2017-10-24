<?php

use Behat\Behat\Context\SnippetAcceptingContext;
use PaulGibbs\WordpressBehatExtension\Context\RawWordpressContext;

/**
 * Define application features from the specific context.
 */
class PluginContext extends RawWordpressContext implements SnippetAcceptingContext {

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
	 * @Given /^The plugin "(?P<plugin>[^"]+)" is activated$/
	 */
	public function thePluginIsActivated( $plugin ) {

		$this->getDriver()
			->activatePlugin( $plugin );
	}

	/**
	 * @Given /^The plugin "(?P<plugin>[^"]+)" is deactivated$/
	 */
	public function thePluginIsDeactivated( $plugin ) {

		$this->getDriver()
			->deactivatePlugin( $plugin );
	}

}
