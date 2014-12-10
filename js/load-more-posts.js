(function() {
    var $ = jQuery;

    $(function() {
        var load_more = $('#nav-below .load-more'),
            ajax_opts = {
                url: LMP.ajax_url,
                data: {
                    action: 'load_more_posts',
                    paged: (LMP.paged == 0)? 1:LMP.paged
                },
                type: 'POST',
                dataType: 'html',
                success: function(html) {
                    var markup = $(html);
                    $(html).insertBefore('#nav-below');
                    load_more.removeClass('loading');
                },
                error: function() {
                    load_more.removeClass('loading');
                    throw "There was an error fetching more posts";
                }
            };

        load_more.find('a').click(function() {
            load_more.addClass('loading');
            var last_story = $('.stories article').last();
                id = last_story.attr('id').replace('post-', '');

            ajax_opts.data.last = id;
            ajax_opts.data.paged += 1;
            ajax_opts.data.query = LMP.query;
            $.ajax(ajax_opts);
            return false;
        });
    });
})();
