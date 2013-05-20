/**
 *
 */

(function( $ ) {
  $('input[data-widget="colorpicker"]').iris({
    hide: false,
    palettes: true
  });
<<<<<<< HEAD
=======

  $('a[data-action="reset"]').on( 'click', function() {
    $('#custom-css-variables').find(':input[data-default-value]').each( function() {
      var input = $(this);
      input.val( input.data('defaultValue') ).trigger( 'change' );
    });
  });
>>>>>>> b3a83f9a75cf2d6581d13e35404ebeb2517f391e
})( jQuery );