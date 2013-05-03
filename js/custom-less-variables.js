/**
 *
 */

(function( $ ) {
  $('input[data-widget="colorpicker"]').iris({
    hide: false,
    palettes: true
  });

  $('a[data-action="reset"]').on( 'click', function() {
    $('#custom-css-variables').find(':input[data-default-value]').each( function() {
      var input = $(this);
      input.val( input.data('defaultValue') ).trigger( 'change' );
    });
  });
})( jQuery );