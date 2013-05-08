(function( $ ) {
  var widget = $('#feedinput_dashboard_widget');

  var itemsList = widget.find('.item-list');

  itemsList
    .on( 'click', 'a[data-action="convert-item"]', function( event ) {
      var button = $(this);
      var itemId = button.data('id');
      var item = button.closest( 'li' );

      $.post( ajaxurl, { action: 'feedinput_dashboard_convert_item', id: itemId })
        .done( function( data ) {
          button.replaceWith( data );
          item.addClass('converted');
        })
        .fail( function() {
	        alert('Something in the AJAX failed - check Network pane?');
        });
    })
    .on( 'click', 'a[data-action="trash-item"]', function( event ) {
      var button = $(this);
      var itemId = button.data('id');
      var item = button.closest( 'li' );

      $.post( ajaxurl, { action: 'feedinput_dashboard_remove_item', id: itemId });

      item.addClass('removed');

      setTimeout( checkToLoadNextPage, 500 );
    });

  // Paging
  var page = 1;
  var loading = false;
  var atEnd = false;

  var loadedItems = {}; // keep track of loaded items
  itemsList.children('[data-id]').each(function() {
    loadedItems[ $(this).data('id') ] = true;
  });

  // Spinner
  var pagingStatusIndicator = itemsList.find('.feedinput-paging-status-indicator');
  var spinner = new Spinner({
    className: 'feedinput-spinner',
    lines: 10,
    length: 5,
    width: 3,
    radius: 7
  }).spin();
  spinner.stop();

  var loadNextPage = function() {
    if ( loading || atEnd ) {
      return false;
    }

    loading = true;
    spinner.spin();
    pagingStatusIndicator.append( spinner.el );
    ++page;

    $.get( ajaxurl, { action: 'feedinput_dashboard_page', page: page} )
      .done(function( data ) {
        loading = false;
        spinner.stop();

        var items = $(data).children();
        atEnd = items.length == 0;

        items.each(function() {
          var item = $(this);
          if ( !loadedItems[item.data('id')] ) {
            item.insertBefore( pagingStatusIndicator );
          }
        });
      });

    return false;
  };

  var checkToLoadNextPage = function() {
    if ( pagingStatusIndicator.offset().top <= itemsList.height() + itemsList.offset().top ) {
      loadNextPage();
    }
  }

  itemsList.on( 'scroll', checkToLoadNextPage );

})( jQuery );