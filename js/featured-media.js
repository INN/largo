/* LFM -- Largo Featured Media modal */
if (!window.console) {
    console = {
        log: function(){}
    };
}

var LFM = _.extend(LFM || {}, {
    Utils: {},
    Views: {},
    Models: {},
    Controller: {},
    instances: {},
    fetching: false
});

(function() {
    var $ = jQuery,
        l10n = _wpMediaViewsL10n,
        isTouchDevice = ('ontouchend' in document);

    /* Models */
    var featuredMediaModel;
    LFM.Models.featuredMediaModel = featuredMediaModel = Backbone.Model.extend({
        initialize: function() {
            Backbone.Model.prototype.initialize.apply(this, arguments);
            this.set({ id: LFM.Utils.getPostId() });
        },

        url: ajaxurl,

        sync: function(method, model, options) {
            var data;

            if (method == 'create' || method == 'update')
                data =  model.toJSON();
            else
                data = {};

            // Make sure we tell the backend what post ID we're dealing with.
            data = _.extend(data, { id: LFM.Utils.getPostId() });

            var action;
            if (method == 'read')
                action = 'largo_featured_media_read';
            else if (method == 'update' || method ==  'create')
                action = 'largo_featured_media_save';
            else
                return false;

            var success = options.success;
            var error = options.error;
            LFM.Utils.doAjax(action, data, success, error);
        }
    });

    /* Featured Media Modal (Controller) */
    LFM.Views.featuredMediaFrame = wp.media.view.MediaFrame.Select.extend({
        initialize: function() {
            _.defaults(this.options, {
                multiple: true,
                editing: false,
                state: 'image',
                metadata: {},
                className: 'featured-media-modal',
                model: new featuredMediaModel()
            });

            wp.media.view.MediaFrame.Select.prototype.initialize.apply(this, arguments);
            this.createIframeStates();
            this.$el.addClass(this.options.className);
        },

        createStates: function() {
            var options = this.options;

            this.states.add([
                // Embed code
                new wp.media.controller.Embed({
                    title: 'Featured embed code',
                    id: 'embed-code',
                    content: 'embed',
                    priority: 0
                }),

                // Video embed
                new wp.media.controller.Embed({
                    title: 'Featured video',
                    id: 'video',
                    content: 'video',
                    priority: 10
                }),

                // Featured image
                new wp.media.controller.FeaturedImage({
                    title: 'Featured image',
                    priority: 20,
                    id: 'image',
                }),

				new wp.media.controller.EditImage({ model: options.editImage }),

                // Featured gallery
                new wp.media.controller.Library({
                    id: 'gallery',
                    title: 'Featured gallery',
                    priority: 30,
                    toolbar: 'main-gallery',
                    filterable: 'uploaded',
                    multiple: 'add',
                    editable: false,
                    library: wp.media.query(_.defaults({
                        type: 'image'
                    }, options.library))
                }),

                // Gallery states.
                new wp.media.controller.GalleryEdit({
                    library: options.selection,
                    editing: options.editing,
                    menu: 'gallery'
                }),

                new wp.media.controller.GalleryAdd(),

            ]);

            if (LFM.has_featured_media) {
                this.states.add([
                    new LFM.Controller.removeFeaturedMedia()
                ]);
            }
        },

        bindHandlers: function() {
            var handlers;

            wp.media.view.MediaFrame.Select.prototype.bindHandlers.apply(this, arguments);

            this.on('menu:create:gallery', this.createMenu, this);
            this.on('toolbar:create:main-gallery', this.createToolbar, this);
            this.on('toolbar:create:featured-image', this.featuredImageToolbar, this);
            this.on('toolbar:create:main-embed', this.mainEmbedToolbar, this);

            handlers = {
                menu: {
                    'default': 'mainMenu',
                    'gallery': 'galleryMenu',
                },

                content: {
                    'embed': 'embedContent',
                    'video': 'embedVideo',
                    'edit-image': 'editImageContent',
                    'edit-selection': 'editSelectionContent',
                    'remove': 'removeFeaturedMedia'
                },

                toolbar: {
                    'main-gallery': 'mainGalleryToolbar',
                    'gallery-edit': 'galleryEditToolbar',
                    'gallery-add': 'galleryAddToolbar',
                    'remove-featured': 'removeFeaturedToolbar'
                }
            };

            _.each( handlers, function( regionHandlers, region ) {
                _.each( regionHandlers, function( callback, handler ) {
                    this.on( region + ':render:' + handler, this[ callback ], this );
                }, this );
            }, this );
        },

        mainMenu: function( view ) {
            view.set({
                'library-separator': new wp.media.View({
                    className: 'separator',
                    priority: 100
                })
            });
        },

        removeFeaturedMedia: function() {
            var view = new LFM.Views.removeFeaturedView({
                controller: this,
                model: this.state()
            });
            this.content.set(view);
        },

        galleryMenu: function( view ) {
            var frame = this;

            view.set({
                cancel: {
                    text: l10n.cancelGalleryTitle,
                    priority: 20,
                    click: function() {
                        frame.setState(frame.state('gallery'));

                        // Keep focus inside media modal
                        // after canceling a gallery
                        this.controller.modal.focusManager.focus();
                    }
                },
                separateCancel: new wp.media.View({
                    className: 'separator',
                    priority: 40
                })
            });
        },

        // Content
        embedVideo: function() {
            var view = new LFM.Views.featuredVideoView({
                controller: this,
                model: this.state()
            });

            this.content.set(view);
        },

        embedContent: function() {
            var view = new LFM.Views.featuredEmbedCodeView({
                controller: this,
                model: this.state()
            }).render();

            this.content.set(view);
        },

        editSelectionContent: function() {
            var state = this.state(),
            selection = state.get('selection'),
            view;

            view = new wp.media.view.AttachmentsBrowser({
                controller: this,
                collection: selection,
                selection: selection,
                model: state,
                sortable: true,
                search: false,
                dragInfo: true,

                AttachmentView: wp.media.view.Attachment.EditSelection
            }).render();

            view.toolbar.set('backToLibrary', {
                text:     l10n.returnToLibrary,
                priority: -100,

                click: function() {
                    this.controller.content.mode('browse');
                }
            });

            // Browse our library of attachments.
            this.content.set( view );
        },

        editImageContent: function() {
            var image = this.state().get('image'),
            view = new wp.media.view.EditImage({ model: image, controller: this }).render();

            this.content.set(view);

            // after creating the wrapper view, load the actual editor via an ajax call
            view.loadEditor();
        },

        selectionStatusToolbar: function(view) {
            var editable = this.state().get('editable');

            view.set('selection', new wp.media.view.Selection({
                controller: this,
                collection: this.state().get('selection'),
                priority: -40,

                // If the selection is editable, pass the callback to
                // switch the content mode.
                editable: editable && function() {
                    this.controller.content.mode('edit-selection');
                }
            }).render());
        },

        mainGalleryToolbar: function(view) {
            var controller = this;

            this.selectionStatusToolbar(view);

            view.set('gallery', {
                style: 'primary',
                text: l10n.createNewGallery,
                priority: 60,
                requires: { selection: true },

                click: function() {
                    var selection = controller.state().get('selection'),
                    edit = controller.state('gallery-edit'),
                    models = selection.where({ type: 'image' });

                    edit.set('library', new wp.media.model.Selection(models, {
                        props:    selection.props.toJSON(),
                        multiple: true
                    }));

                    this.controller.setState('gallery-edit');

                    // Keep focus inside media modal
                    // after jumping to gallery view
                    this.controller.modal.focusManager.focus();
                }
            });
        },

        featuredImageToolbar: function(toolbar) {
            toolbar.view = new LFM.Views.featuredImageToolbar({
                controller: this
            });
        },

        mainEmbedToolbar: function(toolbar) {
            toolbar.view = new LFM.Views.defaultToolbar({
                controller: this
            });
        },

        galleryEditToolbar: function() {
            this.toolbar.set(new LFM.Views.defaultToolbar({
                controller: this
            }));
        },

        galleryAddToolbar: function() {
            this.toolbar.set(new wp.media.view.Toolbar({
                controller: this,
                items: {
                    insert: {
                        style: 'primary',
                        text: l10n.addToGallery,
                        priority: 80,
                        requires: { selection: true },

                        click: function() {
                            var controller = this.controller,
                            state = controller.state(),
                            edit = controller.state('gallery-edit');

                            edit.get('library').add( state.get('selection').models );
                            state.trigger('reset');
                            controller.setState('gallery-edit');
                        }
                    }
                }
            }));
        },

        removeFeaturedToolbar: function() {
            this.toolbar.set(new LFM.Views.removeFeaturedToolbar({
                controller: this
            }));
        }
    });

    /* Views for media types */
    LFM.Views.featuredBaseView = wp.media.View.extend({
        className: 'featured-media-view'
    });

    LFM.Views.featuredEmbeddableView = LFM.Views.featuredBaseView.extend({
        attachments: new wp.media.model.Attachments(),

        initialize: function() {
            LFM.Views.featuredBaseView.prototype.initialize.apply(this, arguments);

            var model = this.controller.model;
            if (this.controller.state().id !== model.get('type') || typeof model.get('attachment_data') == 'undefined')
                this.createUploader();

            return this;
        },

        render: function() {
            LFM.Views.featuredBaseView.prototype.render.apply(this, arguments);
            var model = this.controller.model;
            if (this.controller.state().id == model.get('type') && typeof model.get('attachment_data') !== 'undefined') {
                var attachment = new wp.media.model.Attachment(model.get('attachment_data'));
                this.updateThumbnail(attachment);
            }
            return this;
        },

        createUploader: function() {
            this.attachments.reset([]);
            this.attachments.observe(wp.Uploader.queue);
            this.attachments.on('change:uploading', this.uploadProgress.bind(this));
            this.on('attachmentUploaded', this.attachmentUploaded.bind(this));

            var inlineUploader = new wp.media.view.UploaderInline({
                controller: this.controller,
                status: true,
                postId: null
            });
            this.views.add('#embed-thumb', inlineUploader);
        },

        attachmentUploaded: function() {
            var attachment = this.attachments.first();
            this.updateThumbnail(attachment);
            this.off('attachmentUploaded');
            this.attachments.off();
            this.attachments.unobserve(wp.Uploader.queue);
        },

        updateThumbnail: function(attachment) {
            var self = this;

            this.thumbnail = new LFM.Views.featuredThumbnail({
                el: this.$el.find('#embed-thumb'),
                model: attachment
            }).render();

            this.thumbnail.on('remove', function() {
                self.createUploader();
                self.thumbnail.off();
            });

            this.attachments.unobserve(wp.Uploader.queue);
        },

        uploadProgress: function() {
            if (this.attachments.first().get('uploading') == false)
                this.trigger('attachmentUploaded');
        }
    });

    LFM.Views.featuredEmbedCodeView = LFM.Views.featuredEmbeddableView.extend({
        id: 'media-editor-embed-code',
        template: wp.media.template('featured-embed-code')
    });

    LFM.Views.featuredThumbnail = LFM.Views.featuredBaseView.extend({
        events: {
            'click a.remove-thumb': 'removeThumb'
        },

        template: wp.media.template('featured-thumb'),

        removeThumb: function(event) {
            var target = $(event.currentTarget);
            target.parent().remove();
            this.trigger('remove');
        }
    });

    LFM.Views.featuredVideoView = LFM.Views.featuredEmbeddableView.extend({
        events: {
            'paste input.url': 'fetchVideo',
            'keypress input.url': 'fetchVideo'
        },

        template: wp.media.template('featured-video'),

        fetchVideo: function(event) {
            var self = this;
                error = self.$el.find('p.error');

            error.html('');

            allowedKeyCodes = [86, 91, 17];
            allowedLastKeys = [null, 91, 17];

            if (!(event.keyCode in allowedKeyCodes) && !(this.lastKey in allowedLastKeys) || event.type == 'paste') {
                if (typeof this.kp !== 'undefined')
                    clearTimeout(this.kp);

                var address_input = $('input[name="url"]'),
                    url_pattern = /(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/;

                    address_input.on('input propertychange', function() {
                        self.kp = setTimeout(function() {
                            var address = address_input.val();

                            if (url_pattern.test(address))
                                self.fetchMeta(address);
                            else {
                                error.html('Error: please enter a valid URL.');
                            }
                        }, 100);
                    });

            }
            this.kp = event.keyCode;
        },

        fetchMeta: function(address) {
            var self = this;
                success = function(data) {
                    var error = self.$el.find('p.error');
                    error.html('');

                    if (!data.embed)
                        error.html('Please enter a valid video URL.');
                    else
                        self.$el.find('textarea').html(data.embed);

                    if (data.title)
                        self.$el.find('[name="title"]').val(data.title);

                    if (data.author_name)
                        self.$el.find('[name="credit"]').val(data.author_name);

                    if (self.$el.find('[name="attachment"]').length < 1)
                        self.updateThumbnail(new Backbone.Model(data));

                    self.hideSpinner();
                },
                failure = function() {
                    console.log('An error ocurred');
                };

            this.showSpinner();
            LFM.Utils.doAjax('largo_fetch_video_oembed', {
                action: 'largo_fetch_video_oembed',
                url: address
            }, success, failure);
        },

        showSpinner: function() {
            this.$el.find('.spinner').removeAttr('style');
        },

        hideSpinner: function() {
            this.$el.find('.spinner').css({ display: 'none' });
        }
    });

    LFM.Views.defaultToolbar = wp.media.view.Toolbar.extend({
        saving: false,

        initialize: function() {
            _.defaults(this.options, {
                items: {
                    submit: {
                        style: 'primary',
                        priority: 10,
                        requires: false,
                        text: 'Set as featured',
                        click: this.save.bind(this)
                    }
                }
            });

            wp.media.view.Toolbar.prototype.initialize.apply(this, arguments);

            // Add the "loading" indicator to the submit button container
            this.primary.$el.prepend('<span class="spinner" style="display: none;"></span>');
        },

        save: function() {
            if (this.saving)
                return;

            var self = this,
                view = this.controller,
                state = view.state(),
                attrs = {};

            if (state.get('id') == 'image') {
                attrs.type = 'image';
                var selection = state.get('selection'),
                    selected = selection.map(function(m) { return m.get('id'); });


                if (selected.length > 0) {
                    // Make sure the featuredImageId is set so that the editor works properly
                    // if reopened.
                    wp.media.view.settings.post.featuredImageId = selected[0];
                    // Set the attachment ID that will be sent over the wire.
                    attrs.attachment = selected[0];
                }
            } else if (state.get('id') == 'gallery-edit') {
                attrs.type = 'gallery';
                var library = state.get('library'),
                    selected = library.map(function(m) { return m.get('id'); });

                if (selected.length > 0)
                    attrs.gallery = selected;
            } else {
                attrs = LFM.Utils.formArrayToObj(view.$el.find('form').serializeArray());
            }

            this.saving = true;
            this.showSpinner();
            view.model = new featuredMediaModel(attrs);
            view.model.save({}, {
                success: function() {
                    self.saving = false;
                    self.hideSpinner();
                    $('#set-featured-media-button').html(
                        '<span class="dashicons dashicons-admin-generic"></span> Edit Featured Media');
                    LFM.has_featured_media = true;
                    LFM.instances.modal.close();
                }
            });
        },

        showSpinner: function() {
            this.$el.find('.spinner').removeAttr('style');
        },

        hideSpinner: function() {
            this.$el.find('.spinner').css({ display: 'none' });
        }
    });

    LFM.Views.featuredImageToolbar = LFM.Views.defaultToolbar.extend({
        saving: false,

        initialize: function() {
            _.defaults(this.options, {
                items: {
                    submit: {
                        style: 'primary',
                        priority: 10,
                        requires: { selection: true },
                        text: 'Set as featured',
                        click: this.save.bind(this)
                    }
                }
            });

            LFM.Views.defaultToolbar.prototype.initialize.apply(this, arguments);

            var override = wp.media.template('featured-image-override');
            this.secondary.$el.prepend(override());
        },

        save: function() {
            if (this.saving)
                return;

            var self = this,
                state = this.controller.state();

            var override = LFM.Utils.formArrayToObj(this.secondary.$el.find('form').serializeArray());
            override.id = LFM.Utils.getPostId();

            if (typeof override['featured-image-display'] == 'undefined')
                LFM.featured_image_display = '';
            else
                LFM.featured_image_display = 'on';

            this.showSpinner();
            LFM.Utils.doAjax('largo_save_featured_image_display', override, function() {
                LFM.Views.defaultToolbar.prototype.save.apply(self, arguments);
            });
        }
    });

    LFM.Views.removeFeaturedToolbar = wp.media.view.Toolbar.extend({
        initialize: function() {
            _.defaults(this.options, {
                items: {
                    submit: {
                        style: 'primary',
                        priority: 10,
                        requires: false,
                        text: 'Yes, remove featured media',
                        click: this.clearFeatured.bind(this)
                    }
                }
            });

            wp.media.view.Toolbar.prototype.initialize.apply( this, arguments );

            // Add the "loading" indicator to the submit button container
            this.primary.$el.prepend('<span class="spinner" style="display: none;"></span>');
        },

        clearFeatured: function() {
            var self = this,
                view = this.controller;

            this.showSpinner();
            view.model = new featuredMediaModel();
            view.model.save({}, {
                success: function() {
                    self.hideSpinner();
                    $('#set-featured-media-button').text('Set Featured Media');
                    LFM.instances.modal.close();
                    LFM.has_featured_media = false;
                    wp.media.view.settings.post.featuredImageId = -1;
                }
            });
        },

        showSpinner: function() {
            this.$el.find('.spinner').removeAttr('style');
        },

        hideSpinner: function() {
            this.$el.find('.spinner').css({ display: 'none' });
        }
    });

    LFM.Views.removeFeaturedView = wp.media.View.extend({
        className: 'featured-remove-featured-confirm',
        template: wp.media.template('featured-remove-featured')
    });

    /* Utils */
    LFM.Utils.formArrayToObj = function(arr) {
        var ret = {};
        _.each(arr, function(item) {
            ret[item.name] = item.value;
        });
        return ret;
    };

    LFM.Utils.doAjax = function(action, data, success, error) {
        var json = JSON.stringify(data);

        params = {
            url: ajaxurl,
            type: 'POST',
            data: {
                action: action,
                data: json
            },
            dataType: 'json',
            success: function(data, textStatus, jqXHR) {
                if (success)
                    success(data, textStatus, jqXHR);
            },
            error: function(jqXHR, textStatus, errorThrown) {
                if (error)
                    error(jqXHR, textStatus, errorThrown);
            }
        }

        $.ajax(params);
    };

    LFM.Utils.getPostId = function() {
        return Number($( '#post_ID' ).val());
    };

    /* Controller(s) */
    LFM.Controller.removeFeaturedMedia = wp.media.controller.State.extend({
        defaults: {
            id: 'remove',
            title: 'Remove featured',
            content: 'remove',
            menu: 'default',
            toolbar: 'remove-featured',
            priority: 120,
            type: 'link',
        },

        initialize: function(options) {
            this.props = new Backbone.Model( this.metadata || { url: '' });
        }
    });

    $(document).ready(function() {
        $('#set-featured-media-button').click(function() {
            if (LFM.fetching)
                return;

            var model = new featuredMediaModel(),
                spinner = $('#wp-content-media-buttons .spinner');

            LFM.fetching = true;
            spinner.show();
            model.fetch({
                success: function(data) {
                    LFM.fetching = false;
                    spinner.hide();

                    var modal,
                        initialViewId = data.get('type') || 'embed-code',
                        option = _.findWhere(LFM.options, { id: initialViewId }),
                        args = {
                            state: option.id,
                            model: model
                        }

                    if (initialViewId == 'gallery') {
                        args.state = 'gallery-edit';
                        args.editing = true;

                        var query = new wp.media.model.Query([], {
                            url: ajaxurl,
                            args: {
                                posts_per_page: -1,
                                post__in: _.map(model.get('gallery'), function(id) { return id; })
                            }
                        });

                        query.on('sync', function() {
                            args.selection = new wp.media.model.Selection(query.models, { multiple: true });
                            modal = new LFM.Views.featuredMediaFrame(args);
                            modal.open();
                            LFM.instances.modal = modal;
                            query.off('sync');
                        });
                        query.fetch();
                    } else {
                        modal = new LFM.Views.featuredMediaFrame(args);
                        modal.open();
                        LFM.instances.modal = modal;
                    }
                }
            });

            return false;
        });
    });
}());
