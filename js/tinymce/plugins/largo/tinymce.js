function init() {
    tinyMCEPopup.resizeToInnerSize();
}

function insertModuleCode(){
    var inst = tinyMCE.getInstanceById('content');
    var html = inst.selection.getContent();

    var mod_width = $("input[name='mod_width']:checked").val();
    var mod_align = $("input[name='mod_align']:checked").val();
    var mod_type  = $("input[name='mod_type']:checked").val();

    var start_tag = '[module align="' + mod_align + '" width="' + mod_width + '" type="' + mod_type + '"]';

    window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, start_tag+html+'[/module]');
    tinyMCEPopup.editor.execCommand('mceRepaint');
    tinyMCEPopup.close();
    return;
}
