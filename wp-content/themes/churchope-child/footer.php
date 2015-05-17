		</div>
		
		<footer>
	    <?php if (get_option(SHORTNAME."_footer_widgets_enable") != '') {  
	switch ( get_option(SHORTNAME."_footer_widgets_columns") ) {
		case '1' : {
			$column_class = 'grid_12';
			break;
			}	
		case '2' : {
			$column_class = 'grid_6';
			break;
			}	
		case '3' : {
			$column_class = 'grid_4';
			break;
			}	
		case '4' : {
			$column_class = 'grid_3';
			break;
			}		
	}
 
 ?>
<section id="footer_widgets" class="clearfix row">
<?php
$i = 1;
while ($i <= (int)get_option(SHORTNAME."_footer_widgets_columns")) { ?>
    <aside class="<?php echo $column_class ?>">
   	<?php dynamic_sidebar("footer-".$i)  ?>
    </aside>
    <?php
	$i++;
	 } ?>
	<div class="grid_12 dotted"></div>  
</section> 
  <?php } ?>  
        <div class="clearfix row" id="copyright">
   			 <div class="grid_7"><p><?php  echo wpml_t('churchope', 'copyright', stripslashes(get_option(SHORTNAME."_copyright"))); ?></p></div>
         <img src="<?php echo get_stylesheet_directory_uri(); ?>/images/0-list_background/bottom_logotype.png">
		 </div>
		
		<?php  echo stripslashes(get_option(SHORTNAME."_GA")); ?>
        </footer>
<?php echo (get_option(SHORTNAME . "_boxed"))?'</div>':''?>
  
		<?php  wp_footer(); ?>

	</body>
</html>