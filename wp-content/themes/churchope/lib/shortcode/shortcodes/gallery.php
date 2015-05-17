<?php
defined('WP_ADMIN') || define('WP_ADMIN', true);
$_SERVER['PHP_SELF'] = '/wp-admin/index.php';
require_once('../../../../../../wp-load.php');
?>
<!doctype html>
<html lang="en">
	<head>
		<title><?php _e('Gallery', 'churchope'); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/tinymce/tiny_mce_popup.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/tinymce/utils/mctabs.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/tinymce/utils/form_utils.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/jquery/jquery.js?ver=1.4.2"></script>
		<script language="javascript" type="text/javascript">
			var perpage = '';
			function init() {

				tinyMCEPopup.resizeToInnerSize();

				jQuery('#type').on('change', function () {
					if (jQuery(this).val() === 'filterable') {
						jQuery('#perpage_wrap').hide();
						perpage = '';
					} else {
						jQuery('#perpage_wrap').show();
						
					}
				});

			}
			function submitData() {
				var shortcode;
				//		var selectedContent = tinyMCE.activeEditor.selection.getContent();				
				var taxonomy_terms = jQuery('#taxonomy_terms').val();
				if (jQuery('#type').val() === 'pagination') {
					perpage = 'perpage="' + jQuery('#perpage').val() + '"';
				}
				var layout = jQuery('#layout_type').val();
				var type = '';
				if (jQuery('#type').val() === 'filterable') {
					type = ' isotope="on"';
				} else {
					type = ' pagination="on"';
				}
				shortcode = ' [terms_gallery terms="' + taxonomy_terms + '" ' + perpage + '  layout="' + layout + '" ' + type + ']';

				if (window.tinyMCE) {
					var id;
					var tmce_ver = window.tinyMCE.majorVersion;
					if (typeof tinyMCE.activeEditor.editorId != 'undefined')
					{
						id = tinyMCE.activeEditor.editorId;
					}
					else
					{
						id = 'content';
					}
					if (tmce_ver >= "4") {
						window.tinyMCE.execCommand('mceInsertContent', false, shortcode);
					} else {
						window.tinyMCE.execInstanceCommand(id, 'mceInsertContent', false, shortcode);
					}

					tinyMCEPopup.editor.execCommand('mceRepaint');
					tinyMCEPopup.close();
				}

				return;
			}
		</script>
		<base target="_self" />
	</head>
	<body  onload="init();">
		<form name="gallery" action="#" >
			<div class="tabs">
				<ul>
					<li id="gallery_tab" class="current"><span><a href="javascript:mcTabs.displayTab('gallery_tab','gallery_panel');" onMouseDown="return false;"><?php _e('Gallery', 'churchope'); ?></a></span></li>
				</ul>
			</div>
			<div class="panel_wrapper">

				<fieldset style="margin-bottom:10px;padding:10px">
					<legend><?php _e('Taxonomy terms:', 'churchope'); ?></legend>
					<label for="taxonomy_terms"><?php _e('Choose a taxonomy terms:', 'churchope'); ?></label><br><br>

					<?php wp_dropdown_categories('name=taxonomy_terms&id=taxonomy_terms&show_count=1&hierarchical=1&taxonomy=' . Custom_Posts_Type_Gallery::TAXONOMY); ?>
				</fieldset>
				<fieldset style="margin-bottom:10px;padding:10px">
					<legend>Filterable gallery:</legend>
					<label for="type">Choose if you want use filterable gallery or with pagination:</label><br><br>
					<select name="type" id="type"  style="width:250px">						
						<option value="filterable" selected="selected"><?php _e('Filterable', 'churchope'); ?></option>
						<option value="pagination"><?php _e('Pagination', 'churchope'); ?></option>								
					</select>	
				</fieldset>
				<fieldset style="margin-bottom:10px;padding:10px;display: none;" id="perpage_wrap">
					<legend><?php _e('Show per page:', 'churchope'); ?></legend>
					<label for="perpage"><?php _e('Number to show:', 'churchope'); ?></label><br><br>
					<input name="perpage" type="text" id="perpage" style="width:250px">
				</fieldset>


				<fieldset style="margin-bottom:10px;padding:10px">
					<legend><?php _e('Layout Type:', 'churchope'); ?></legend>
					<label for="layout_type"><?php _e('Choose a layout type:', 'churchope'); ?></label><br><br>
					<select name="layout_type" id="layout_type"  style="width:250px">
						<option value=""><?php _e('Big', 'churchope'); ?></option>
						<option value="medium"><?php _e('Medium', 'churchope'); ?></option>	
						<option value="small"><?php _e('Small', 'churchope'); ?></option>						
					</select>					
				</fieldset>
			</div>
			<div class="mceActionPanel">
				<div style="float: right">
					<input type="submit" id="insert" name="insert" value="<?php _e('Insert', 'churchope'); ?>" onClick="submitData();" />
				</div>
			</div>
		</form>
	</body>
</html>