<?php
defined('WP_ADMIN') || define('WP_ADMIN', true);
$_SERVER['PHP_SELF'] = '/wp-admin/index.php';
require_once('../../../../../../wp-load.php');
if (get_option(SHORTNAME . "_customcolor") != '')
{
	$customcolor = get_option(SHORTNAME . "_customcolor");
}
else
{
	$customcolor = "#00a0c6";
}
?>
<!doctype html>
<html lang="en">
	<head>
		<title><?php _e('Sermon Schedule', 'churchope'); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
		<link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/lib/metabox/style.css">

		<?php theme_time_format(); ?>

		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/tinymce/tiny_mce_popup.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/tinymce/utils/mctabs.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/tinymce/utils/form_utils.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo includes_url(); ?>/js/jquery/jquery.js"></script>
		<script language="javascript" type="text/javascript" src="<?php echo get_template_directory_uri(); ?>/lib/metabox/js/jquery.timePicker.min.js"></script>
		<script language="javascript" type="text/javascript">
			jQuery(document).ready(function() {
				jQuery('.text_time').timePicker({
					show24Hours: time_24_format,
					separator: ':',
					step: 30
				});
			});
		</script>


		<script language="javascript" type="text/javascript">
			function init() {

				tinyMCEPopup.resizeToInnerSize();
			}
			function submitData() {
				var shortcode = '';
				//		var selectedContent = tinyMCE.activeEditor.selection.getContent();				
				var schedule_category = jQuery('#schedule_category').val();
				var title = jQuery('#title').val();
				var time = jQuery('#time').val();
				var content = jQuery('#content').val();

				if (schedule_category)
				{
					shortcode = ' [schedule title="' + title + '" speaker="' + schedule_category + '" time="' + time + '"]' + content + '[/schedule]';
				}

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
		<?php
		if (!get_option(SHORTNAME . "_gfontdisable"))
		{
			?>
			<link href='//fonts.googleapis.com/css?family=<?php
			$gfont = array();
			if (get_option(SHORTNAME . "_preview") != "")
			{
				if (isset($_SESSION[SHORTNAME . '_gfont']))
				{
					$gfont[] = stripslashes($_SESSION[SHORTNAME . '_gfont']);
				}
			}
			else
			{

				$gfont[] = get_option(SHORTNAME . '_gfont');
			}

			if (!count($gfont))
			{
				//default style
				$gfont[] = '[{"family":"Open Sans","variants":["300italic","400italic","600italic","700italic","800italic","400","300","600","700","800"],"subsets":[]}]';
			}

			echo Admin_Theme_Element_Select_Gfont::font_queue($gfont);
			?>' rel='stylesheet' type='text/css'>
		<?php } ?>
		<base target="_self" />
	</head>
	<body  onload="init();">

		<form name="schedule" action="#" >
			<div class="tabs">
				<ul>
					<li id="schedule_tab" class="current"><span><a href="javascript:mcTabs.displayTab('schedule_tab','schedule_panel');" onMouseDown="return false;"><?php _e('Blog', 'churchope'); ?></a></span></li>
				</ul>
			</div>
			<div class="panel_wrapper">


				<fieldset style="margin-bottom:10px;padding:10px">
					<legend><?php _e('Sermon Speaker:', 'churchope'); ?></legend>
					<label for="schedule_category"><?php _e('Choose a Speaker:', 'churchope'); ?></label><br><br>
					<select name="schedule_category" id="schedule_category"  style="width:250px">
						<?php
						$cat_args = array(
							'hide_empty' => false,
						);
						$categories = get_terms(Custom_Posts_Type_Sermon::TAXONOMY_SPEAKER, $cat_args);
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
				</fieldset>

				<fieldset style="margin-bottom:10px;padding:10px">
					<legend><?php _e('Part Title:', 'churchope'); ?></legend>
					<label for="title"><?php _e('Sermon Part Title:', 'churchope'); ?></label><br><br>
					<input name="title" type="text" id="title" style="width:250px">
				</fieldset>
				<fieldset style="margin-bottom:10px;padding:10px">
					<legend><?php _e('Time:', 'churchope'); ?></legend>
					<label for="time"><?php _e('Time:', 'churchope'); ?></label><br><br>
					<input class="cmb_timepicker text_time" type="text" name="time" id="time" value="" autocomplete="OFF" size="10">
				</fieldset>
				<fieldset style="margin-bottom:10px;padding:10px">
					<legend><?php _e('Content:', 'churchope'); ?></legend>
					<label for="content"><?php _e('Content:', 'churchope'); ?></label><br><br>
					<textarea id="content" rows="6" cols="45"></textarea>
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