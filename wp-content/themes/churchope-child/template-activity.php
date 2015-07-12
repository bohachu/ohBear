<?php
/*
  Template Name: Activity
 */
?>

<?php get_header(); ?>
<div id="contentarea" class="row clearfix">
     <div id="ohbear-activity" class="mobile">
     <img class="activity-left" src="<?php echo get_stylesheet_directory_uri(); ?>/images/mobile/5-activities/activities_title.png" alt="news_title">
     <img class="activity-right" src="<?php echo get_stylesheet_directory_uri(); ?>/images/mobile/5-activities/activities_pic.png" alt="news_pic">
     <hr>
     </div>
	<div class="grid_12" id="ohbear-activity-center">

		<?php get_template_part('loop'); ?>
    <img id="ohbear-activity-logo" src="<?php echo get_stylesheet_directory_uri(); ?>/images/4-activities/p1_pic.png" alt="">
    </div>
 
  
</div>
<?php get_footer(); ?>