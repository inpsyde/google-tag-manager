<?php # -*- coding: utf-8 -*-

namespace Inpsyde\GoogleTagManager\Tests\Behat;

use Behat\Behat\Context\SnippetAcceptingContext;
use PaulGibbs\WordpressBehatExtension\Context\RawWordpressContext;

/**
 * Define application features from the specific context.
 */
class PluginContext extends RawWordpressContext implements SnippetAcceptingContext {

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
