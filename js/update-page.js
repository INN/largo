(function() {
    var $ = jQuery;

    $('input#update').click(function() {
        var parent = $('.update-message'),
            spinner = parent.find('.spinner');

        parent.find('.message').remove();

        spinner.css('display', 'inline-block');
        $.ajax({
            url: ajaxurl,
            data: {
                action: 'largo_ajax_update_database',
            },
            type: 'POST',
            dataType: 'json',
            success: function(data) {
                spinner.css('display', 'none');
                if (!data.success)
                    parent.append('<div class="error">' + data.status + '</div>');
                else
                    parent.html('<div class="updated">' + data.status + '</div>');

                $('[href="themes.php?page=largo-block-theme-options"]').attr('href', 'themes.php?page=options-framework');
            },
            error: function() {
                throw "There was an error running the update.";
            }
        })
        return false;
    });
})();
