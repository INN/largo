(function() {
    var $ = jQuery,
        LoadMorePosts = function(config) {
            this.config = config;
            this.$el = $('#' + this.config.nav_id);
            return this.init();
        };

    LoadMorePosts.prototype.init = function() {
        this.ajax_opts = {
            url: this.config.ajax_url,
            data: {
                action: 'load_more_posts',
                paged: this.config.paged,
                is_home: this.config.is_home,
                is_series_landing: this.config.is_series_landing,
                // opt is used by partials/content-series.php to return the same type of post.
                opt: this.config.opt
            },
            type: 'POST',
            dataType: 'html',
            success: this._success.bind(this),
            error: this._error
        };

        this.bind_events();

        return this;
    };

    LoadMorePosts.prototype.request = function() {
        this.$el.addClass('loading');
        this.ajax_opts.data.paged += 1;
        this.ajax_opts.data.query = JSON.stringify(this.config.query);
        $.ajax(this.ajax_opts);
        return false;
    };

    LoadMorePosts.prototype.bind_events = function() {
        this.$el.find('a').click(this.request.bind(this));
    };

    LoadMorePosts.prototype._success = function(html) {
        if (html.trim() == '') {
            this.$el.html("<span>" + this.config.no_more_posts + "</span>");
        } else {
            var markup = $(html);
            $(html).insertBefore(this.$el);
        }
        this.$el.removeClass('loading');
    };

    LoadMorePosts.prototype._error = function() {
        this.$el.removeClass('loading');
        throw "There was an error fetching more posts";
    };

    if (typeof window.LoadMorePosts == 'undefined')
        window.LoadMorePosts = LoadMorePosts;

})();
