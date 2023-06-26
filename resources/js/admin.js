/* global jQuery */
import '../scss/admin.scss';

jQuery( '#inpsyde-tabs' ).tabs();

jQuery( '#inpsyde-form' ).on( 'submit', function () {
	const $form = jQuery( this ),
		hash = jQuery( '.ui-state-active', '#inpsyde-tabs' )
			.children( '.ui-tabs-anchor' )
			.attr( 'href' ),
		action = $form.attr( 'action' ).split( '#' )[ 0 ];

	$form.attr( 'action', action + hash );
} );
