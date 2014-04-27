( function( $ ) {
	$('.advance-widget-settings').each( function() {
		var wrapper = $(this);
		wrapper.addClass( 'closed' );
		wrapper.find( '.advance-widget-settings-title' ).on( 'click', function( event ) {
			wrapper.toggleClass( 'closed' );
			event.preventDefault();
		});
	});
})( jQuery );