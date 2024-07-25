/**
 * WordPress dependencies
 */
import { createRoot } from '@wordpress/element';
import { dispatch } from '@wordpress/data';
import { store as coreStore } from '@wordpress/core-data';
/**
 * Internal dependencies
 */
import { SettingsPage } from './components/SettingsPage';
import '../scss/admin.scss';

dispatch( coreStore ).addEntities(
	Array.from( InpsydeGoogleTagManager.Entities )
);

const rootElement = document.querySelector( '.settings__content' );
if ( rootElement ) {
	const root = createRoot( rootElement );
	root.render( <SettingsPage /> );
}
