<?php
defined('WP_ADMIN') || define('WP_ADMIN', true);
$_SERVER['PHP_SELF'] = '/wp-admin/index.php';
require_once('../../../../../../wp-load.php');
?>
<!doctype html>
<html lang="en">
	<head>
		<title><?php _e('Sermon speakers', 'churchope'); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/jquery/jquery.js?ver=1.7.1"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/tinymce/tiny_mce_popup.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/tinymce/utils/mctabs.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/tinymce/utils/form_utils.js"></script>
		<script language="javascript" type="text/javascript">
			function init() {
				tinyMCEPopup.resizeToInnerSize();
			}
			function submitData() {
				var shortcode;
				var category = jQuery('#category').val();
				var orderby = jQuery('#orderby').val();
				shortcode = '[speakers';
				if (category && category.length)
				{
					shortcode += ' category="' + category + '"';
				}

				if (orderby && orderby.length && orderby != 'name') // name is default criteria
				{
					shortcode += ' sortby="' + orderby + '"';
				}

				if (jQuery('#avatar').is(':checked')) {
					shortcode += ' hide_avatar="1"';
				}

				shortcode += ']';

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
		<link rel="stylesheet" type="text/css" media="all" href="<?php echo get_template_directory_uri() . '/backend/css/admin.css' ?>" />
		<base target="_self" />
	</head>
	<body  onload="init();">
		<form name="speakers" action="#" >
			<div class="tabs">
				<ul>
					<li id="speaker_ta" class="current"><span><a href="javascript:mcTabs.displayTab('speaker_tab','speakers_panel');" onMouseDown="return false;"><?php _e('speakers', 'churchope'); ?></a></span></li>
				</ul>
			</div>
			<div class="panel_wrapper">
				<fieldset style="margin-bottom:10px;padding:10px">
					<legend><?php _e('Sermon speakers:', 'churchope'); ?></legend>
					<label for="category"><?php _e('Choose a speaker:', 'churchope'); ?></label><br><br>
					<select name="category" id="category"  style="width:250px" MULTIPLE SIZE=5>
						<?php
						$terms_list = get_terms(Custom_Posts_Type_Sermon::TAXONOMY_SPEAKER);
						foreach ($terms_list as $term)
						{
							$option = '<option value="' . $term->term_id . '">';
							$option .= $term->name;
							$option .= ' (' . $term->count . ')';
							$option .= '</option>';
							echo $option;
						}
						?>
					</select>
					<br/>
<?php _e('* if no one selected, then selected ALL', 'churchope'); ?>
				</fieldset>
				<fieldset style="margin-bottom:10px;padding:10px">
					<legend><?php _e('Sort by:', 'churchope'); ?></legend>
					<label for="orderby"><?php _e('Choose a sort by criteria:', 'churchope'); ?></label><br><br>
					<select name="orderby" id="orderby"  style="width:250px">
						<option value="name"><?php _e('Name', 'churchope'); ?></option>
						<option value="count"><?php _e('Count', 'churchope'); ?></option>
					</select>
				</fieldset>
				<fieldset style="margin-bottom:10px;padding:10px">
					<legend><?php _e('Avatar option:', 'churchope'); ?></legend>
					<label for="avatar"><?php _e('Check if you want disable speaker avatar', 'churchope'); ?></label><br><br>
					<input name="avatar" type="checkbox" id="avatar">
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