<?php 
/*
 * This file contains the HTML generated for full calendars. You can copy this file to yourthemefolder/plugins/events-manager/templates and modify it in an upgrade-safe manner.
 * 
 * There are two variables made available to you: 
 * 
 * 	$calendar - contains an array of information regarding the calendar and is used to generate the content
 *  $args - the arguments passed to EM_Calendar::output()
 * 
 * Note that leaving the class names for the previous/next links will keep the AJAX navigation working.
 */
$cal_count = count($calendar['cells']); //to prevent an extra tr
$col_count = $tot_count = 1; //this counts collumns in the $calendar_array['cells'] array
$col_max = count($calendar['row_headers']); //each time this collumn number is reached, we create a new collumn, the number of cells should divide evenly by the number of row_headers
?>
<div class="calendar_header">
<?php if(ICL_LANGUAGE_CODE=='zh'): ?>
<a id="ohbear-ical" href="<?php echo get_permalink(icl_object_id(4658, 'page', false)); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/3-schedule/subBtn.png"></a>
<?php elseif(ICL_LANGUAGE_CODE=='en'): ?>
<a id="ohbear-ical" href="<?php echo get_permalink(icl_object_id(4658, 'page', false)); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/3-schedule/subBtn.png"></a>
<?php elseif(ICL_LANGUAGE_CODE=='ja'): ?>
<a id="ohbear-ical" href="<?php echo get_permalink(icl_object_id(4658, 'page', false)); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/3-schedule/subBtn.png"></a>
<?php endif;?><a class="em-calnav full-link em-calnav-prev" href="<?php echo $calendar['links']['previous_url']; ?>"></a><span class="month_name"><?php echo ucfirst(date_i18n(get_option('dbem_full_calendar_month_format'), $calendar['month_start'])); ?></span><a class="em-calnav full-link em-calnav-next" href="<?php echo $calendar['links']['next_url']; ?>"></a><hr>
</div>
<div class="days-names"><span><?php echo implode('</span><span>',$calendar['row_headers']); ?></span></div>

<table class="em-calendar fullcalendar">
	<tbody>
		<tr>
			<?php
			foreach($calendar['cells'] as $date => $cell_data ){
				$class = ( !empty($cell_data['events']) && count($cell_data['events']) > 0 ) ? 'eventful':'eventless';
				if(!empty($cell_data['type'])){
					$class .= "-".$cell_data['type']; 
				}
				//In some cases (particularly when long events are set to show here) long events and all day events are not shown in the right order. In these cases, 
				//if you want to sort events cronologically on each day, including all day events at top and long events within the right times, add define('EM_CALENDAR_SORTTIME', true); to your wp-config.php file 
				if( defined('EM_CALENDAR_SORTTIME') && EM_CALENDAR_SORTTIME ) ksort($cell_data['events']); //indexes are timestamps
				?>
				<td class="<?php echo $class; ?>">
					<?php if( !empty($cell_data['events']) && count($cell_data['events']) > 0 ): ?>
					<?php echo date('j',$cell_data['date']); ?>
					<!--<ul>-->
						<?php echo EM_Events::output($cell_data['events'],array('format'=>get_option('dbem_full_calendar_event_format'))); ?>
						<?php if( $args['limit'] && $cell_data['events_count'] > $args['limit'] && get_option('dbem_display_calendar_events_limit_msg') != '' ): ?>
						<a href="<?php echo esc_url($cell_data['link']); ?>"><?php echo get_option('dbem_display_calendar_events_limit_msg'); ?></a>
						<?php endif; ?>
					<!--</ul>-->
					<?php else:?>
					<?php echo date('j',$cell_data['date']); ?>
					<?php endif; ?>
				</td>
				<?php
				//create a new row once we reach the end of a table collumn
				$col_count= ($col_count == $col_max ) ? 1 : $col_count+1;
				echo ($col_count == 1 && $tot_count < $cal_count) ? '</tr><tr>':'';
				$tot_count ++; 
			}
			?>
		</tr>
	</tbody>
</table>
