<?php

namespace Inpsyde\GoogleTagManager\Tests\Acceptance;

use AcceptanceTester;
use Codeception\Util\Fixtures;

class SettingsCest {

	/**
	 * @var string
	 */
	private $slug;

	public function _before( AcceptanceTester $I ) {

		$this->slug = Fixtures::get( 'PLUGIN_SLUG' );

		$I->loginAsAdmin();
		$I->amOnPluginsPage();

		try {
			$I->activatePlugin( $this->slug );
		}
		catch ( \Throwable $e ) {
		}
	}

	public function visitSettingsPage( AcceptanceTester $I ) {

		$I->wantToTest( 'That i can open the settings page.' );
		$I->amOnPage( '/wp-admin/options-general.php?page=' . $this->slug );
		$I->canSee( 'Google Tag Manager', 'h2' );
		$I->canSee( 'DataLayer', 'a' );
	}

	public function configureDataLayer( AcceptanceTester $I ) {

		$expected_id     = 'GTM-12345';
		$expected_option = 'disabled';
		$expected_name   = 'test';

		$I->wantToTest( 'That i can change the dataLayer-settings.' );
		$I->amOnPage( '/wp-admin/options-general.php?page=' . $this->slug );
		$I->fillField( [ 'id' => 'dataLayer_gtm_id' ], $expected_id );
		$I->selectOption( [ 'id' => 'dataLayer_auto_insert_noscript' ], $expected_option );
		$I->fillField( [ 'id' => 'dataLayer_datalayer_name' ], $expected_name );
		$I->click( '#submit' );

		$I->seeInField( [ 'id' => 'dataLayer_gtm_id' ], $expected_id );
		$I->canSeeOptionIsSelected( [ 'id' => 'dataLayer_auto_insert_noscript' ], $expected_option );
		$I->seeInField( [ 'id' => 'dataLayer_datalayer_name' ], $expected_name );
	}
}
