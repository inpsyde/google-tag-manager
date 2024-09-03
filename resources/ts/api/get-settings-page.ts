/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';

export const getSettingsPage = (): Promise< DataLayerResponse > => {
	const namespace = InpsydeGoogleTagManager.Rest.namespace;
	return apiFetch( {
		path: namespace + '/settings-page',
		method: 'GET',
	} );
};
