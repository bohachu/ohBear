<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
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
		<meta charset="<?php bloginfo('charset'); ?>">

		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="<?php echo home_url(); ?>">
		<title>
			<?php
			if (get_query_var('pagename') == 'customeventslist')
			{

				$events_for = date_i18n(get_option('date_format'), strtotime(get_query_var('event_month') . '/' . get_query_var('event_day') . '/' . get_query_var('event_year')));
				if ($events_for && strlen($events_for))
				{
					printf(__('Events for %s', 'churchope'), $events_for);
					?> | <?php
				}
				bloginfo('name');
			}
			elseif (!defined('WPSEO_VERSION'))
			{
				// if there is no WordPress SEO plugin activated

				wp_title(' | ', true, 'right');
				bloginfo('name');
				?> | <?php
				bloginfo('description'); // or some WordPress default
			}
			else
			{
				wp_title();
			}
			?>	
		</title>
		<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> Feed" href="<?php bloginfo('rss2_url'); ?>">	
		<script> var THEME_URI = '<?php echo get_template_directory_uri(); ?>';</script>
    <script src="//code.jquery.com/jquery-1.10.2.js"></script>

		<?php
		if (is_singular() && get_option('thread_comments'))
		{
			wp_enqueue_script('comment-reply');
		}		
		?>


		<?php
		global $post, $post_layout;
		if (!is_404() && !is_search())
		{
			$pid = get_the_ID();
		}
		else
		{
			$pid = null;
		}
		if (is_home() && get_option("page_for_posts"))
		{
			$pid = get_option("page_for_posts");
		}

		if (is_single() && $post->post_type == 'post')
		{
			$post_layout = (get_post_meta($pid, SHORTNAME . "_post_layout", true)) ? get_post_meta($pid, SHORTNAME . "_post_layout", true) : 'layout_' . get_option(SHORTNAME . '_post_layout') . '_sidebar';
		}
		elseif (is_single() && $post->post_type == Custom_Posts_Type_Event::POST_TYPE)
		{
			$post_layout = (get_post_meta($pid, SHORTNAME . "_post_layout", true)) ? get_post_meta($pid, SHORTNAME . "_post_layout", true) : 'layout_' . get_option(SHORTNAME . '_events_layout') . '_sidebar';
		}
		elseif (is_single() && $post->post_type == Custom_Posts_Type_Sermon::POST_TYPE)
		{
			$post_layout = (get_post_meta($pid, SHORTNAME . "_post_layout", true)) ? get_post_meta($pid, SHORTNAME . "_post_layout", true) : 'layout_' . get_option(SHORTNAME . '_sermons_layout') . '_sidebar';
		}
		elseif (is_single() && $post->post_type == Custom_Posts_Type_Gallery::POST_TYPE)
		{
			$post_layout = (get_post_meta($pid, SHORTNAME . "_post_layout", true)) ? get_post_meta($pid, SHORTNAME . "_post_layout", true) : 'layout_' . get_option(SHORTNAME . '_gallery_layout') . '_sidebar';
		}
		elseif (is_single() && $post->post_type == Custom_Posts_Type_Testimonial::POST_TYPE)
		{
			$post_layout = (get_post_meta($pid, SHORTNAME . "_post_layout", true)) ? get_post_meta($pid, SHORTNAME . "_post_layout", true) : 'layout_' . get_option(SHORTNAME . '_testimonial_layout') . '_sidebar';
		}
		elseif (is_category() || is_tag())
		{
			global $wp_query;
			$term = $wp_query->get_queried_object();
			$post_layout = (get_tax_meta($term->term_id, SHORTNAME . "_post_listing_layout", true)) ? get_tax_meta($term->term_id, SHORTNAME . "_post_listing_layout", true) : 'layout_' . get_option(SHORTNAME . '_post_listing_layout') . '_sidebar';
		}
		elseif (is_tax(Custom_Posts_Type_Gallery::TAXONOMY))
		{
			global $wp_query;
			$term = $wp_query->get_queried_object();
			$post_layout = (get_tax_meta($term->term_id, SHORTNAME . "_post_listing_layout", true)) ? get_tax_meta($term->term_id, SHORTNAME . "_post_listing_layout", true) : 'layout_' . get_option(SHORTNAME . '_galleries_listing_layout') . '_sidebar';
		}
		elseif (is_tax(Custom_Posts_Type_Testimonial::TAXONOMY))
		{
			global $wp_query;
			$term = $wp_query->get_queried_object();
			$post_layout = (get_tax_meta($term->term_id, SHORTNAME . "_post_listing_layout", true)) ? get_tax_meta($term->term_id, SHORTNAME . "_post_listing_layout", true) : 'layout_' . get_option(SHORTNAME . '_testimonials_listing_layout') . '_sidebar';
		}
		elseif (is_tax(Custom_Posts_Type_Sermon::TAXONOMY))
		{
			global $wp_query;
			$term = $wp_query->get_queried_object();
			$post_layout = (get_tax_meta($term->term_id, SHORTNAME . "_post_listing_layout", true)) ? get_tax_meta($term->term_id, SHORTNAME . "_post_listing_layout", true) : 'layout_' . get_option(SHORTNAME . '_sermons_listing_layout') . '_sidebar';
		}
		elseif (is_tax(Custom_Posts_Type_Event::TAXONOMY))
		{
			global $wp_query;
			$term = $wp_query->get_queried_object();
			$post_layout = (get_tax_meta($term->term_id, SHORTNAME . "_post_listing_layout", true)) ? get_tax_meta($term->term_id, SHORTNAME . "_post_listing_layout", true) : 'layout_' . get_option(SHORTNAME . '_events_listing_layout') . '_sidebar';
		}
		elseif (is_home() || (is_404() && get_query_var('pagename') != 'customeventslist') || is_search() || is_date())
		{
			$post_layout = 'layout_' . get_option(SHORTNAME . '_post_listing_layout') . '_sidebar';
		}
		elseif (is_page())
		{
			if (get_post_meta($pid, "_wp_page_template", true) == 'template-leftsidebar.php')
			{
				$post_layout = 'layout_left_sidebar';
			}
			elseif (get_post_meta($pid, "_wp_page_template", true) == 'template-rightsidebar.php')
			{
				$post_layout = 'layout_right_sidebar';
			}
			else
			{
				$post_layout = 'layout_none_sidebar';
			}
		}
		elseif (get_query_var('pagename') == 'customeventslist')
		{
			$post_layout = 'layout_' . get_option(SHORTNAME . '_events_listing_layout') . '_sidebar';
		}
		else
		{
			$post_layout = 'layout_none_sidebar';
		}

		/**
		 * slideshow...
		 */
		global $wp_query;
		$current_term = $wp_query->get_queried_object();

		if ((is_tax() || is_tag() || is_category()) && $current_term && get_tax_meta($current_term->term_id, SHORTNAME . "_tax_slider", true) && (get_tax_meta($current_term->term_id, SHORTNAME . "_tax_slider", true) !== 'Disable'))
		{
			$slideshow = 'th_slideshow';
		}
		//post page
		elseif (!is_archive() && get_post_meta($pid, SHORTNAME . "_post_slider", true) && (get_post_meta($pid, SHORTNAME . "_post_slider", true) !== 'Disable'))
		{
			$slideshow = 'th_slideshow';
		}
		elseif (is_home() && get_post_meta(get_option("page_for_posts"), SHORTNAME . "_post_slider", true) && get_option("page_on_front"))
		{
			$slideshow = 'th_slideshow';
		}
		// Reading settings Blog page undefined and Front page undefined and Blog listing slider disabled
		elseif (is_home() && !get_post_meta(get_option("page_for_posts"), SHORTNAME . "_post_slider", true) && !get_option("page_on_front") && get_option(SHORTNAME . "_blog_slider") && get_option(SHORTNAME . "_blog_slider") !== "Disable" && get_option(SHORTNAME . "_blog_slider") !== "Use global")
		{
			$slideshow = 'th_slideshow';
		}
		elseif (is_home() && !get_post_meta(get_option("page_for_posts"), SHORTNAME . "_post_slider", true) && !get_option("page_on_front") && get_option(SHORTNAME . "_blog_slider") && get_option(SHORTNAME . "_blog_slider") == "Disable")
		{
			$slideshow = '';
		}
		elseif ($current_term && (isset($current_term->term_id) && get_tax_meta($current_term->term_id, SHORTNAME . "_tax_slider", true) == 'Disable') || ($pid && get_post_meta($pid, SHORTNAME . "_post_slider", true) == 'Disable'))
		{
			$slideshow = '';
		}
		//global slideshow settings
		else
		{
			$slideshow = (get_option(SHORTNAME . "_global_slider") !== 'Disable') ? 'th_slideshow' : '';
		}

		$widget_title = (get_post_meta($pid, SHORTNAME . "_title_sidebar", true)) ? 'widget_title' : '';
		$boxed = (get_option(SHORTNAME . "_boxed")) ? 'boxed' : '';


		//custom header background
		$custom_header_settings = false;
		if (is_singular() && get_post_meta($pid, SHORTNAME . "_custom_post_header", true))
		{
			$custom_header_settings = true;
			$custom_header_color = get_post_meta($pid, SHORTNAME . "_page_header_color", true);
			$custom_header_pattern = get_post_meta($pid, SHORTNAME . "_post_menupattern", true);
			$custom_header_repeat = get_post_meta($pid, SHORTNAME . "_post_headerpattern_repeat", true);
			$custom_header_horizontal = get_post_meta($pid, SHORTNAME . "_post_headerpattern_x", true);
			$custom_header_vertical = get_post_meta($pid, SHORTNAME . "_post_headerpattern_y", true);
		}
		elseif (isset($current_term) && get_tax_meta($current_term->term_id, SHORTNAME . "_custom_term_header", true))
		{
			$custom_header_settings = true;
			$term_id = $current_term->term_id;

			$custom_header_color = get_tax_meta($term_id, SHORTNAME . "_term_header_color", true);
			$custom_header_pattern = get_tax_meta($term_id, SHORTNAME . "_term_menupattern", true);
			$custom_header_repeat = get_tax_meta($term_id, SHORTNAME . "_term_headerpattern_repeat", true);
			$custom_header_horizontal = get_tax_meta($term_id, SHORTNAME . "_term_headerpattern_x", true);
			$custom_header_vertical = get_tax_meta($term_id, SHORTNAME . "_term_headerpattern_y", true);
		}

		if (!empty($custom_header_settings))
		{
			echo "<style>
					#color_header {background:$custom_header_color url('$custom_header_pattern') $custom_header_repeat $custom_header_horizontal $custom_header_vertical !important; }
				</style>";
		}
		?>		

		<?php	wp_head(); ?>    
	</head>
	<body <?php body_class($post_layout . ' ' . $slideshow . ' ' . $widget_title . ' ' . $boxed); ?>>

		<?php echo ($boxed) ? '<div class="wrapper">' : '' ?>        
		  <!--[if lt IE 7]><?php _e('<p class=chromeframe>Your browser is <em>ancient!</em> <a href="http://browsehappy.com/">Upgrade to a different browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">install Google Chrome Frame</a> to experience this site.</p>', 'churchope'); ?><![endif]-->    
    <div id="facebook_side_button">
      <a href="https://www.facebook.com/OhBearTaiwan" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/1-index/btn_facebook.png"></a>
    </div> 
    <div class="wrapper_top">
      
       </div>
    
    <header class="clearfix">      
			<div class="header_bottom">
				<div class="header_top">
					<div class="row">
            <div class="header_left">               
               <div class="header_left_date">
                <a href="<?php echo get_site_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/0-list_background/taiwan_logo.png" alt="Taiwan_logo"></a>

               <hr>            
               <div class="current-date"><?php echo date_i18n('Y ● F j日● l', time()); ?></div>
              </div>  
              <?php
							wp_nav_menu(array('theme_location' => 'left-menu', 'container_class' => 'main_menu_left', 'menu_class' => 'sf-menu clearfix mobile-menu', 'fallback_cb' => '', 'container' => 'nav', 'link_before' => '', 'link_after' => '', 'items_wrap' => '<div id="menu-icon"><div><em></em><em></em><em></em></div>' . __('Navigation', 'churchope') . '</div><ul id="%1$s" class="%2$s">%3$s</ul>', 'walker' => new Walker_Nav_Menu_Sub()));
							 ?>
             </div>
             <div class="header_center">
             <a href="<?php echo get_site_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/0-list_background/logo_title.gif"></a>
             </div>
            <div class="header_right">
               <div class="header_right_weather">
                 <!--
                 <a href="<?php echo get_site_url(); ?>/購物車/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/0-list_background/btn_shop.png" alt="shop"></a>             
-->
               <?php the_widget( 'WP_Widget_Search' ); ?> 
<!--

                <?php echo do_shortcode('[searchandfilter fields="search,category,post_tag" types=",radio,radio" headings=",Categories,Tags"]'); ?>    
    -->                      
                <hr>
                 <!--
                <div class="change-lan"> <a href="<?php echo wpml_get_home_url() ?>">中文</a> ● 日本語 ● English </div>
                 -->
                <div class="change-lan">中文 ● 日本語 ● English </div>
               </div>
 
               
               <?php
							wp_nav_menu(array('theme_location' => 'right-menu', 'container_class' => 'main_menu_right', 'menu_class' => 'sf-menu clearfix mobile-menu', 'fallback_cb' => '', 'container' => 'nav', 'link_before' => '', 'link_after' => '', 'items_wrap' => '<div id="menu-icon"><div><em></em><em></em><em></em></div>' . __('Navigation', 'churchope') . '</div><ul id="%1$s" class="%2$s">%3$s</ul>', 'walker' => new Walker_Nav_Menu_Sub()));
							  ?>
              </div>

  
                
    

            
					</div>        
				</div>
			</div>
      
            
		</header>

		<section id="color_header" class="clearfix">
      
     <?php
				if (is_front_page())
				{echo do_shortcode("[huge_it_slider id='2']"); } ?>   

      
      <!--

      <?php
				if (is_front_page())
				{get_template_part('title'); } ?>   
-->
     
		</section>
   
		<div role="main" id="main">