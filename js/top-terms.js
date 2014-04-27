/**
 * Handles adding/removing options from top_terms admin dropdown
 */

(function( $ ) {

	$(document).ready(function() {

		var $dropdown = $('#top_term'),
			$checklists = $('.categorychecklist').not( "[id^='prominence'], [id^='post-type']" );

		$("input[type='checkbox']", $checklists).on( 'click', function() {
			var value = $(this).val();
			if (this.checked) {
				//make sure it exists
				if ( !$("option[value='"+value+"']", $dropdown).length ) {
					$dropdown.append('<option value="'+value+'">'+ $(this).parent('label').text().trim() +'</option>');
				}
			} else {
				//make sure it's gone
				if ( $("option[value='"+value+"']", $dropdown).length ) {
					$("option[value='"+value+"']", $dropdown).remove();
				}
			}
		});
	});

})( jQuery );