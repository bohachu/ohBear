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
		<title><?php _e('Blog', 'churchope'); ?></title>
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
				var shortcode = '';
				var pagination = '';
	//		var selectedContent = tinyMCE.activeEditor.selection.getContent();				
				var blog_category = jQuery('#blog_category').val();
				var perpage = jQuery('#perpage').val();

				if (jQuery('#pagination').is(':checked'))
				{
					pagination = jQuery('#pagination:checked').val();
				}

				if (blog_category)
				{
					shortcode = ' [blog category="' + blog_category.join(',') + '" perpage="' + perpage + '" pagination="' + pagination + '" ]';
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
		<form name="blog" action="#" >
			<div class="tabs">
				<ul>
					<li id="blog_tab" class="current"><span><a href="javascript:mcTabs.displayTab('blog_tab','blog_panel');" onMouseDown="return false;"><?php _e('Blog', 'churchope'); ?></a></span></li>
				</ul>
			</div>
			<div class="panel_wrapper">

				<fieldset style="margin-bottom:10px;padding:10px">
					<legend><?php _e('Category of blog:', 'churchope'); ?></legend>
					<label for="blog_category"><?php _e('Choose a category:', 'churchope'); ?></label><br><br>
					<select name="blog_category" id="blog_category"  style="width:250px" MULTIPLE SIZE=5>
<?php
$categories = get_categories();
foreach ($categories as $category)
{
	$option = '<option value="' . $category->term_id . '">';
	$option .= $category->cat_name;
	$option .= ' (' . $category->category_count . ')';
	$option .= '</option>';
	echo $option;
}
?>

					</select>					
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