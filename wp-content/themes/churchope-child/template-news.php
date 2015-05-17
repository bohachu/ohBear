<?php
/*
  Template Name: News
*/
?>

<?php get_header(); ?>
<div id="contentarea" class="row clearfix">
	<div class="grid_12" id="ohbear-news">
       <img class="news-left" src="<?php echo get_stylesheet_directory_uri(); ?>/images/1-index/news_title.png" alt="news_title">
       <img class="news-right" src="<?php echo get_stylesheet_directory_uri(); ?>/images/1-index/news_pic.png" alt="news_pic">
      <hr>
       <a href="<?php echo get_site_url(); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/7-index-news_events/btn_home.png" alt=""></a>
    
    <?php get_template_part('loop'); ?>
 
  
    </div>
</div>
<?php get_footer(); ?>
