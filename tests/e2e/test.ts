/**
 * External dependencies
 */
import { test as base } from '@inpsyde/playwright-utils/build';
/**
 * Internal dependencies
 */
import { PluginSettingsPage } from './src/PluginSettingsPage';

const test = base.extend< {
	pluginSettingsPage: PluginSettingsPage;
} >( {
	pluginSettingsPage: async ( { page }, use ) => {
		await use( new PluginSettingsPage( { page } ) );
	},
} );

export { test };
