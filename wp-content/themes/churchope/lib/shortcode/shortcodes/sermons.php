<?php
defined('WP_ADMIN') || define('WP_ADMIN', true);
$_SERVER['PHP_SELF'] = '/wp-admin/index.php';
require_once('../../../../../../wp-load.php');
?>
<!doctype html>
<html lang="en">
	<head>
		<title><?php _e('Sermon', 'churchope'); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/tinymce/tiny_mce_popup.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/tinymce/utils/mctabs.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/tinymce/utils/form_utils.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/jquery/jquery.js?ver=1.4.2"></script>
		<script language="javascript" type="text/javascript">
			function init() {

				tinyMCEPopup.resizeToInnerSize();
			}
			function submitData() {
				var shortcode = '[sermons ';
				var sermon_cat = jQuery('#sermon_cat').val();
				var perpage = jQuery('#perpage').val();
				var pagination = jQuery('#pagination').is(':checked')


				if (sermon_cat)
				{
					shortcode += 'category="' + sermon_cat.join(',') + '" ';
				}
				if (perpage)
				{
					shortcode += ' perpage="' + perpage + '" ';
				}

				if (pagination) {
					shortcode += 'pagination="1" ';
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
	</head>
	<body  onload="init();">
		<form name="sermon" action="#" >
			<div class="tabs">
				<ul>
					<li id="sermon_tab" class="current"><span><a href="javascript:mcTabs.displayTab('sermon_tab','sermon_panel');" onMouseDown="return false;"><?php _e('sermon', 'churchope'); ?></a></span></li>
				</ul>
			</div>
			<div class="panel_wrapper">
				<fieldset style="margin-bottom:10px;padding:10px">
					<legend><?php _e('Category of sermon:', 'churchope'); ?></legend>
					<label for="sermon_cat"><?php _e('Choose a category:', 'churchope'); ?></label><br><br>
					<select name="sermon_cat" id="sermon_cat"  style="width:250px" MULTIPLE SIZE=5>
						<?php
						$categories = get_terms(Custom_Posts_Type_Sermon::TAXONOMY);

						foreach ($categories as $category)
						{
							$option = '<option value="' . $category->term_id . '">';
							$option .= $category->name;
							$option .= ' (' . $category->count . ')';
							$option .= '</option>';
							echo $option;
						}
						?>
					</select>
					<br/>
					<?php _e('* if no one selected, then selected ALL', 'churchope'); ?>
				</fieldset>
				<fieldset style="margin-bottom:10px;padding:10px">
					<legend><?php _e('Show per page:', 'churchope'); ?></legend>
					<label for="perpage"><?php _e('Number to show:', 'churchope'); ?></label><br><br>
					<input name="perpage" type="text" id="perpage" style="width:250px">
				</fieldset>
				<fieldset style="margin-bottom:10px;padding:10px">
					<legend><?php _e('Pagination:', 'churchope'); ?></legend>
					<label for="pagination"><?php _e('Check if you want show pagination:', 'churchope'); ?></label><br><br>
					<input name="pagination" type="checkbox" id="pagination">
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