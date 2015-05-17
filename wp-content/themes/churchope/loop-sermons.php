<?php
/* Start the Loop  */
// global $query_string;
// query_posts($query_string . "&orderby=meta_value&meta_key=ch_sermon_date");

if (have_posts()) :
	while (have_posts()) : the_post();

		$sermon_audio = (get_post_meta(get_the_ID(), SHORTNAME . '_sermon_audio', true)) ? get_post_meta(get_the_ID(), SHORTNAME . '_sermon_audio', true) : null;	
		$sermon_pdf = (get_post_meta(get_the_ID(), SHORTNAME . '_sermon_pdf', true)) ? get_post_meta(get_the_ID(), SHORTNAME . '_sermon_pdf', true) : null;
		$sermon_video = (get_post_meta(get_the_ID(), SHORTNAME . '_sermon_video', true)) ? get_post_meta(get_the_ID(), SHORTNAME . '_sermon_video', true) : null;
		$hide_sermon_speakers = (get_post_meta(get_the_ID(), SHORTNAME . '_hide_sermon_speakers', true)) ? get_post_meta(get_the_ID(), SHORTNAME . '_hide_sermon_speakers', true) : null;

		/* Single post */
		if (is_single()) : 
				wp_enqueue_script('th_sermonmedia');
			?>
			<article <?php post_class('clearfix') ?> >
				<div class="post_title_area">
					<?php if (!get_option(SHORTNAME."_hidedate")) { ?>
					<div class="postdate"><span></span><strong class="day"><?php echo get_the_date('d') ?></strong><strong class="month"><?php echo get_the_date('M') ?></strong></div>
					<?php } ?>
					<div class="blogtitles <?php if (get_option(SHORTNAME."_hidedate")) { echo ' nodate'; }?>"><h1 class="entry-title"><span><?php the_title(); ?></span></h1>
						
							<?php
							if (get_the_tags())
							{ ?>
								<p class="post_tags"><?php	the_tags();	?></p>
							<?php } 
							if ( get_the_terms( $post->ID, Custom_Posts_Type_Sermon::TAXONOMY ) ) {
							?>
								<p class="post_categories"><?php _e('Categories:', 'churchope'); ?> <?php the_terms( $post->ID, Custom_Posts_Type_Sermon::TAXONOMY ); ?></p>
							<?php } ?>
					</div>


				</div>
				<?php if ($sermon_audio || $sermon_video || $sermon_pdf) { ?>
					<div class="sermon_attrs">
						<?php if ($sermon_audio || $sermon_video) { 
							global $post_layout;

							?>
							<div class="sermon_attrs_frame">
								<?php 
									
									if ($sermon_video) :
										$w = ($post_layout == 'layout_none_sidebar') ? 910 : 570;
										$h = ($post_layout == 'layout_none_sidebar') ? 512 : 320;
										$atts = array('w' => $w, 'h' => $h, );
										if ( $video_id = youtube_id_from_url($sermon_video) ) {
											$player_type =  'youtube';
										} elseif ($video_id = vimeo_id_from_url($sermon_video)) {
											$player_type =  'vimeo';
										}
										if(!empty($player_type )) {
											echo "<div id='video' class='active' data-player='{$player_type}'>";
												echo str_replace(array('[raw]', '[/raw]'), '', th_video($atts, $sermon_video ));
											echo "</div>";
										} else {
											echo "<div id='video' class='active'>";
												_e('Please enter a valid video URL', 'churchope');
											echo "</div>";
										}
									endif;

									if($sermon_audio) :
										$atts_audio = array('href' => $sermon_audio,);
										echo "<div id='audio'>";
											echo th_audio($atts_audio, basename($sermon_audio, '.mp3'));
										echo "</div>";
									endif;
								
								?>
							</div>
						<?php } ?>
						<ul class="single_sermons_meta">
							<?php if ($sermon_video){  ?>
								<li class="sermon_video<?php echo (!$sermon_audio and !$sermon_pdf) ? " last" : ""; ?>">
									<a href="#video" class="video active"><?php _e('Video', 'churchope'); ?></a>
								</li>
							<?php } ?>
							<?php if ($sermon_audio){ ?>
								<li class="sermon_audio<?php echo (!$sermon_pdf) ? " last" : ""; ?>">
									<a href="#audio" class="audio<?php echo (!$sermon_video) ? " autoplay" : ""; ?>" ><?php _e('Audio', 'churchope'); ?></a>
								</li>
							<?php } ?>
							<?php if ($sermon_pdf){ ?>
								<li class="sermon_pdf">
									<a target="_blank"  href="<?php echo $sermon_pdf; ?>"><?php _e('PDF', 'churchope'); ?></a>
								</li>
							<?php } ?>
						</ul>
					</div>
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

			<?php 
				//speakers list
				$speakers = get_the_terms( $post->ID, Custom_Posts_Type_Sermon::TAXONOMY_SPEAKER ); 
				if (!empty($speakers) and !($hide_sermon_speakers)) {
			?>
				<h3 class="entry-title"></h3>
				<?php
					foreach ($speakers as $i => $speaker) {
				?>
					<div class="authorbox">
						<a class="imgborder" href="<?php echo esc_url(get_term_link($speaker)) ?>"><span class="placeholder">
								<?php
								if (get_tax_meta($speaker->term_id, SHORTNAME . "_speaker_ava", true))
								{
									global $theme_images_size;
									$speaker_ava = get_tax_meta($speaker->term_id, SHORTNAME . "_speaker_ava", true);

									echo $theme_images_size->getThumbnailSrc($speaker_ava['id'], 'sermon_speaker');
								}
								else
								{
									echo '<img src ="' . get_template_directory_uri() . '/images/noava.png' . '">';
								}
								?>
							</span></a>
							<div>
								<h5>
									<span class="sermon_speaker">
										<?php _e('Speaker:', 'churchope'); ?>
										<a href="<?php echo esc_url( get_term_link($speaker) )?>">
											<?php echo $speaker->name; ?>
										</a>
									</span>
								</h5>
								<p><?php echo th_the_content(get_tax_meta($speaker->term_id, SHORTNAME . "_speaker_church", true)); /*$speaker->description;*/ ?></p>
							</div>
						</div>
				<?php
					} //eof foreach
				} //eof speakers list
			?>

				<?php
				if (get_option(SHORTNAME . "_related") == '')
				{
					?>
					<?php locate_template(array('inc/related.php'), true, true); ?>	
				<?php } ?>

				<?php comments_template('', true); ?>
			</article>

			<?php
		/* Categories/tags/archives listing */
		elseif (is_archive()) :
			?>

			<article <?php post_class('posts_listing') ?> id="post-<?php the_ID(); ?>">
				
				<?php
				if (has_post_thumbnail() && !get_option(SHORTNAME."_hidethumb"))
				{
					?>		
					<a href="<?php the_permalink() ?>" title="<?php echo the_title(); ?>" class="imgborder clearfix thumb listing"><?php get_theme_post_thumbnail(get_the_ID(), 'blog_thumbnail'); ?></a>
				<?php } ?>

					

				<div class="post_title_area">
					<?php if (!get_option(SHORTNAME."_hidedate")) { ?>
					<div class="postdate"><span></span><strong class="day"><?php echo get_the_date('d') ?></strong><strong class="month"><?php echo get_the_date('M') ?></strong></div>
					<?php } ?>
					<div class="blogtitles <?php if (get_option(SHORTNAME."_hidedate")) { echo ' nodate'; }?>">
						<h2 class="entry-title">
							<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to', 'churchope'); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?>
							</a>
						</h2>
						<?php //if ($sermon_audio || $sermon_video || $sermon_pdf) { ?>
							<div class="sermon_attrs_blog">
								<ul class="sermons_meta">
									<?php if ($sermon_video){  ?>
										<li class="sermon_video">
											<a href="<?php the_permalink() ?>#video" rel="bookmark" title="<?php _e('Permanent Link to', 'churchope'); ?> <?php the_title_attribute(); ?>"  class="sermon_video_link"><?php _e('Video', 'churchope'); ?>
											</a>
											
										</li>
									<?php } ?>
									<?php if ($sermon_audio){ ?>
										<li class="sermon_audio">
											<a href="<?php the_permalink() ?>#audio" rel="bookmark" title="<?php _e('Permanent Link to', 'churchope'); ?> <?php the_title_attribute(); ?>"  class="sermon_audio_link"><?php _e('Audio', 'churchope'); ?>
											</a>
											
										</li>
									<?php } ?>
									<?php if ($sermon_pdf){ ?>
										<li class="sermon_pdf">
											<a class="sermon_pdf_link" target="_blank" href="<?php echo $sermon_pdf; ?>">
												<?php _e('PDF', 'churchope'); ?>
											</a>
											
										</li>
									<?php } ?>
									<?php
										if (comments_open()) : 
											echo '<li class="sermon_comments">';

											comments_popup_link(__('Comments (0)', 'churchope'), __('Comment (1)', 'churchope'), __('Comments (%)', 'churchope'), 'commentslink');
											echo '</li>';
										endif;
									?>
									<li class="sermon_readmore">
										<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to', 'churchope'); ?> <?php the_title_attribute(); ?>"  class="sermon_readmore"><?php _e('Read more', 'churchope'); ?></a>
									</li>
								</ul>
								<?php //echo th_video('', $sermon_video ); ?>
							</div>
						<?php //} ?>
				


						<div class="entry-content <?php if (!get_option(SHORTNAME . "_excerpt")) {echo " entry-excerpt";} ?>">

							<?php
							if (get_option(SHORTNAME . "_excerpt"))
							{
								the_content('', false);
							}
							else
							{
								the_excerpt();
							}
							?>
						</div>

					</div>
				</div>
			</article>

			<?php
		/* Blog posts */
		else :
			?>

			<article <?php post_class('posts_listing') ?> id="post-<?php the_ID(); ?>">
				
				<?php
				if (has_post_thumbnail() && !get_option(SHORTNAME."_hidethumb"))
				{
					?>		
					<a href="<?php the_permalink() ?>" title="<?php echo the_title(); ?>" class="imgborder clearfix thumb listing"><?php get_theme_post_thumbnail(get_the_ID(), 'blog_thumbnail'); ?></a>
				<?php } ?>

					

				<div class="post_title_area">
					<?php if (!get_option(SHORTNAME."_hidedate")) { ?>
					<div class="postdate"><span></span><strong class="day"><?php echo get_the_date('d') ?></strong><strong class="month"><?php echo get_the_date('M') ?></strong></div>
					<?php } ?>
					<div class="blogtitles <?php if (get_option(SHORTNAME."_hidedate")) { echo ' nodate'; }?>">
						<h2 class="entry-title">
							<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to', 'churchope'); ?> <?php the_title_attribute(); ?>"><?php the_title(); ?>
							</a>
						</h2>
						<?php //if ($sermon_audio || $sermon_video || $sermon_pdf) { ?>
							<div class="sermon_attrs_blog">
								<ul class="sermons_meta">
									<?php if ($sermon_video){  ?>
										<li class="sermon_video">
											<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to', 'churchope'); ?> <?php the_title_attribute(); ?>"  class="sermon_video_link"><?php _e('Video', 'churchope'); ?>
											</a>
											
										</li>
									<?php } ?>
									<?php if ($sermon_audio){ ?>
										<li class="sermon_audio">
											<a href="<?php the_permalink() ?>#audio" rel="bookmark" title="<?php _e('Permanent Link to', 'churchope'); ?> <?php the_title_attribute(); ?>"  class="sermon_audio_link"><?php _e('Audio', 'churchope'); ?>
											</a>
											
										</li>
									<?php } ?>
									<?php if ($sermon_pdf){ ?>
										<li class="sermon_pdf">
											<a class="sermon_pdf_link" target="_blank"  href="<?php echo $sermon_pdf; ?>">
												<?php _e('PDF', 'churchope'); ?>
											</a>
											
										</li>
									<?php } ?>
									<?php
										if (comments_open()) : 
											echo '<li class="sermon_comments">';

											comments_popup_link(__('Comments (0)', 'churchope'), __('Comment (1)', 'churchope'), __('Comments (%)', 'churchope'), 'commentslink');
											echo '</li>';
										endif;
									?>
									<li class="sermon_readmore">
										<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php _e('Permanent Link to', 'churchope'); ?> <?php the_title_attribute(); ?>"  class="sermon_readmore"><?php _e('Read more', 'churchope'); ?></a>
									</li>
								</ul>
								<?php //echo th_video('', $sermon_video ); ?>
							</div>
						<?php //} ?>
				


						<div class="entry-content <?php if (!get_option(SHORTNAME . "_excerpt")) {echo " entry-excerpt";} ?>">

							<?php
							if (get_option(SHORTNAME . "_excerpt"))
							{
								the_content('', false);
							}
							else
							{
								the_excerpt();
							}
							?>
						</div>

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



