<?php
/*隱藏meta標籤的WordPress版本*/
remove_action('wp_head', 'wp_generator');

/*註冊導覽列*/
if (!function_exists('th_register_menus_OhBear'))
{

	function th_register_menus_OhBear()
	{
	register_nav_menus(
		array(
		'left-menu' => __('Left Menu', 'churchope'),
		'right-menu' => __('Right Menu', 'churchope'),
    		'mobile-menu' => __('Mobile Menu', 'churchope')
		)
	);
	}

}
add_action('init', 'th_register_menus_OhBear');

/*non-WC theme compatibility*/

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'my_theme_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'my_theme_wrapper_end', 10);

function my_theme_wrapper_start() {
  echo '<div id="contentarea" class="row clearfix">';
  echo  '<div class="grid_12">';  
}

function my_theme_wrapper_end() {
  echo '</div>';
  echo '</div>';
}

 add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
    add_theme_support( 'woocommerce' );
}

remove_action( 'woocommerce_before_main_content','woocommerce_breadcrumb', 20, 0);
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10 );


/*取消woocommerce的商品評論*/
add_filter( 'woocommerce_product_tabs', 'wcs_woo_remove_reviews_tab', 98 );
function wcs_woo_remove_reviews_tab($tabs) {
 unset($tabs['reviews']);
 return $tabs;
}

/*隱藏woocommerce商品頁面標題*/
add_filter('woocommerce_show_page_title',false);

/*隱藏woocommerce相關商品顯示*/
function wc_remove_related_products( $args ) {
	return array();
}
add_filter('woocommerce_related_products_args','wc_remove_related_products', 10); 


/*更改woocommerce商品頁面的導覽鈕*/

remove_action('woocommerce_pagination', 'woocommerce_pagination', 10);

function woocommerce_pagination() {

wp_pagenavi('', '', array(
  'prev_text' => '<img src="' . get_bloginfo('stylesheet_directory') . '/images/5-products/p1_btn_L.png' . '">',
  'next_text' => '<img src="' . get_bloginfo('stylesheet_directory') . '/images/5-products/p1_btn_R.png' . '">',  
));

}

add_action( 'woocommerce_pagination', 'woocommerce_pagination', 10);


/*調整商品相關資訊的順訊*/

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 15 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40);
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 9 );

add_action('woocommerce_single_product_summary', 'show_the_date',10);    
function show_the_date() {
    echo "<span  id=\"ohbear-product-date\" class=\"entry-date\">上架日期： " . get_the_date() . "</span>";
    }

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 11 );

//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

//add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 11 );


//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
//add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 5 );

/*更改woocommerce商品庫存顯示方式*/
add_filter( 'woocommerce_get_availability', 'custom_get_availability', 1, 2);

function custom_get_availability( $availability, $_product ) {
  global $product;
  $stock = $product->get_total_stock();

  if ( $_product->is_in_stock() ) $availability['availability'] = __(' 現有庫存： ' . $stock  , 'woocommerce');
  if ( !$_product->is_in_stock() ) $availability['availability'] = __('已售完，現無庫存', 'woocommerce');

  return $availability;
}

/*更改woocommerce加入購物車的顯示方式*/
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_cart_button_text' );    // 2.1 +
 
function woo_custom_cart_button_text() {
 
        return __( '放入購物車', 'woocommerce' );
 
}

add_filter ( 'woocommerce_product_thumbnails_columns', 'xx_thumb_cols' );
 function xx_thumb_cols() {
     return 5; // .last class applied to every 5th thumbnail
 }

?>
<?php
add_filter('body_class','add_body_class_to_search_filter');
function add_body_class_to_search_filter($classes) {
	
	global $sf_form_data;
	
	if ($sf_form_data->is_valid_form())
	{		
		$classes[] = "woocommerce-page";
	}
	// return the $classes array
	return $classes;
}
?>
<?php

/**
 * WooCommerce Extra Feature
 * --------------------------
 *
 * Register a shortcode that creates a product categories dropdown list
 *
 * Use: [product_categories_dropdown orderby="title" count="0" hierarchical="0"]
 *
 */
add_shortcode( 'product_categories_dropdown', 'woo_product_categories_dropdown' );

function woo_product_categories_dropdown( $atts ) {

  extract(shortcode_atts(array(
    'count'         => '0',
    'hierarchical'  => '0',
    'show_uncategorized' => 0,
    'orderby' 	    => ''
    ), $atts));
	
	ob_start();
	
	$c = $count;
	$h = $hierarchical;
	$o = ( isset( $orderby ) && $orderby != '' ) ? $orderby : 'order';
		
	// Stuck with this until a fix for http://core.trac.wordpress.org/ticket/13258
	woocommerce_product_dropdown_categories( $c, $h, 0, $o );

        wc_enqueue_js( "
                                jQuery('.dropdown_product_cat').change(function(){
                                        if(jQuery(this).val() != '') {
                                                location.href = '" . home_url() . "/?product_cat=' + jQuery(this).val();
                                        }
                                });
                        " );
	
	return ob_get_clean();
	
}
?>
<?php

/**
 * Default widget class
 * @abstract 
 */
abstract class Widget_Default extends WP_Widget
{

	/**
	 * Widget Prefix
	 * @var string
	 */
	protected $prefix;

	/**
	 * Textdomain for translation
	 * @var string 
	 */
	protected $textdomain;

	/**
	 * 
	 * @var string
	 */
	protected $classname = '';

	/**
	 * required if more than 250px
	 * @var int 
	 */
	protected $width = 200;

	/**
	 * currently not used but may be needed in the future
	 * @var int 
	 */
	protected $height = 350;

	/**
	 * shown on the configuration page.
	 * @var string
	 */
	protected $description = '';

	/**
	 * Name
	 * @var string
	 */
	protected $__name = '';

	/**
	 * Part of base_id. THEMENAME-{__id}
	 * @var type 
	 */
	protected $__id = '';

	/**
	 * Delimiter between the name of the THEME and the name of the widget <br/>
	 * displayed on the configuration page
	 * @var string
	 */
	protected $name_delimiter = ' &rarr; ';

	/**
	 * Wiget constructor
	 */
	function __construct()
	{
		$this->setPrefix(strtolower(THEMENAME));
		$this->setTextdomain(strtolower(THEMENAME));
		parent::__construct($this->getBaseId(), $this->getTranslatedName(), $this->getWidgetOption(), $this->getWidgetControlOption());
	}

	/**
	 * Translated name for the widget displayed on the configuration page/
	 * @return string
	 */
	protected function getTranslatedName()
	{
		return THEMENAME . $this->getNameDelimiter() . $this->getName();
	}

	/**
	 * Get classname and translated description
	 * @return array Optional Passed to wp_register_sidebar_widget()
	 *  - classname:
	 *  - description: shown on the configuration page
	 */
	protected function getWidgetOption()
	{
		$widget_ops = array('classname' => $this->getClassName(),
			'description' => $this->getDescription());

		return $widget_ops;
	}

	/**
	 * Get  Base ID for the widget, lower case,
	 * if left empty a portion of the widget's class name will be used. Has to be unique.
	 * @return string 
	 */
	protected function getBaseId()
	{
		$base_id = "{$this->getPrefix()}-{$this->getIdSuffix()}";
		return strtolower($base_id);
	}

	/**
	 * Get wodget control data
	 * @return array Passed to wp_register_widget_control()
	 * 	 - width: required if more than 250px
	 * 	 - height: currently not used but may be needed in the future
	 * 	 - id_base:
	 */
	protected function getWidgetControlOption()
	{
		$control_ops = array('width' => $this->getWidth(),
			'height' => $this->getHeight(),
			'id_base' => $this->getBaseId());
		return $control_ops;
	}

	/**
	 * Get wigget prefix( lowercase THEMNAME) 
	 * @return string
	 */
	public function getPrefix()
	{
		return $this->prefix;
	}

	protected function setPrefix($prefix)
	{
		$this->prefix = $prefix;
	}

	/**
	 * Get textdomain for translation
	 * @return string
	 */
	public function getTextdomain()
	{
		return $this->textdomain;
	}

	protected function setTextdomain($textdomain)
	{
		$this->textdomain = $textdomain;
	}

	/**
	 * Get widget classname
	 * @return string 
	 */
	public function getClassName()
	{
		return $this->classname;
	}

	/**
	 * Set widget classname 
	 * @param string $classname 
	 */
	public function setClassName($classname)
	{
		$this->classname = $classname;
	}

	public function getWidth()
	{
		return $this->width;
	}

	public function setWidth($width)
	{
		$this->width = $width;
	}

	public function getHeight()
	{

		return $this->height;
	}

	public function setHeight($height)
	{
		$this->height = $height;
	}

	/**
	 * Get widget description for shown on the configuration page
	 * @return string 
	 */
	public function getDescription()
	{

		return $this->description;
	}

	/**
	 * Set widget description for shown on the configuration page
	 * @param string $description shown on the configuration page
	 */
	public function setDescription($description)
	{
		$this->description = $description;
	}

	/**
	 * Set Widget name<br/>
	 * for the widget displayed on the configuration page
	 * @param string $name 
	 */
	protected function setName($name)
	{
		$this->__name = $name;
	}

	/**
	 * Get widget name<br/>
	 * for the widget displayed on the configuration page
	 * @return string
	 */
	public function getName()
	{
		return $this->__name;
	}

	/**
	 * Set suffix part of id_base (THEMENAME-{suffix})
	 * @param string $suffix 
	 */
	protected function setIdSuffix($suffix)
	{
		$this->__id = $suffix;
	}

	/**
	 * Get suffix part of id_base (THEMNAME-{suffix})
	 * @return string
	 */
	protected function getIdSuffix()
	{
		return $this->__id;
	}

	/**
	 * Set Delimetr for the THEMANAME and widget name displayed on the configuration page
	 * @param string $name_delimiter 
	 */
	protected function setNameDelimiter($name_delimiter)
	{
		$this->name_delimiter = $name_delimiter;
	}

	/**
	 * Get Delimetr for the THEMANAME and widget name displayed on the configuration page
	 * @return string
	 */
	protected function getNameDelimiter()
	{
		return $this->name_delimiter;
	}

	/**
	 * Check is plugin wpml is active
	 * @return boolean
	 */
	protected function isWPML_PluginActive()
	{
		return defined('ICL_LANGUAGE_CODE');
	}

}

?>
<?php
/**
 * Interface for caching widget data using Transients API.
 * @see http://codex.wordpress.org/Transients_API
 */
interface Widget_Interface_Cache
{
        /**
         * 3600 sec - Hour
         */
        const EXPIRATION_HOUR = 3600;

        /**
         * 1800 sec - Half an hour
         */
        const EXPIRATION_HALF_HOUR = 1800;

        const DELETE_ALL_CACHE = true;


        /**
         * Get unique identifier for widget cached data
         */
        function getTransientId();

        /**
         * Reinit cache data
         */
        function reinitWidgetCache($instance);

        /**
         * Get cached widget data
         */
        function getCachedWidgetData();

        /**
         * Number of seconds to keep the cached data before refreshing.
         */
        function getExparationTime();

        /**
         * Delete a transient
         */
        function deleteWidgetCache();
}
?>
<?php

/**
 * Show previews from gallery category 
 */
class Widget_Gallery2 extends Widget_Default implements Widget_Interface_Cache
{
	const GALLERY_POST_TRANSIENT = 'JH8wo0sd';
	
	public function __construct()
	{
		$this->setClassName('widget_gallery2');
		$this->setName(__('From Gallery2','churchope'));
		$this->setDescription(__('Show previews from gallery category','churchope'));
		$this->setIdSuffix('gallery2');
		parent::__construct();
		add_action('save_post', array(&$this, 'action_clear_widget_cache'));
	}
	
	function action_clear_widget_cache($postID)
	{
		if(get_post_type($postID) == Custom_Posts_Type_Gallery::POST_TYPE)
		{
			$temp_number = $this->number;

			$settings = $this->get_settings();
			
			if ( is_array($settings) ) {
				foreach ( array_keys($settings) as $number ) {
					if ( is_numeric($number) ) {
						$this->number = $number;
						$this->deleteWidgetCache();
					}
				}
			}
			$this->number = $temp_number;
		}
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters( 'widget_title', $instance['title'] );		
		$wport = $this->getGalleries($instance);

		///HTML
		echo $before_widget;

		if ( $title )
		{
			echo $before_title . $title . $after_title;
		}
		
     
		if ($wport->have_posts()) : ?>
			<ul>
			<?php  while($wport->have_posts()) : $wport->the_post();?>
				<li class="<?php if( ($wport->current_post % 2 ) == 0  ) { echo("first");} ?>" >
             <h2 class="entry-title"><a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a></h2> 						
				</li>
			<?php endwhile; ?>
			</ul>
		<?php endif;
		
		wp_reset_postdata();   
		echo $after_widget;
	}


	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['number'] = strip_tags( $new_instance['number'] );
		$instance['category'] = strip_tags( $new_instance['category'] );
		$this->deleteWidgetCache();
	

		return $instance;
	}


	function form( $instance ) {

		// Defaults
		$gallery_terms = '';
		$defaults = array( 'title' => __( 'From gallery', 'churchope' ), 'number' => '4');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
			
		$gallery_terms = get_terms(Custom_Posts_Type_Gallery::TAXONOMY);
			
		?>
		<div>
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>">
					<?php _e( 'Title:', 'churchope' ); ?>
				</label>
				<input id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $instance['title']; ?>" style="width:100%;" />
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'category' ); ?>" >
					<?php _e( 'Category of gallery:', 'churchope' ); ?>
				</label>
				<select name="<?php echo $this->get_field_name( 'category' ); ?>" id="<?php echo $this->get_field_id( 'category' ); ?>"  style="width:100%;">
					<option value="">None</option>
					<?php
					if($gallery_terms)
					{
						foreach ($gallery_terms as $cat)
						{
							if ($instance['category'] == $cat->slug)
							{
								$selected = "selected='selected'";
							}
							else
							{
								$selected = "";
							}
							echo "<option $selected value='" . $cat->slug . "'>" . $cat->name . "</option>";
						}
					}
					?>
				</select>
			</p>
			<p>
				<label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of items to show:', 'churchope' ); ?></label>
				<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $instance['number']; ?>" style="width:100%;" />
			</p>
	
		</div>
		<div style="clear:both;">&nbsp;</div>
	<?php
	}
	
	private function getGalleries($instance)
	{
		if( false === ($gallery = $this->getCachedWidgetData()))
		{
			$this->reinitWidgetCache($instance);
		}
		else
		{
			return $gallery;
		}
		return $this->getCachedWidgetData();
	}

	public function deleteWidgetCache()
	{
		global $sitepress;

		if($sitepress && is_object($sitepress) &&  method_exists($sitepress, 'get_active_languages'))
		{
			foreach($sitepress->get_active_languages() as $lang)
			{

				if(isset($lang['code']))
				{
					delete_site_transient($this->getTransientId($lang['code']));
				}
			}
		}
		
		delete_site_transient($this->getTransientId()); // clear cache
	}

	public function getCachedWidgetData()
	{
		return  get_site_transient($this->getTransientId());
	}

	public function getExparationTime()
	{
		return self::EXPIRATION_HOUR;
	}

	public function getTransientId($code = '')
	{
		$key = self::GALLERY_POST_TRANSIENT;
		if($code)
		{
			$key .= '_' . $code;
		}
		elseif($this->isWPML_PluginActive()) // wpml
		{
			$key .= '_' . ICL_LANGUAGE_CODE;
		}
		
		return $this->get_field_id( $key );
	}

	public function reinitWidgetCache($instance)
	{
		$number		= (int)$instance['number'];
		$category	= $instance['category'];
		
		$wport = new WP_Query("post_type=".Custom_Posts_Type_Gallery::POST_TYPE."&".Custom_Posts_Type_Gallery::TAXONOMY."=".$category."&post_status=publish&posts_per_page=".$number."&order=DESC");
		set_site_transient($this->getTransientId(), $wport, $this->getExparationTime());
	}
}
?>
<?php
/**
 * Class collection with all theme widgets 
 */
final class Widget 
{
	/**
	 * Unrigister widgets exception AND Register all theme widgets.
	 */
	static public function run()
	{
		unregister_widget('WP_Widget_Recent_Posts' );
		
		register_widget('Widget_Flickr');
		register_widget('Widget_FeedburnerEmail');
		register_widget('Widget_ContactForm');
		register_widget('Widget_Gallery');
		register_widget('Widget_RecentPosts');
		register_widget('Widget_Twitter');
		register_widget('Widget_SocialLinks');
		register_widget('Widget_PopularPosts');
		register_widget('Widget_Event');
		register_widget('Widget_Event_Upcoming');
		register_widget('Widget_Testimonial');
		register_widget('Widget_Sermon_Recent');
		register_widget('Widget_Sermon_Categories');
		register_widget('Widget_Sermon_Speakers');
		register_widget('Widget_MailChimp');

    unregister_widget('Widget_Gallery');
  	register_widget('Widget_Gallery2');


	}
}


?>
<?php
/*
function correct_ajax() {
    wp_enqueue_script( 'script', get_template_directory_uri() . '/js/script.js', array(), '1.0.0', false );
    $l10n = array('wpml_lang' => $sitepress->get_current_language());
    wp_localize_script($handle, $object_name, $l10n);
    
     
    global $sitepress;
    $sitepress->switch_lang($_GET['wpml_lang'], true);
    load_theme_textdomain( $domain, $path );
}

add_action( 'wp_enqueue_scripts', 'theme_name_scripts' );
*/
?>
<?php
/*
if (defined('DOING_AJAX') && DOING_AJAX) {
  add_action('setup_theme', 'mytheme_setup_theme');
}
*/
 add_action('setup_theme', 'mytheme_setup_theme');

/**
 * React early in WordPress execution on an AJAX request to set the current language.
 */
function mytheme_setup_theme() {
  global $sitepress;
  
  // Switch lang if necessary - check for our AJAX action
  if (method_exists($sitepress, 'switch_lang') && isset($_GET['wpml_lang']) && $_GET['wpml_lang'] !== $sitepress->get_default_language()) {
    $sitepress->switch_lang($_GET['wpml_lang'], true);
  }
}
?>