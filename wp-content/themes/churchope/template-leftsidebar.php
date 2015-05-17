<?php
/*
  Template Name: LeftSidebar
 */
?>
<?php get_header(); ?>
<div id="contentarea" class="row clearfix">
	<div class="main-content grid_8">    
		<?php get_template_part('loop'); ?>
	</div>
	<aside class="grid_4 left-sidebar">
		<?php (get_post_meta(get_the_ID(), SHORTNAME . '_page_sidebar', true)) ? $sidebar = get_post_meta(get_the_ID(), SHORTNAME . '_page_sidebar', true) : $sidebar = "default-sidebar";
		generated_dynamic_sidebar_th($sidebar); ?>
	</aside>
</div>
<?php get_footer(); ?>