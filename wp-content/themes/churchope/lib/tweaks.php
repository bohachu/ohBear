<?php
//CLEANUP

/**
 * Removes the media 'From URL' string.
 *
 * @see wp-includes|media.php
 */
if (!function_exists('th_media_view_strings'))
{

	function th_media_view_strings($strings)
	{
		global $admin_menu;
		if ($admin_menu && $admin_menu->isEditThemeSubmenu())
		{
			unset($strings['insertFromUrlTitle']);
		}
		return $strings;
	}

}
add_filter('media_view_strings', 'th_media_view_strings');



// remove CSS from recent comments widget
if (!function_exists('th_remove_recent_comments_style'))
{

	function th_remove_recent_comments_style()
	{
		global $wp_widget_factory;
		if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments']))
		{
			remove_action('wp_head', array($wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style'));
		}
	}

}

// remove CSS from gallery
if (!function_exists('th_gallery_style'))
{

	function th_gallery_style($css)
	{
		return preg_replace("/<style type='text\/css'>(.*?)<\/style>/s", '', $css);
	}

}

if (!function_exists('th_head_cleanup'))
{

	function th_head_cleanup()
	{		
		add_action('wp_head', 'th_remove_recent_comments_style', 1);
		add_filter('gallery_style', 'th_gallery_style');
	}

}
add_action('init', 'th_head_cleanup');

////
//OTHER TWEAKS
////
// we don't need to self-close these tags in html5:
// <img>, <input>
if (!function_exists('th_remove_self_closing_tags'))
{

	function th_remove_self_closing_tags($input)
	{
		return str_replace(' />', '>', $input);
	}

}
add_filter('get_avatar', 'th_remove_self_closing_tags');
add_filter('comment_id_fields', 'th_remove_self_closing_tags');

// set the post revisions to 5 unless the constant
// was set in wp-config.php to avoid DB bloat
if (!defined('WP_POST_REVISIONS'))
	define('WP_POST_REVISIONS', 5);

// allow more tags in TinyMCE including iframes
if (!function_exists('th_change_mce_options'))
{

	function th_change_mce_options($options)
	{
		$ext = 'pre[id|name|class|style],iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src]';
		if (isset($initArray['extended_valid_elements']))
		{
			$options['extended_valid_elements'] .= ',' . $ext;
		}
		else
		{
			$options['extended_valid_elements'] = $ext;
		}
		return $options;
	}

}
add_filter('tiny_mce_before_init', 'th_change_mce_options');

//clean up the default WordPress style tags
if (!function_exists('th_clean_style_tag'))
{

	function th_clean_style_tag($input)
	{
		preg_match_all("!<link rel='stylesheet'\s?(id='[^']+')?\s+href='(.*)' type='text/css' media='(.*)' />!", $input, $matches);
		//only display media if it's print
		$media = $matches[3][0] === 'print' ? ' media="print"' : '';
		return '<link rel="stylesheet" href="' . $matches[2][0] . '"' . $media . '>' . "\n";
	}

}
add_filter('style_loader_tag', 'th_clean_style_tag');

//lightbox replace


if (!function_exists('th_addlightboxrel_replace'))
{

	function th_addlightboxrel_replace($content)
	{
		if (is_singular() && is_main_query())
		{
			global $post;			
			$postID = (!empty($post)) ? $post->ID : '';
			$pattern = "/(<a\s*(?!.*\bdata-pp=)[^>]*) ?(href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")) ?(class=('|\")(.*?)('|\"))?/i";
			$replacement = '$1 href=$3$4.$5$6 data-pp="lightbox[' . $postID . ']" class="autolink lightbox $9" ';
			$content = preg_replace($pattern, $replacement, $content);
		}
		return $content;
	}

}
add_filter('the_content', 'th_addlightboxrel_replace', 11);
add_filter('get_comment_text', 'th_addlightboxrel_replace');
add_filter('prepend_attachment', 'th_addlightboxrel_replace');


//SEO meta

if (!function_exists('th_add_theme_favicon'))
{

	function th_add_theme_favicon()
	{
		if (get_option(SHORTNAME . "_favicon"))
		{
			?>
			<link rel="shortcut icon" href="<?php echo get_option(SHORTNAME . "_favicon"); ?>" />
			<?php
		}
	}

}
add_action('wp_head', 'th_add_theme_favicon');

if (!function_exists('th_default_comments_off'))
{

	function th_default_comments_off($data)
	{
		// each custom post type has default_comments_off method to.
		if ($data['post_type'] == 'page' && $data['post_status'] == 'auto-draft')
		{
			$data['comment_status'] = 0;
			$data['ping_status'] = 0;
		}

		return $data;
	}

}
add_filter('wp_insert_post_data', 'th_default_comments_off');


if (!function_exists('th_imgborder_from_editor'))
{

	function th_imgborder_from_editor($class)
	{
		$class = $class . ' imgborder';
		return $class;
	}

}
add_filter('get_image_tag_class', 'th_imgborder_from_editor');


if (!function_exists('th_default_widgets_init'))
{

	function th_default_widgets_init()
	{
		if (isset($_GET['activated']))
		{
			update_option('sidebars_widgets', array(
				'default-sidebar' => array('search')
			));
		}
	}

}
add_action('widgets_init', 'th_default_widgets_init');



// CUSTOMIZE ADMIN MENU ORDER
if (!function_exists('th_custom_menu_order'))
{

	function th_custom_menu_order($menu_ord)
	{
		if (!$menu_ord)
		{
			return true;
		}
		return array(
			'index.php',
			'separator1',
			'edit.php',
			'edit.php?post_type=page',
			'edit.php?post_type=' . Custom_Posts_Type_Event::POST_TYPE,
			'edit.php?post_type=' . Custom_Posts_Type_Sermon::POST_TYPE,
			'edit.php?post_type=' . Custom_Posts_Type_Gallery::POST_TYPE,
			'edit.php?post_type=' . Custom_Posts_Type_Slideshow::POST_TYPE,
			'edit.php?post_type=' . Custom_Posts_Type_Testimonial::POST_TYPE,
			'revslider',
			'separator2',
			SHORTNAME . '_general',
			'separator-last'
		);
	}

}

add_filter('custom_menu_order', 'th_custom_menu_order');
add_filter('menu_order', 'th_custom_menu_order');

// CUSTOM USER PROFILE FIELDS
if (!function_exists('th_custom_userfields'))
{

	function th_custom_userfields($contactmethods)
	{
		// ADD CONTACT CUSTOM FIELDS

		$contactmethods['contact_facebook'] = __('Facebook', 'churchope');
		$contactmethods['contact_phone_office'] = __('Office Phone', 'churchope');
		$contactmethods['contact_phone_mobile'] = __('Mobile Phone', 'churchope');
		$contactmethods['contact_office_fax'] = __('Office Fax', 'churchope');

		// ADD ADDRESS CUSTOM FIELDS
		$contactmethods['address_line_1'] = __('Address Line 1', 'churchope');
		$contactmethods['address_line_2'] = __('Address Line 2 (optional)', 'churchope');
		$contactmethods['address_city'] = __('City', 'churchope');
		$contactmethods['address_state'] = __('State', 'churchope');
		$contactmethods['address_zipcode'] = __('Zipcode', 'churchope');
		return $contactmethods;
	}

}

add_filter('user_contactmethods', 'th_custom_userfields', 10, 1);


//Remove read more page jump
if (!function_exists('th_remove_more_jump_link'))
{

	function th_remove_more_jump_link($link)
	{
		$offset = strpos($link, '#more-');
		if ($offset)
		{
			$end = strpos($link, '"', $offset);
		}
		if ($end)
		{
			$link = substr_replace($link, '', $offset, $end - $offset);
		}
		return $link;
	}

}
add_filter('the_content_more_link', 'th_remove_more_jump_link');

//remove pings to self
if (!function_exists('th_no_self_ping'))
{

	function th_no_self_ping(&$links)
	{
		$home = home_url();
		foreach ($links as $l => $link)
		{
			if (0 === strpos($link, $home))
				unset($links[$l]);
		}
	}

}
add_action('pre_ping', 'th_no_self_ping');

// customize admin footer text
if (!function_exists('th_custom_admin_footer'))
{

	function th_custom_admin_footer()
	{
		echo 'Copyrighted by ' . get_option('blogname') . '. | Developed by <a href="http://themoholics.com" title="WordPress Premium Themes" >Themoholics</a>.';
	}

}
add_filter('admin_footer_text', 'th_custom_admin_footer');

//
if (!function_exists('th_new_excerpt_more'))
{

	function th_new_excerpt_more($more)
	{
		return '...';
	}

}
add_filter('excerpt_more', 'th_new_excerpt_more');

//excerpt length
if (!function_exists('excerpt'))
{

	function excerpt($num)
	{
		$limit = $num + 1;
		$original_excerpt = get_the_excerpt();

		$cleaned = $text = preg_replace('|\[(.+?)\](.+?\[/\\1\])?|s', '', $original_excerpt);
		$excerpt = mb_substr($cleaned, 0, $limit);

		if (mb_strlen($original_excerpt) > mb_strlen($excerpt))
			$excerpt .= "...";

		echo $excerpt;
	}

}

// Theme default avatar

if (!function_exists('th_newgravatar'))
{

	function th_newgravatar($avatar_defaults)
	{
		$myavatar = get_template_directory_uri() . '/images/noava.png';
		$avatar_defaults[$myavatar] = THEMENAME;
		return $avatar_defaults;
	}

}
add_filter('avatar_defaults', 'th_newgravatar');


//Responsive twitter
if (!function_exists('th_twitter_oembed_hotfix'))
{

	function th_twitter_oembed_hotfix($html, $data, $url)
	{
		// remove the blockquote width attribute and value from the twitter oembed html response and return the filtered version
		$html = str_ireplace('<blockquote class="twitter-tweet" width="550">', '<blockquote class="twitter-tweet">', $html);
		return $html;
	}

}
add_filter('embed_oembed_html', 'th_twitter_oembed_hotfix', 10, 3);


if (!function_exists('th_the_content'))
{

	function th_the_content($content)
	{
		/**
		 * @see http://wordpress.stackexchange.com/questions/9667/get-wordpress-post-content-by-post-id
		 */
		$content = wpautop($content);
		$content = do_shortcode($content);
		$content = str_replace(']]>', ']]&gt;', $content);
		return $content;
	}

}

if (!function_exists('ajax_captcha_check'))
{

	function ajax_captcha_check()
	{
		require_once get_template_directory() . '/lib/recaptchalib.php';
		$resp = recaptcha_check_answer(
				get_option(SHORTNAME . '_captcha_private_key'), $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);

		header('Content-Type: application/json');
		die(json_encode($resp));
	}

}
add_action('wp_ajax_captcha_check', 'ajax_captcha_check');
add_action('wp_ajax_nopriv_captcha_check', 'ajax_captcha_check');

if (!function_exists('th_ajax_calendar_walker'))
{

	function th_ajax_calendar_walker()
	{
		require_once get_template_directory() . '/lib/shortcode/eventsCalendar.php';
		die();
	}

}
add_action('wp_ajax_calendar_walker', 'th_ajax_calendar_walker');
add_action('wp_ajax_nopriv_calendar_walker', 'th_ajax_calendar_walker');

if (!function_exists('th_ajax_send_contact_form'))
{

	function th_ajax_send_contact_form()
	{
		require_once get_template_directory() . '/lib/shortcode/contactForm/contactsend.php';
		die();
	}

}
add_action('wp_ajax_send_contact_form', 'th_ajax_send_contact_form');
add_action('wp_ajax_nopriv_send_contact_form', 'th_ajax_send_contact_form');

if (!function_exists('th_recaptcha_get_html'))
{

	function th_recaptcha_get_html($error = null)
	{
		if (get_option(SHORTNAME . '_captcha_private_key'))
		{
			if ($publickey = get_option(SHORTNAME . '_captcha_public_key'))
			{
				require_once get_template_directory() . '/lib/recaptchalib.php';
				return recaptcha_get_html($publickey, $error, is_ssl());
			}
		}
		return '';
	}

}

add_action('admin_footer-edit-tags.php', 'th_remove_speaker_description');

function th_remove_speaker_description()
{
	global $current_screen;
	if ($current_screen->taxonomy == Custom_Posts_Type_Sermon::TAXONOMY_SPEAKER):
		?>
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				$('#tag-description, .form-table #description').closest('.form-field').remove();
			});
			jQuery(window).load(function() {
				jQuery('#addtag iframe').contents().find('body').blur(function() {
					if (typeof tinyMCE != 'undefined') {
						tinyMCE.triggerSave(true);
					}
				});
			});
		</script>
		<?php
	endif;
}
?>