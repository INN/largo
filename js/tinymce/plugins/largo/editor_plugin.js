(function() {
        tinymce.create('tinymce.plugins.largo', {
                /**
                 * Initializes the plugin, this will be executed after the plugin has been created.
                 * This call is done before the editor instance has finished it's initialization so use the onInit event
                 * of the editor instance to intercept that event.
                 *
                 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
                 * @param {string} url Absolute URL to where the plugin is located.
                 */
                init : function(ed, url) {
                        // Register the command so that it can be invoked by using tinyMCE.activeEditor.execCommand('mceExample');
                        ed.addCommand('modulize', function() {

                                ed.windowManager.open({
                                        file : url + '/window.php?wpbase=' + userSettings.url,
                                        width : 320,
                                        height : 200,
                                        inline : 1
                                }, {
                                        plugin_url : url
                                });
                        });

                        // Register example button
                        ed.addButton('modulize', {
                                title : 'Module Wrapper',
                                cmd : 'modulize',
                                image : url + '/module.png'
                        });

                        // Add a node change handler, selects the button in the UI when a image is selected
                        ed.onNodeChange.add(function(ed, cm, n) {
                                cm.setActive('modulize', n.nodeName == 'IMG');
                        });
                },

                /**
                 * Creates control instances based in the incomming name. This method is normally not
                 * needed since the addButton method of the tinymce.Editor class is a more easy way of adding buttons
                 * but you sometimes need to create more complex controls like listboxes, split buttons etc then this
                 * method can be used to create those.
                 *
                 * @param {String} n Name of the control to create.
                 * @param {tinymce.ControlManager} cm Control manager to use inorder to create new control.
                 * @return {tinymce.ui.Control} New control instance or null if no control was created.
                 */
                createControl : function(n, cm) {
                        return null;
                },

                /**
                 * Returns information about the plugin as a name/value array.
                 * The current keys are longname, author, authorurl, infourl and version.
                 *
                 * @return {Object} Name/value array containing information about the plugin.
                 */
                getInfo : function() {
                        return {
                                longname : 'Argo Plugin',
                                author : 'Marc Lavallee',
                                authorurl : 'http://argoproject.org/',
                                infourl : 'http://argoproject.org/',
                                version : "1.0"
                        };
                }
        });

        // Register plugin
        tinymce.PluginManager.add('modulize', tinymce.plugins.largo);
})();

