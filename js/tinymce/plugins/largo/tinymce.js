function init() {	//this doesn't seem to actually be getting called, despite what's in window.php?
	tinyMCEPopup.resizeToInnerSize();
}

function insertModuleCode(){
	var inst = tinyMCE.getInstanceById('content'),
		html = inst.selection.getContent(),
		mod_width = $("input[name='mod_width']:checked").val(),
		mod_align = $("input[name='mod_align']:checked").val(),
		mod_type  = $("input[name='mod_type']:checked").val(),
		embed_style = "",
		start_tag = '<aside class="module align-' + mod_align + ' ' + mod_width + ' type-' + mod_type + '" ' + embed_style + '>',
		end_tag = '</aside>';

	if (mod_type == 'embed') {
		if  (mod_width == 'extract') {
			var dims = getEmbedDimensions( html );
			if (dims) embed_style = 'style="' + dims + '"';
		} else {
			mod_type  += " embed-container";
		}
		if (/^\s*(https?:\/\/[^\s"]+)\s*$/im.test(html)) {
			start_tag += "[embed]";
			end_tag = '[/embed]' + end_tag;
		}
	}

	window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, start_tag + html + end_tag);
	tinyMCEPopup.editor.execCommand('mceRepaint');
	tinyMCEPopup.close();
	return;
}

//try to extract a width and height value from the markup
function getEmbedDimensions(markup){

	var style = "";
	//look for a width
	var widtharray = /width=('|")([0-9]*)('|")/im.exec(markup);
	if (widtharray.length == 4) {
		style = style + "overflow:hidden;width:" + widtharray[2] + "px;";
	} else {
		return false;
	}

	//then a height, too, just because
	var heightarray = /height=('|")([0-9]*)('|")/im.exec(markup);
	if (heightarray.length == 4) {
		style = style + "height:" + heightarray[2] + "px;";
	}
	return style;
}

/**
 * Some UX improvements for embedded media
 */
jQuery(document).ready(function($) {

	//watch the mod_type and trigger the embed change
	$("input[name='mod_type']").click(function() {
		widthToggle();
	});

	function widthToggle() {
		$ex = $("#extract");
		if ( $("input[name='mod_type']:checked").val() == 'embed')
			$ex.removeAttr('disabled');
		else {
			//uncheck it
			if ( $ex.prop('checked') ) {
				$ex.removeAttr('checked');
				console.log( $("#half") );
				$("#half").prop('checked','checked');
			}
			//disable it
			$ex.attr('disabled','disabled');
		}
	}

	widthToggle();
});