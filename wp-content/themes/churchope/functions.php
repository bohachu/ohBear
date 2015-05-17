<?php
$customize_iterator = 1;
//Defines
define('SHORTNAME', 'ch');   // Required!!
define('THEMENAME', 'Churchope'); // Required!!
define('TEXTDOMAIN', 'churchope'); // Required!!

define('ICL_AFFILIATE_ID', 7410);
define('ICL_AFFILIATE_KEY', '52286484063b643175cdfd8e743f1448');
defined('CLASS_DIR_PATH') || define('CLASS_DIR_PATH', get_template_directory() . '/classes/'); // Path to classes folder in Theme

include "wpml-integration.php";
$themename = "Churchope";

$adminmenuname = __('Theme Options', 'churchope');

/**
 * Class autoloader function
 * @param string $class Class name to load
 * @return boolean
 */
if (!function_exists('wp_auto_loader'))
{

	function wp_auto_loader($class)
	{
		$theme_class_path = CLASS_DIR_PATH . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
		if (!class_exists($class))
		{
			if (file_exists($theme_class_path) && is_readable($theme_class_path))
			{
				include_once($theme_class_path);
				return true;
			}
		}
		return false;
	}

}
spl_autoload_register('wp_auto_loader');

$themeicon = get_template_directory_uri() . '/backend/img/themoholics.gif';

//*** THEME ADMIN OBJECT ****//

$admin_menu = new Admin_Theme_Menu(__('Theme Options', 'churchope'));
$admin_menu->setMenuSlug(SHORTNAME . '_general')
		->setAdminMenuName($adminmenuname)
		->setIconUrl($themeicon);

// Load admin options
locate_template(array('backend/setup.php'), true, true);

// Load metabox
locate_template(array('lib/metabox/functions.php'), true, true);

locate_template(array('lib/shortcode/shortcodes.php'), true, true);
locate_template(array('lib/tweaks.php'), true, true);
locate_template(array('customize.php'), true, true);


// hack to except conflict with revslider plugin
if (!in_array('revslider/revslider.php', apply_filters('active_plugins', get_option('active_plugins'))))
{
	locate_template(array('lib/revslider/revslider.php'), true, true);
}


/**
 * Custom images size
 */
$theme_images_size = new Custom_Thumbnail(); // varible name use in theme_post_thumbnail function
$theme_images_size->addThemeImageSize('recent_posts', 75, 50, Custom_Thumbnail::REMOVE_ON_CHANGE, 1)
		->addThemeImageSize('gallery_widget', 124, 124, Custom_Thumbnail::REMOVE_ON_CHANGE, 1)
		->addThemeImageSize('recent_sermons', 75, 60, Custom_Thumbnail::REMOVE_ON_CHANGE, 1)
		->addThemeImageSize('cycle_side', 350, 235, Custom_Thumbnail::REMOVE_ON_CHANGE, 1)
		->addThemeImageSize('teaser-thumbnail', 928, 440, Custom_Thumbnail::REMOVE_ON_CHANGE, 1)
		->addThemeImageSize('cycle_full', 830, 275, Custom_Thumbnail::REMOVE_ON_CHANGE, 1)
		->addThemeImageSize('gallery_big', 600, 222, Custom_Thumbnail::REMOVE_ON_CHANGE, 1)
		->addThemeImageSize('gallery_small', 272, 220, Custom_Thumbnail::REMOVE_ON_CHANGE, 1)
		->addThemeImageSize('blog_shortcode', 150, 100, Custom_Thumbnail::REMOVE_ON_CHANGE, 1)
		->addThemeImageSize('blog_thumbnail', 928, 356, Custom_Thumbnail::REMOVE_ON_CHANGE, 1)
		->addThemeImageSize('sermon_speaker', 100, 100, Custom_Thumbnail::REMOVE_ON_CHANGE, 1);

/**
 * adding custom page type
 */
$slideshow = new Custom_Posts_Type_Slideshow();
$slideshow->run();

$gallery = new Custom_Posts_Type_Gallery();
$gallery->run();

$testimonial = new Custom_Posts_Type_Testimonial();
$testimonial->run();


$event = new Custom_Posts_Type_Event();
$event->run();

$sermon = new Custom_Posts_Type_Sermon();
$sermon->run();

/**
 * Adding custom meta box to post category.
 */
$custom_category_meta = new Custom_MetaBox_Item_Category();
$custom_category_meta->run();

/**
 * Adding custom meta box to post tag.
 */
$custom_tag_met = new Custom_MetaBox_Item_Tag();
$custom_tag_met->run();




//theme update check
$envato_username = get_option(SHORTNAME . "_envato_nick");
$envato_api = get_option(SHORTNAME . "_envato_api");

if ($envato_username && $envato_api)
{
	Envato_Theme_Updater::init($envato_username, $envato_api, 'themoholics');
}

if (!function_exists('th_session_admin_init'))
{

	function th_session_admin_init()
	{

		if (get_option(SHORTNAME . "_preview") && !session_id())
		{
			session_start();
			if (isset($_POST['use_session_values']) && $_POST['use_session_values'] == 1)
			{
				foreach ($_POST as $name => $value)
				{
					$_SESSION[$name] = $value;
				}
			}
			elseif (isset($_POST['reset_session_values']) && $_POST['reset_session_values'] == 1)
			{
				session_unset();
			}
		}
	}

}
add_action('init', 'th_session_admin_init');
/**
 * Do flush_rewrite_rules if slug of custom post type was changed.
 */
if (!function_exists('th_flush_rewrite_rules'))
{

	function th_flush_rewrite_rules()
	{
		if (get_option(SHORTNAME . '_need_flush_rewrite_rules'))
		{
			flush_rewrite_rules();
			delete_option(SHORTNAME . '_need_flush_rewrite_rules');
		}
	}

}
add_action('init', 'th_flush_rewrite_rules');


if (!function_exists('change_avatar_css'))
{

	function change_avatar_css($class)
	{
		$class = str_replace("class='avatar", "class='imgborder ", $class);
		return $class;
	}

}
add_filter('get_avatar', 'change_avatar_css');

/**
 * Remove width & height from avatr html
 */
if (!function_exists('th_get_avatar'))
{

	function th_get_avatar($avatar, $id_or_email, $size, $default, $alt)
	{
		$avatar = preg_replace(array('/\swidth=("|\')\d+("|\')/', '/\sheight=("|\')\d+("|\')/'), '', $avatar);
		return $avatar;
	}

}
add_filter('get_avatar', 'th_get_avatar', 10, 5);


if (!function_exists('th_image_send_to_editor'))
{

	function th_image_send_to_editor($html)
	{
		if (get_option(SHORTNAME . "_responsive"))
		{
			return $html;
		}

		$html = preg_replace(array('/\swidth=("|\')\d+("|\')/', '/\sheight=("|\')\d+("|\')/'), '', $html);

		return $html;
	}

}
add_filter('image_send_to_editor', 'th_image_send_to_editor', 10, 5);

$locationMap = new Locate_Map();

// Custom menus
add_theme_support('menus'); // sidebar

/**
 * Register all theme widgets
 */
add_action('widgets_init', array('Widget', 'run'));

// This theme uses Featured Images (also known as post thumbnails) for per-post/per-page Custom Header images
add_theme_support('post-thumbnails');

//
load_theme_textdomain('churchope', get_template_directory());

add_theme_support('automatic-feed-links');

add_editor_style();

if (!isset($content_width))
	$content_width = 912;

/**
 * add after post content map direction
 */
if (!function_exists('event_map_directions'))
{

	function event_map_directions($content)
	{
		global $post;
		if (is_singular() && isset($post->ID) && get_post_meta($post->ID, Locate_Api_Map::getMetaKey(), true))
		{
			if (get_post_meta($post->ID, SHORTNAME . Locate_Api_Map::MAP_META_KEY_SUFFIX, true))
			{
				$content .= apply_filters('location_map_directions_html', null, null, null);
			}
		}
		return $content;
	}

}
add_filter('the_content', 'event_map_directions');

if (!function_exists('th_register_menus'))
{

	function th_register_menus()
	{
		register_nav_menus(
				array(
					'header-menu' => __('Header Menu', 'churchope'),
					'footer-menu' => __('Footer Menu', 'churchope')
				)
		);
	}

}
add_action('init', 'th_register_menus');

// Print styles
if (!function_exists('th_add_styles'))
{

	function th_add_styles()
	{
		if (!is_admin())
		{
			wp_enqueue_style('main', get_template_directory_uri() . '/css/main.css', '', null, 'all');
			wp_enqueue_style('font-awesome', get_template_directory_uri() . '/css/font-awesome.css', '', null, 'all');
			if (!get_option(SHORTNAME . "_responsive"))
			{
				wp_enqueue_style('media.querias', get_template_directory_uri() . '/css/media.queries.css', '', null, 'all');
			}
			wp_enqueue_style('prettyphoto', get_template_directory_uri() . '/js/prettyphoto/css/prettyPhoto.css', '', null, 'all');
		}

		$custom_stylesheet = new Custom_CSS_Style();
		$custom_stylesheet->run();
		if (!is_admin())
		{
			wp_enqueue_style('retina', get_template_directory_uri() . '/css/retina.css', '', null, 'all');
		}

		wp_enqueue_style('default', get_stylesheet_directory_uri() . '/style.css', '', null, 'all');
	}

}
add_action('wp_enqueue_scripts', 'th_add_styles');

/**
 * Add to DB default settings of theme admin page and
 * try to create custom css/skin.css file if dir is writable
 * @global Admin_Theme_Menu $admin_menu
 */
if (!function_exists('th_theme_switch'))
{

	function th_theme_switch()
	{
		global $admin_menu;
		$admin_menu->themeActivation();

		$custom_stylesheet = new Custom_CSS_Style();
		$custom_stylesheet->themeSetup();

		RevSliderAdmin::onActivate();

		wp_redirect(admin_url("admin.php?page=" . SHORTNAME . "_dummy"));
	}

}
add_action('after_switch_theme', 'th_theme_switch');


if (!function_exists('th_theme_correct_path'))
{

	function th_theme_correct_path()
	{
		if (!isCorrectThemeFolder())
		{
			echo '<div id="message" class="error">';
			echo __("<p><strong>You have installed theme incorrectly!</strong> Please, check instructions at documentation.</p></div>", "churchope");
		}
	}

}
add_action('admin_notices', 'th_theme_correct_path');

/**
 * Check is theme folder is correct
 * example WP_CONTENT_DIR /themes/theme_folder
 */
if (!function_exists('isCorrectThemeFolder'))
{

	function isCorrectThemeFolder()
	{
		$theme_dir_path = get_template_directory();
		$standart_path = WP_CONTENT_DIR . '/themes';
		$theme_dir_name = str_replace(WP_CONTENT_DIR . '/themes', '', $theme_dir_path);
		$theme_dir_name = trim($theme_dir_name, '\\..\/'); // delete sleshes

		$path_info = pathinfo($theme_dir_name);

		if (isset($path_info['dirname']) && $path_info['dirname'] == '.')
		{
			return true;
		}
		return false;
	}

}


//print scripts

if (!function_exists('th_register_scripts'))
{

	function th_register_scripts()
	{
		wp_register_script('modernizer', get_template_directory_uri() . '/js/modernizr.min.js', array('jquery'), null);
		wp_register_script('widget_mailchimp', get_template_directory_uri() . '/js/mailchimp-widget-min.js', array('jquery'), null);
		wp_register_script('th_scripts', get_template_directory_uri() . '/js/script.js', array('jquery'), null, true);
		wp_register_script('th_swipe', get_template_directory_uri() . '/js/swipe.js', array('jquery'), null, true);

		wp_register_script('superfish', get_template_directory_uri() . '/js/superfish/superfish.js', array('jquery'), null, true);
		wp_register_script('validate', get_template_directory_uri() . '/js/jquery.validate.min.js', array('jquery'), null, true); // Shortcode Contact form
		wp_register_script('nextevent', get_template_directory_uri() . '/js/nextevent.js', array('jquery'), null, true);   // Widget Next Event1
		wp_register_script('jcycle', get_template_directory_uri() . '/js/jquery.cycle.all.js', array('jquery'), null, true);  // Slideshow, Widget Testimonials
		wp_register_script('prettyphoto', get_template_directory_uri() . '/js/prettyphoto/js/jquery.prettyPhoto.js', array('jquery'), null, true);  // lightbox
		wp_register_script('isotope', get_template_directory_uri() . '/js/jquery.isotope.min.js', array('jquery'), null, true);  // isotope filterable gallery
		wp_register_script('jplayer', get_template_directory_uri() . '/js/jquery.jplayer.min.js', array('jquery'), null, true);  // audio jPlayer
		wp_register_script('th_sermonmedia', get_template_directory_uri() . '/js/sermonmedia.js', array('jquery'), null, true);  // sermon Video Player
		wp_register_script('preview', get_template_directory_uri() . '/js/preview.js', array('jquery'), null, true);
		wp_register_script('th_colorpicker', get_template_directory_uri() . '/backend/js/mColorPicker/javascripts/mColorPicker.js', array('jquery'), null, true);
		wp_register_script('history', get_template_directory_uri() . '/js/jquery.history.js', array('jquery'), null, true);  // history.js
		
		$http = is_ssl() ? 'https://' : 'http://';
		wp_register_script('th_frogoloop', $http . 'a.vimeocdn.com/js/froogaloop2.min.js', array('jquery'), null, true);

		$ajax_data = array(
			'admin_url' => admin_url('admin-ajax.php'),
			'directory_uri' => get_template_directory_uri(),
		);

		wp_localize_script('th_scripts', 'ThemeData', $ajax_data);

		$i18n = array(
			'wrong_connection' => __('Something going wrong with connection...', 'churchope'),
		);
		wp_localize_script('th_scripts', 'Theme_i18n', $i18n);

		if (!is_admin() && !in_array($GLOBALS['pagenow'], array('wp-login.php', 'wp-register.php')))
		{

			wp_enqueue_script('modernizer');
			wp_enqueue_script('superfish');
			wp_enqueue_script('prettyphoto');



			wp_enqueue_script('th_scripts');

			if (get_option(SHORTNAME . "_preview"))
			{
				wp_enqueue_script('preview');
				wp_enqueue_script('th_colorpicker');
			}
		}
	}

}
add_action('init', 'th_register_scripts');

// WPML.org integration

wpml_register_string('churchope', 'copyright', stripslashes(get_option(SHORTNAME . "_copyright")));

/*
 * meta functions for easy access:
 */

//get term meta field
if (!function_exists('get_tax_meta'))
{

	function get_tax_meta($term_id, $key, $multi = false)
	{
		$t_id = (is_object($term_id)) ? $term_id->term_id : $term_id;
		$m = get_option('tax_meta_' . $t_id);
		if (isset($m[$key]))
		{
			return $m[$key];
		}
		else
		{
			return '';
		}
	}

}

//delete meta
if (!function_exists('delete_tax_meta'))
{

	function delete_tax_meta($term_id, $key)
	{
		$m = get_option('tax_meta_' . $term_id);
		if (isset($m[$key]))
		{
			unset($m[$key]);
		}
		update_option('tax_meta_' . $term_id, $m);
	}

}

//update meta
if (!function_exists('update_tax_meta'))
{

	function update_tax_meta($term_id, $key, $value)
	{
		$m = get_option('tax_meta_' . $term_id);
		$m[$key] = $value;
		update_option('tax_meta_' . $term_id, $m);
	}

}

if (!function_exists('get_theme_post_thumbnail'))
{

	function get_theme_post_thumbnail($id, $size = 'thumbnail')
	{
		global $theme_images_size;
		if ($theme_images_size instanceof Custom_Thumbnail)
		{
			$theme_images_size->getThumbnail($id, $size);
		}
		else
		{
			the_post_thumbnail($size);
		}
	}

}

if (!function_exists('theme_post_thumbnail'))
{

	function theme_post_thumbnail($size = 'thumbnail')
	{
		global $theme_images_size;
		if ($theme_images_size instanceof Custom_Thumbnail)
		{
			$theme_images_size->getThumbnail(null, $size);
		}
		else
		{
			the_post_thumbnail($size);
		}
	}

}

if (!function_exists('timezome_time'))
{

	function timezome_time()
	{
		return time() + get_option('gmt_offset', 0) * 3600;
	}

}

//
if (!function_exists('set_per_page'))
{

	function set_per_page($query)
	{
		if (is_category() || is_tag() || is_tax())
		{
			global $wp_query;
			$term = $wp_query->get_queried_object();
			if (get_tax_meta($term->term_id, SHORTNAME . '_post_listing_number', true))
			{
				$post_count = get_tax_meta($term->term_id, SHORTNAME . '_post_listing_number', true);
				$query->set('posts_per_page', $post_count);
			}
		}

		return $query;
	}

}

if (!is_admin())
{
	add_action('pre_get_posts', 'set_per_page');
}

// Sidebars
register_sidebar(array(
	'id' => 'default-sidebar',
	'description' => __('The default sidebar!', 'churchope'),
	'name' => __('Default sidebar', 'churchope'),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h3 class="widget-title">',
	'after_title' => '</h3>',
));

register_sidebar(array(
	'id' => 'header',
	'name' => __('Header sidebar', 'churchope'),
	'before_widget' => '<div id="%1$s" class="widget %2$s clearfix">',
	'after_widget' => '</div>',
	'before_title' => '<h4  class="widget-title">',
	'after_title' => '</h4>',
));

register_sidebar(array(
	'id' => 'footer-1',
	'name' => __('Footer Column 1', 'churchope'),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h4  class="widget-title">',
	'after_title' => '</h4>',
));

register_sidebar(array(
	'id' => 'footer-2',
	'name' => __('Footer Column 2', 'churchope'),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h4  class="widget-title">',
	'after_title' => '</h4>',
));

register_sidebar(array(
	'id' => 'footer-3',
	'name' => __('Footer Column 3', 'churchope'),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h4  class="widget-title">',
	'after_title' => '</h4>',
));

register_sidebar(array(
	'id' => 'footer-4',
	'name' => __('Footer Column 4', 'churchope'),
	'before_widget' => '<div id="%1$s" class="widget %2$s">',
	'after_widget' => '</div>',
	'before_title' => '<h4  class="widget-title">',
	'after_title' => '</h4>',
));

if (!function_exists('list_comments'))
{

	function list_comments($comment, $args, $depth)
	{

		$GLOBALS['comment'] = $comment;
		?>

		<li <?php comment_class(); ?> id="li-comment-<?php comment_ID() ?>">

			<div id="comment-<?php comment_ID(); ?>" class="comment-body clearfix">

				<div class="avatars">
					<?php echo get_avatar($comment, $size = '75', '', get_comment_author()); ?>
					<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
				</div>

				<div class="comment-text">
					<div class="comment-meta">

						<?php printf('<cite class="fn">%s</cite>', get_comment_author_link()) ?>

						<span><?php printf(__('Posted on %1$s at %2$s', 'churchope'), get_comment_date(), get_comment_time()) ?></span>

					</div>
					<div class="comment-entry" >
						<?php comment_text() ?>
					</div>

					<?php if ($comment->comment_approved == '0') : ?>

						<em><?php _e('Your comment is awaiting moderation.', 'churchope') ?></em>

					<?php endif; ?>
				</div>
			</div>
			<?php
		}

	}
	?>
