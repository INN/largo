(function() {
	tinymce.create('tinymce.plugins.largo', {
			/**
			 * Initializes the plugin, this will be executed after the plugin has been created.
			 * This call is done before the editor instance has finished its initialization so use the onInit event
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
						height : 320,
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

				// Add a node change handler, selects the button in the UI when an aside is selected
				// TO DO: only do this stuff if the aside's class is "module"
				// TO DO: pass the markup to the form in way that options are pre-set
				ed.onNodeChange.add(function(ed, cm, n) {
					if (n.parentNode.nodeName == 'ASIDE') {
						//select the aside instead
						ed.selection.select(n.parentNode);
						cm.setActive('modulize', true);
					} else {
						cm.setActive('modulize', false);
					}
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
					longname : 'Largo Plugin',
					author : 'Investigative News Network',
					authorurl : 'http://largoproject.org/',
					infourl : 'http://largoproject.org/',
					version : "1.0"
				};
			}
	});

	// Register plugin
	tinymce.PluginManager.add('modulize', tinymce.plugins.largo);
})();

