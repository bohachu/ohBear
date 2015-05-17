<?php
/* Start the Loop  */
global $query_string;
$order = (get_option(SHORTNAME . '_event_cat_order')) ? get_option(SHORTNAME . '_event_cat_order') : 'DESC';
query_posts($query_string . "&orderby=meta_value&meta_key=ch_event_date&order=".$order);

if (have_posts()) :
	while (have_posts()) : the_post();

		$repeat = (get_post_meta(get_the_ID(), SHORTNAME . '_event_is_repeat', true)) ? get_post_meta(get_the_ID(), SHORTNAME . '_event_is_repeat', true) : null;
		$repeat_interval = (get_post_meta(get_the_ID(), SHORTNAME . '_event_interval', true)) ? get_post_meta(get_the_ID(), SHORTNAME . '_event_interval', true) : null;

		$event_date = (get_post_meta(get_the_ID(), SHORTNAME . '_event_date', true)) ? get_post_meta(get_the_ID(), SHORTNAME . '_event_date', true) : null;
		$event_time = (get_post_meta(get_the_ID(), SHORTNAME . '_event_time', true)) ? get_post_meta(get_the_ID(), SHORTNAME . '_event_time', true) : null;

		$event_address = (get_post_meta(get_the_ID(), SHORTNAME . '_event_address', true)) ? get_post_meta(get_the_ID(), SHORTNAME . '_event_address', true) : null;
		$event_phone = (get_post_meta(get_the_ID(), SHORTNAME . '_contact_phone', true)) ? get_post_meta(get_the_ID(), SHORTNAME . '_contact_phone', true) : null;



		$time = strtotime($event_date);
		$day = date_i18n('d', $time);
		$month = date_i18n('M', $time);
		$year = date_i18n('Y', $time);

		if ($repeat_interval)
		{
			switch ($repeat_interval)
			{
				case 'day':
					$repeat_interval = __('Day', 'churchope'); //dayofweek
					break;

				case 'week':
					$repeat_interval = __('Week', 'churchope'); //dayofweek
					break;

				case 'month':
					$repeat_interval = __('Month', 'churchope'); //dayofweek	
					break;

				case 'year':
					$repeat_interval = __('Year', 'churchope'); //dayofweek	
					break;
			}
		}

		/* Single page */
		if (is_single()) :
			?>
			<article <?php post_class('clearfix events') ?> >
				<div class="postdate"><span></span><strong class="day"><?php echo ($repeat) ? '<img src="' . get_template_directory_uri() . '/images/i_repeat.png" data-retina="' . get_template_directory_uri() . '/images/retina/i_repeat@2x.png" alt="Repeat" >' : $day; ?></strong><strong class="month"><?php echo ($repeat) ? _e('Every ', 'churchope') . $repeat_interval : $month . '<br>' . $year; ?></strong></div>

				<div class="content_wrap">
					<div class="post_title_area">
						<div class="blogtitles">
							<h1 class="entry-title"><span><?php the_title(); ?></span></h1>
						</div>
					</div>	
					<?php if ($event_time || $event_address || $event_phone)
					{ ?>
						<ul class="events_meta">
							<?php if ($event_time)
							{ ?><li class="event_time"><?php echo $event_time; ?></li><?php } ?>
						<?php if ($event_address)
						{ ?><li class="event_address"><?php echo $event_address; ?></li><?php } ?>
							<?php if ($event_phone)
							{ ?><li class="event_phone"><?php echo $event_phone; ?></li><?php } ?>
						</ul>
					<?php } ?>
					<div class="entry-content">
                                            <?php the_content(); ?>
					</div>
                                        <?php wp_link_pages( array(
                                            'before' => '<div class="pagination clearfix"><ul class="page-numbers"><li class="page-numbers-item">', 'after' => '</ul></div>',
                                            'separator' => '</li><li class="page-numbers-item">',
                                            'pagelink' => '%',
                                            'link_before' => '<span>',
                                            'link_after'  => '</span>'
                                        ));?>

                                        <?php comments_template('', true); ?>
				</div>
			</article>

			<?php
		/* Categories/tags/archives listing */
		elseif (is_archive()) :

			global $wp_query, $post_layout;
			;


			$layout = ($post_layout == 'layout_none_sidebar') ? 'grid_12' : 'grid_8';
			?>

			<article <?php post_class('clearfix events') ?> >
				<div class="postdate"><span></span><strong class="day"><?php echo ($repeat) ? '<img src="' . get_template_directory_uri() . '/images/i_repeat.png" data-retina="' . get_template_directory_uri() . '/images/retina/i_repeat@2x.png" alt="Repeat" >' : $day; ?></strong><strong class="month"><?php echo ($repeat) ? _e('Every ', 'churchope') . $repeat_interval : $month; ?></strong></div>

				<div class="content_wrap">
					<div class="post_title_area">
						<div class="blogtitles">
							<h2 class="entry-title"><span><a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a></span></h2>
						</div>
					</div>	
						<?php if ($event_time || $event_address || $event_phone)
						{ ?>
						<ul class="events_meta">
						<?php if ($event_time)
						{ ?><li class="event_time"><?php echo $event_time; ?></li><?php } ?>
							<?php if ($event_address)
							{ ?><li class="event_address"><?php echo $event_address; ?></li><?php } ?>
				<?php if ($event_phone)
				{ ?><li class="event_phone"><?php echo $event_phone; ?></li><?php } ?>
						</ul>
			<?php } ?>
					<div class="entry-content">
			<?php the_excerpt(); ?>
					</div>					

				</div>
			</article>




		<?php endif; ?>
	<?php endwhile; ?>

	<?php
	// get total number of pages
	global $wp_query;
	$total = $wp_query->max_num_pages;
// only bother with the rest if we have more than 1 page!
	if ($total > 1)
	{
		?>
		<div class="pagination clearfix">
			<?php
			// get the current page
			if (get_query_var('paged'))
			{
				$current_page = get_query_var('paged');
			}
			else if (get_query_var('page'))
			{
				$current_page = get_query_var('page');
			}
			else
			{
				$current_page = 1;
			}
			// structure of "format" depends on whether we're using pretty permalinks
			$permalink_structure = get_option('permalink_structure');
			if (empty($permalink_structure))
			{
				if (is_front_page())
				{
					$format = '?paged=%#%';
				}
				else
				{
					$format = '&paged=%#%';
				}
			}
			else
			{
				$format = 'page/%#%/';
			}



			echo paginate_links(array(
				'base' => get_pagenum_link(1) . '%_%',
				'format' => $format,
				'current' => $current_page,
				'total' => $total,
				'mid_size' => 10,
				'type' => 'list'
			));
			?>
		</div>
			<?php } ?>
		<?php else : ?>
	<article class="hentry">
		<h1>
	<?php _e('Not Found', 'churchope'); ?>
		</h1>
		<p class="center">
	<?php _e('Sorry, but you are looking for something that isn\'t here.', 'churchope'); ?>
		</p>
	</article>
<?php endif; ?>
<?php wp_reset_query(); ?>