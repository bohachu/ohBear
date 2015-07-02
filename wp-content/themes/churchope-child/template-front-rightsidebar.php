<?php
/*
  Template Name: Front-RightSidebar
 */
?>
<?php get_header(); ?>


<div id="contentarea" class="row clearfix ohbear-front">
	<div class="grid_8">
     <div id="front-news">
       <img class="news-left" src="<?php echo get_stylesheet_directory_uri(); ?>/images/1-index/news_title.png" alt="news_title">
       <img class="news-right" src="<?php echo get_stylesheet_directory_uri(); ?>/images/1-index/news_pic.png" alt="news_pic">
      <hr>
       </div>
    <?php get_template_part('loop'); ?>
    <a href="<?php echo get_permalink(icl_object_id(3855, 'page', false)); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/1-index/news_btn_more-news.png" alt=""></a>
	</div>
	<aside class="grid_4 right-sidebar">
		<?php (get_post_meta(get_the_ID(), SHORTNAME . '_page_sidebar', true)) ? $sidebar = get_post_meta(get_the_ID(), SHORTNAME . '_page_sidebar', true) : $sidebar = "default-sidebar";
		generated_dynamic_sidebar_th($sidebar); ?>
	</aside>  
</div>

<div class="ohbear-front-mobile">
  <?php
wp_nav_menu(array('theme_location' => 'mobile-menu', 'container_class' => 'main_mobile_menu', 'menu_class' => 'mobile clearfix ', 'fallback_cb' => '', 'container' => 'nav', 'link_before' => '', 'link_after' => '', 'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>', 'walker' => new Walker_Nav_Menu_Sub()));
  ?>
</div>
<?php get_footer(); ?>
