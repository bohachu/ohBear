<?php

/**
 * Sermon Speakers Widget
 *
 */
//Speakers
class Widget_Sermon_Speakers extends Widget_Default
{

	/**
	 * Option for display all speeakers
	 */
	const ALL = 'all';

	function __construct()
	{
		$this->setClassName('widget_sermon_speakers');
		$this->setName(__('Sermon Speakers', 'churchope'));
		$this->setDescription(__('A list of Sermon Speakers', 'churchope'));
		$this->setIdSuffix('sermon-speakers');
		parent::__construct();
	}

	function widget($args, $instance)
	{
		extract($args);

		$title = apply_filters('widget_title', empty($instance['title']) ? __('Sermon Speakers', 'churchope') : $instance['title'], $instance, $this->id_base);
		$selected_speakers = $instance['selected_speakers'];
		$speakers = array();

		echo $before_widget;
		if ($title)
			echo $before_title . $title . $after_title;
		?>

		<ul>
			<?php
			if ($this->displayAll($selected_speakers))
			{
				$speakers = $this->getAllSpeakers();
			}
			else
			{
				foreach ($selected_speakers as $speaker)
				{
					if (get_term($speaker, Custom_Posts_Type_Sermon::TAXONOMY_SPEAKER))
					{
						$speakers[] = get_term($speaker, Custom_Posts_Type_Sermon::TAXONOMY_SPEAKER);
					}
				}
			}
			if ($speakers)
			{
				foreach ($speakers as $speaker)
				{
					?>
					<li>
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
						<div class="recent_txt">
							<a href="<?php echo esc_url(get_term_link($speaker)) ?>"><?php echo $speaker->name; ?></a>
							<?php echo '<p>' . esc_textarea(get_tax_meta($speaker->term_id, SHORTNAME . "_speaker_excerpt", true)) . '</p>'; ?>
						</div>
					</li>

					<?php
				} //eof foreach
			} //eof no speakers check
			?>
		</ul>
		<?php
		echo $after_widget;
	}

	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['selected_speakers'] = ( $new_instance['selected_speakers'] ) ? (array) $new_instance['selected_speakers'] : array();
		return $instance;
	}

	function form($instance)
	{
		//Defaults
		$instance = wp_parse_args((array) $instance, array('title' => '', 'selected_speakers' => ''));
		$title = esc_attr($instance['title']);
		$selected_speakers = (array) $instance['selected_speakers'];
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'churchope'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

		<p>
			<label for="<?php echo $this->get_field_id('selected_speakers'); ?>"><?php _e('Speakers (multiple):', 'churchope'); ?></label><br>
			<select class="widefat" name="<?php echo $this->get_field_name('selected_speakers'); ?>[]" multiple="multiple" id="<?php echo $this->get_field_id('selected_speakers'); ?>">
				<option value="<?php echo self::ALL; ?>" <?php echo (in_array(self::ALL, $selected_speakers)) ? 'selected' : ''; ?>><?php _e('All', 'churchope'); ?></option>
				<?php
				$cat_args['taxonomy'] = Custom_Posts_Type_Sermon::TAXONOMY_SPEAKER;
				$cat_args['hide_empty'] = 0;
				//wp_list_categories(apply_filters('widget_categories_args', $cat_args));
				$speakers = get_categories($cat_args);
				foreach ($speakers as $speaker)
				{
					?>
					<option value="<?php echo $speaker->term_id; ?>"<?php echo (in_array($speaker->term_id, $selected_speakers)) ? ' selected' : ''; ?>><?php echo $speaker->name; ?></option>			
				<?php } //eof foreach ?>
			</select>

		</p>
		<?php
	}

	/**
	 * Check is need display all speakers.
	 * display all if 'All' or no one selected
	 *
	 * @param array $speakers multiselect form 
	 * @return type
	 */
	private function displayAll($speakers)
	{
		return empty($speakers) || in_array(self::ALL, $speakers);
	}

	private function getAllSpeakers()
	{
		return get_terms(Custom_Posts_Type_Sermon::TAXONOMY_SPEAKER, array('orderby' => 'name', 'hide_empty' => false,));
	}

}
