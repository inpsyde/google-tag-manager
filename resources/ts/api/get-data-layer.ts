/**
 * WordPress dependencies
 */
import apiFetch from '@wordpress/api-fetch';

export const getDataLayer = (): Promise< DataLayerResponse > => {
	const namespace = InpsydeGoogleTagManager.Rest.namespace;
	return apiFetch( {
		path: namespace + '/data-layer',
		method: 'GET',
	} );
};
