/**
 * Internal dependencies
 */
import { test } from '../test';
/**
 * External dependencies
 */
import { expect } from '@inpsyde/playwright-utils/build';

test.describe( 'Plugin Settings - DataLayer', () => {
	test.beforeEach( async ( { pluginSettingsPage, login } ) => {
		await login.login( 'admin', 'password' );
		await pluginSettingsPage.visit();
	} );

	test( 'I can set successfully a GTM ID.', async ( {
		pluginSettingsPage,
	} ) => {
		await pluginSettingsPage.fillInGtmId( 'GTM-12345' );
		await pluginSettingsPage.submitForm();

		await expect( pluginSettingsPage.successMessage() ).toBeVisible();
		await expect( pluginSettingsPage.gtmIdInput() ).toHaveValue(
			'GTM-12345'
		);
	} );

	test( 'I can set successfully a dataLayer name.', async ( {
		pluginSettingsPage,
	} ) => {
		await pluginSettingsPage.fillInDataLayerName( 'myDataLayer' );
		await pluginSettingsPage.submitForm();

		await expect( pluginSettingsPage.successMessage() ).toBeVisible();
		await expect( pluginSettingsPage.dataLayerNameInput() ).toHaveValue(
			'myDataLayer'
		);
	} );

	test( 'I can enable and disable the noscript.', async ( {
		pluginSettingsPage,
	} ) => {
		await pluginSettingsPage.enableAutoInsertNoScript();
		await pluginSettingsPage.submitForm();
		await expect( pluginSettingsPage.successMessage() ).toBeVisible();
		await expect(
			pluginSettingsPage.autoInsertNoscriptSelect()
		).toHaveValue( 'enable' );

		await pluginSettingsPage.disableAutoInsertNoScript();
		await pluginSettingsPage.submitForm();
		await expect( pluginSettingsPage.successMessage() ).toBeVisible();
		await expect(
			pluginSettingsPage.autoInsertNoscriptSelect()
		).toHaveValue( 'disable' );
	} );

	test( 'I can enable or disable collectors.', async ( {
		pluginSettingsPage,
	} ) => {
		const collectors: Map< string, boolean > = new Map();
		collectors.set( 'User', ! Math.round( Math.random() ) );
		collectors.set( 'Site info', ! Math.round( Math.random() ) );
		collectors.set( 'Search', ! Math.round( Math.random() ) );
		collectors.set( 'Post data', ! Math.round( Math.random() ) );

		//@ts-ignore
		for ( const [ collector, selected ] of collectors ) {
			if ( selected ) {
				await pluginSettingsPage.enableCollector( collector );
			} else {
				await pluginSettingsPage.disableCollector( collector );
			}
		}

		await pluginSettingsPage.submitForm();
		await expect( pluginSettingsPage.successMessage() ).toBeVisible();

		//@ts-ignore
		for ( const [ collector, selected ] of collectors ) {
			const check = expect(
				pluginSettingsPage.enableCollectorCheckbox( collector )
			);
			if ( selected ) {
				await check.toBeChecked();
			} else {
				await check.not.toBeChecked();
			}
		}
	} );
} );
