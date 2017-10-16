(
	function( $ ) {
		"use strict";
		$( "#inpsyde-tabs" ).tabs( {
			activate: function( event, ui ) {
				var $form = $( '#inpsyde-form' ),
					$anchor = event.currentTarget,
					hash = $anchor.getAttribute( 'href' ),
					action = $form.attr( 'action' ).split( '#' )[ 0 ];

				$form.attr( 'action', action + hash );
			}
		} );
	}
)( jQuery );