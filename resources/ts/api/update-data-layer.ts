/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';

export const updateDataLayer = (
	settings: Settings
): Promise< DataLayerResponse > => {
	const namespace = InpsydeGoogleTagManager.Rest.namespace;
	return apiFetch( {
		path: namespace + '/data-layer',
		method: 'POST',
		data: settings,
	} );
};
