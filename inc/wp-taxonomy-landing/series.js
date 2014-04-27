(function($) {

	// show/hide custom HTML element
	$('input[name="header_style"]').on('click', function() {
		var $val = $(this).val();
		if ( $val == 'standard' ) {
			$('#header-html:visible').hide('fast');
		} else {
			$('#header-html:hidden').show('fast');
		}
	});

	// show/hide custom HTML element
	$('input[name="footer_style"]').on('click', function() {
		var $val = $(this).val();
		if ( $val != 'custom' ) {
			$('#footer-html:visible').hide('fast');
		} else {
			$('#footer-html:hidden').show('fast');
		}
	});

	//toggle help text display and dropdowns for columns
	$('input[name="cftl_layout"]').on('click', function() {
		var $val = $(this).val();
		console.log( $val );
		$('#explainer').removeClass().addClass($val);
		if ( $val == 'three-column' ) {
			$('.regioner:hidden').show('fast');
		} else if ( $val == 'two-column' ) {
			$('#left-region:visible').hide('fast');
			$('#right-region:hidden').show('fast');
		} else {
			$('.regioner:visible').hide('fast');
		}
	});

	$('input[name="cftl_layout"]:checked').trigger('click');

	//enabled header fade stuff
	$('#cftl_header_enabled').on('change', function() {
		if ( !this.checked ) {
			$('.form-field-radios-stacked, .form-field, .form-field-wysiwyg > *' , '#cftl_tax_landing_header').fadeTo(100, 0.5);
		} else {
			$('.form-field-radios-stacked, .form-field, .form-field-wysiwyg > *' , '#cftl_tax_landing_header').fadeTo(100, 1);
		}
	});

	//Custom order manager link
	$('select[name="post_order"]').on('change', function() {
		if ( $(this).val() == 'custom' ) {
			$('a.thickbox.custom:hidden').fadeIn('fast');
		} else {
			$('a.thickbox.custom:visible').fadeOut('fast');
		}
	});
	$('select[name="post_order"]').change();

	//Custom order manager sortable
	if ($('#custom-order').length) {
		var $sort = $("#postsort"), original_order = $sort.html();
		$sort.sortable().disableSelection();

		//ajax order submit
		$('#save-order').on('click', function() {
			tb_remove();
			var orderdata = 'action=series_sort&series_id=' + $sort.data('series-id') + '&' + $sort.sortable('serialize');
			orderdata += '&nonce=' + $('#_wpnonce').val() + '&post_id=' + $('#post_ID').val();
			$.post( ajaxurl, orderdata, function(resp) {
				console.log(resp);
			});
		});

		//cancel button
		$('input[name="cancel"]').on('click', function() {
			$sort.html(original_order);
			tb_remove();
		});

	}

	//fade on page load by toggling
	$('#cftl_header_enabled, #cftl_footer_enable').change().change();

})( jQuery );