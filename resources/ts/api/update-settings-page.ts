/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';

export const updateSettingsPage = (
	settings: Settings
): Promise< SettingsPageResponse > => {
	const namespace = InpsydeGoogleTagManager.Rest.namespace;
	return apiFetch( {
		path: namespace + '/settings-page',
		method: 'POST',
		data: settings,
	} );
};
