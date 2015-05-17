<?php
/*
  Template Name: Front-RightSidebar
 */
?>
<?php get_header(); ?>

<div id="contentarea" class="row clearfix">
	<div class="grid_8">
     <div id="front-news">
       <img class="news-left" src="<?php echo get_stylesheet_directory_uri(); ?>/images/1-index/news_title.png" alt="news_title">
       <img class="news-right" src="<?php echo get_stylesheet_directory_uri(); ?>/images/1-index/news_pic.png" alt="news_pic">
      <hr>
       </div>
    <?php get_template_part('loop'); ?>
    <a href="<?php echo get_site_url(); ?>/新鮮事/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/1-index/news_btn_more-news.png" alt=""></a>
	</div>
	<aside class="grid_4 right-sidebar">
		<?php (get_post_meta(get_the_ID(), SHORTNAME . '_page_sidebar', true)) ? $sidebar = get_post_meta(get_the_ID(), SHORTNAME . '_page_sidebar', true) : $sidebar = "default-sidebar";
		generated_dynamic_sidebar_th($sidebar); ?>
	</aside>
</div>
<?php get_footer(); ?>