(
	function( $ ) {
		"use strict";
		$( "#inpsyde-tabs" ).tabs();

		$( '#inpsyde-form' ).on( 'submit', function() {
			var $form = $( this ),
				hash = $( '.ui-state-active', '#inpsyde-tabs' ).children( '.ui-tabs-anchor' ).attr( 'href' ),
				action = $form.attr( 'action' ).split( '#' )[ 0 ];

			$form.attr( 'action', action + hash );
		} );
	}
)( jQuery );