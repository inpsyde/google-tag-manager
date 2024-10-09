/**
 * Internal dependencies
 */
import { test } from '../test';

test.describe( 'Plugin', () => {
	test.beforeEach( async ( { login } ) => {
		await login.login( 'admin', 'password' );
	} );

	test( 'The Plugin can be activated.', async ( {
		plugins,
		login,
		page,
	}, testInfo ) => {
		await plugins.activatePlugin( 'Inpsyde Google Tag Manager' );
		await plugins.assertPluginActive( 'Inpsyde Google Tag Manager' );
		await page.screenshot( { path: `${ testInfo.outputDir }/plugin.png` } );
	} );

	test( 'The Plugin can be deactivated.', async ( {
		plugins,
		page,
	}, testInfo ) => {
		await plugins.deactivatePlugin( 'Inpsyde Google Tag Manager' );
		await plugins.assertPluginNotActive( 'Inpsyde Google Tag Manager' );
		await page.screenshot( { path: `${ testInfo.outputDir }/plugin.png` } );
	} );
} );
