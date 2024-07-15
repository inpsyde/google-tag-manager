/**
 * External dependencies
 */
import { test } from '@inpsyde/playwright-utils';

test.describe( 'Plugin', () => {
	test( 'The Plugin is activated.', async ( {
		plugins,
		login,
		page,
	}, testInfo ) => {
		await login.login( 'admin', 'password' );
		await plugins.assertPluginActive( 'Inpsyde Google Tag Manager' );
		await page.screenshot( { path: `${ testInfo.outputDir }/plugin.png` } );
	} );
} );
