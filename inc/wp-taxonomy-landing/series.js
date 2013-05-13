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

	//toggle help text display
	$('input[name="cftl_layout"]').on('click', function() {
		var $val = $(this).val();
		$('#explainer').removeClass().addClass($val);
	});

	//enabled header fade stuff
	$('#cftl_header_enabled').on('change', function() {
		if ( !this.checked ) {
			$('.form-field-radios-stacked, .form-field, .form-field-wysiwyg > *' , '#cftl_tax_landing_header').fadeTo(100, 0.5);
		} else {
			$('.form-field-radios-stacked, .form-field, .form-field-wysiwyg > *' , '#cftl_tax_landing_header').fadeTo(100, 1);
		}
	});

	//enabled footer fade stuff
	$('#cftl_footer_enable').on('change', function() {
		if ( !this.checked ) {
			$('#footer-html').fadeTo(100, 0.5);
		} else {
			$('#footer-html').fadeTo(100, 1);
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