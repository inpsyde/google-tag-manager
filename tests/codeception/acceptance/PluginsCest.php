<?php

namespace Inpsyde\GoogleTagManager\Tests\Acceptance;

use AcceptanceTester;
use Codeception\Util\Fixtures;

class PluginsCest {

	public function _before( AcceptanceTester $I ) {

		$I->loginAsAdmin();
		$I->amOnPluginsPage();
	}

	public function activatePlugin( AcceptanceTester $I ) {

		$I->wantToTest( 'That i can activate the plugin without errors.' );
		$I->activatePlugin( Fixtures::get( 'PLUGIN_SLUG' ) );
		$I->canSeePluginActivated( Fixtures::get( 'PLUGIN_SLUG' ) );
	}

	/**
	 * @before activatePlugin
	 */
	public function deactivatePlugin( AcceptanceTester $I ) {

		$I->wantToTest( 'That i can deactivate the plugin without errors.' );
		$I->deactivatePlugin( Fixtures::get( 'PLUGIN_SLUG' ) );
		$I->canSeePluginDeactivated( Fixtures::get( 'PLUGIN_SLUG' ) );
	}

}
