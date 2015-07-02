<?php get_header(); ?>

<?php
$post_layout = (get_post_meta(get_the_ID(), SHORTNAME . "_post_layout", true)) ? get_post_meta(get_the_ID(), SHORTNAME . "_post_layout", true) : 'layout_'.get_option(SHORTNAME . '_post_layout').'_sidebar';	
$post_sidebar = (get_post_meta(get_the_ID(), SHORTNAME . '_post_sidebar', true)) ? get_post_meta(get_the_ID(), SHORTNAME . '_post_sidebar', true) : get_option(SHORTNAME . '_post_sidebar');
?>
<div id="contentarea" class="row clearfix ohbear-activity-single">
  <a id="ohbear-activity-single-back" href="<?php echo get_permalink(icl_object_id(4233, 'page', false)); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/7-index-news_events/btn_back.png" alt=""></a>
	<div class="main-content <?php echo ($post_layout == 'layout_none_sidebar') ? 'grid_12' : 'grid_5'; ?>">    
   
    <?php get_template_part('loop'); ?>
     
  </div>
	 
  <?php if ($post_layout !== 'layout_none_sidebar') { ?>
            <aside class="grid_4 <?php echo ($post_layout == 'layout_left_sidebar') ? 'left-sidebar' : 'right-sidebar'; ?>">
                <?php $sidebar = ($post_sidebar) ? $post_sidebar : "default-sidebar";
                generated_dynamic_sidebar_th($sidebar);
                ?>
            </aside>
        <?php } ?>
</div>
<?php get_footer(); ?>