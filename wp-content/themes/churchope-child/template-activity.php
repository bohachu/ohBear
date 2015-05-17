<?php
/*
  Template Name: Activity
 */
?>

<?php get_header(); ?>
<div id="contentarea" class="row clearfix">
  
	<div class="grid_12" id="ohbear-activity-center">
 
		<?php get_template_part('loop'); ?>
    <img id="ohbear-activity-logo" src="<?php echo get_stylesheet_directory_uri(); ?>/images/4-activities/p1_pic.png" alt="">
    </div>
 
  
</div>
<?php get_footer(); ?>