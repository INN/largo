<?php
$SITEURL = ( $_SERVER[ 'HTTPS' ] ) ? 'https://' : 'http://';
$SITEURL .= $_SERVER[ 'HTTP_HOST' ] or $_SERVER[ 'SERVER_NAME' ];
$SITEURL .= $_GET[ 'wpbase' ];
?>

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title>Wrap content in module</title>
    <script language="javascript" type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $SITEURL; ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $SITEURL; ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
    <script language="javascript" type="text/javascript" src="<?php echo $SITEURL; ?>/wp-content/themes/largo/js/tinymce/plugins/argo/tinymce.js"></script>

</head>
<body onload="tinyMCEPopup.executeOnLoad('init();)'); document.body.style.display='';">
<form>
    <b>Alignment:</b><br />
    <ul>
        <li><input type="radio" name="mod_align" value="left" checked> Left</li>
        <li><input type="radio" name="mod_align" value="right"> Right</li>
        <li><input type="radio" name="mod_align" value="center"> Center</li>
    </ul>
    <b>Width:</b><br />
    <ul>
        <li><input type="radio" name="mod_width" value="half" checked> Half</li>
        <li><input type="radio" name="mod_width" value="full"> Full</li>
    </ul>
    <b>Type:</b><br />
    <ul>
        <li><input type="radio" name="mod_type" value="aside" checked> Aside</li>
        <li><input type="radio" name="mod_type" value="pull-quote"> Pull Quote</li>
    </ul>


    <div class="mceActionPanel">
        <div style="float: left">
            <input type="button" id="cancel" name="cancel" value="Cancel" onclick="tinyMCEPopup.close();" />
        </div>
        <div style="float: right">
            <input type="submit" id="insert" name="insert" value="Insert" onclick="insertModuleCode();" />
        </div>
    </div>
</form>
</body>
</html>

