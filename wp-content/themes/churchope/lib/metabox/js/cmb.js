
/**
 * Controls the behaviours of custom metabox fields.
 *
 * @author Andrew Norcross
 * @author Jared Atchison
 * @author Bill Erickson
 * @see    https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
 */

/*jslint browser: true, devel: true, indent: 4, maxerr: 50, sub: true */
/*global jQuery, tb_show, tb_remove */

/**
 * Custom jQuery for Custom Metaboxes and Fields
 */
jQuery(document).ready(function($) {
	'use strict';

	var formfield;

	/**
	 * Initialize timepicker (this will be moved inline in a future release)
	 */
	$('.cmb_timepicker').each(function() {
		$('#' + jQuery(this).attr('id')).timePicker({
			show24Hours: time_24_format,
			separator: ':',
			step: 30
		});
	});

	/**
	 * Initialize jQuery UI datepicker (this will be moved inline in a future release)
	 */
	$('.cmb_datepicker').each(function() {
		$('#' + jQuery(this).attr('id')).datepicker();
		// $('#' + jQuery(this).attr('id')).datepicker({ dateFormat: 'yy-mm-dd' });
		// For more options see http://jqueryui.com/demos/datepicker/#option-dateFormat
	});
	// Wrap date picker in class to narrow the scope of jQuery UI CSS and prevent conflicts
	$("#ui-datepicker-div").wrap('<div class="cmb_element" />');


	if (jQuery('#ch_event_date').length) {

		jQuery('#ch_event_skipped_dates').attr('readonly', 'readonly').multiDatesPicker({
			dateFormat: "mm/dd/yy",
			addDates: (jQuery('#ch_event_skipped_dates').val() === '') ? jQuery('#ch_event_skipped_dates').val() : jQuery('#ch_event_skipped_dates').val().split(','),
			minDate: (jQuery('#ch_event_date').val() === '') ? '' : jQuery('#ch_event_date').val(),
			maxDate: (jQuery('#ch_event_end_date').val() === '') ? '' : jQuery('#ch_event_end_date').val()
		});


		if (jQuery('#ch_event_date').val() === '') {
			jQuery('#ch_event_is_repeat,#ch_event_interval,#ch_event_time,#ch_event_end_date,#ch_event_skipped_dates').each(function() {
				jQuery(this).closest('tr').hide();
			});

			jQuery('#ch_event_date').on('change', function() {
				jQuery('#ch_event_is_repeat,#ch_event_time').each(function() {
					jQuery(this).closest('tr').show();
				});

			});
		} else {

			if (jQuery('#ch_event_is_repeat').is(':checked')) {

				jQuery('#ch_event_is_repeat,#ch_event_time').each(function() {
					jQuery(this).closest('tr').show();
				});

				jQuery('#ch_event_end_date').datepicker('option', {minDate: jQuery('#ch_event_date').val()});
				jQuery('#ch_event_skipped_dates').datepicker('option', {minDate: jQuery('#ch_event_date').val()});
				jQuery('#ch_event_skipped_dates').datepicker('option', {maxDate: jQuery('#ch_event_end_date').val()});


				if (jQuery('#ch_event_end_date').val() !== '') {
					jQuery('#ch_event_date').datepicker('option', {maxDate: jQuery('#ch_event_end_date').val()});
				}
			}

		}



		jQuery('#ch_event_is_repeat').on('change', function() {
			if (jQuery('#ch_event_is_repeat').is(':checked')) {
				jQuery('#ch_event_interval,#ch_event_end_date,#ch_event_skipped_dates').each(function() {
					jQuery(this).closest('tr').show();
				});
			} else {
				jQuery('#ch_event_interval,#ch_event_end_date,#ch_event_skipped_dates').each(function() {
					jQuery(this).closest('tr').hide();
				});
			}
		});




		jQuery('#ch_event_date').on('change', function() {
			jQuery('#ch_event_end_date').datepicker('option', {minDate: jQuery('#ch_event_date').val()});
			jQuery('#ch_event_skipped_dates').datepicker('option', {minDate: jQuery('#ch_event_date').val()});
			jQuery('#ch_event_skipped_dates').multiDatesPicker({
				dateFormat: "mm/dd/yy",
				addDates: (jQuery('#ch_event_skipped_dates').val() === '') ? jQuery('#ch_event_skipped_dates').val() : jQuery('#ch_event_skipped_dates').val().split(','),
				minDate: (jQuery('#ch_event_date').val() === '') ? '' : jQuery('#ch_event_date').val(),
				maxDate: (jQuery('#ch_event_end_date').val() === '') ? '' : jQuery('#ch_event_end_date').val()
			});
		});

		jQuery('#ch_event_end_date').on('change', function() {
			jQuery('#ch_event_date').datepicker('option', {maxDate: jQuery('#ch_event_end_date').val()});
			jQuery('#ch_event_skipped_dates').datepicker('option', {maxDate: jQuery('#ch_event_end_date').val()});
			jQuery('#ch_event_skipped_dates').multiDatesPicker({
				dateFormat: "mm/dd/yy",
				addDates: (jQuery('#ch_event_skipped_dates').val() === '') ? jQuery('#ch_event_skipped_dates').val() : jQuery('#ch_event_skipped_dates').val().split(','),
				minDate: (jQuery('#ch_event_date').val() === '') ? '' : jQuery('#ch_event_date').val(),
				maxDate: (jQuery('#ch_event_end_date').val() === '') ? '' : jQuery('#ch_event_end_date').val()
			});
		});
	}
	//events metaboxes

	//header colors metaboxes
	jQuery('#ch_page_header_color, #ch_post_menupattern, #ch_post_headerpattern_repeat, #ch_post_headerpattern_x, #ch_post_headerpattern_y ').closest('tr').css('display', 'none');
	if (jQuery('#ch_custom_post_header').val() === 'custom') {
		jQuery('#ch_page_header_color, #ch_post_menupattern, #ch_post_headerpattern_repeat, #ch_post_headerpattern_x, #ch_post_headerpattern_y ').closest('tr').show();
	}

	jQuery('#ch_custom_post_header').on('change', function() {
		if (jQuery(this).val() === 'custom') {
			jQuery('#ch_page_header_color, #ch_post_menupattern, #ch_post_headerpattern_repeat, #ch_post_headerpattern_x, #ch_post_headerpattern_y ').closest('tr').show();
		} else {
			jQuery('#ch_page_header_color, #ch_post_menupattern, #ch_post_headerpattern_repeat, #ch_post_headerpattern_x, #ch_post_headerpattern_y ').closest('tr').css('display', 'none');
		}
	});

	//slideshow metaboxes
	jQuery('#ch_post_slider_cat, #ch_post_slider_count, #ch_global_slider_alias').closest('tr').css('display', 'none');
	jQuery('#slideshow_effect_options, #mymetabox_revslider_0').css('display', 'none');

	if (jQuery('#ch_post_slider').val() === 'revSlider') {
		jQuery('#ch_global_slider_alias').closest('tr').show();
		jQuery('#mymetabox_revslider_0').show();
	} else if (jQuery('#ch_post_slider').val() === 'jCycle') {
		jQuery('#ch_post_slider_cat, #ch_post_slider_count').closest('tr').show();
		jQuery('#slideshow_effect_options').show();
	}

	jQuery('#ch_post_slider').on('change', function() {
		if (jQuery('#ch_post_slider').val() === 'revSlider') {
			jQuery('#ch_global_slider_alias').closest('tr').show();
			jQuery('#mymetabox_revslider_0').show();
			jQuery('#ch_post_slider_cat, #ch_post_slider_count').closest('tr').css('display', 'none');
			jQuery('#slideshow_effect_options').css('display', 'none');
		} else if (jQuery('#ch_post_slider').val() === 'jCycle') {
			jQuery('#ch_post_slider_cat, #ch_post_slider_count').closest('tr').show();
			jQuery('#slideshow_effect_options').show();
			jQuery('#ch_global_slider_alias').closest('tr').css('display', 'none');
			jQuery('#mymetabox_revslider_0').css('display', 'none');
		} else {
			jQuery('#ch_post_slider_cat, #ch_post_slider_count, #ch_global_slider_alias').closest('tr').css('display', 'none');
			jQuery('#slideshow_effect_options, #mymetabox_revslider_0').css('display', 'none');
		}
	});




	/**
	 * Initialize color picker
	 */
	$('input:text.cmb_colorpicker').each(function(i) {
		$(this).after('<div id="picker-' + i + '" style="z-index: 1000; background: #EEE; border: 1px solid #CCC; position: absolute; display: block;"></div>');
		$('#picker-' + i).hide().farbtastic($(this));
	})
			.focus(function() {
				$(this).next().show();
			})
			.blur(function() {
				$(this).next().hide();
			});

	/**
	 * File and image upload handling
	 */
	$('.cmb_upload_file').change(function() {
		formfield = $(this).attr('name');
		$('#' + formfield + '_id').val("");
	});

	$('.cmb_upload_button').live('click', function() {
		var buttonLabel;
		formfield = $(this).prev('input').attr('name');
		buttonLabel = 'Use as ' + $('label[for=' + formfield + ']').text();
		tb_show('', 'media-upload.php?post_id=' + $('#post_ID').val() + '&type=file&cmb_force_send=true&cmb_send_label=' + buttonLabel + '&TB_iframe=true');
		return false;
	});

	$('.cmb_remove_file_button').live('click', function() {
		formfield = $(this).attr('rel');
		$('input#' + formfield).val('');
		$('input#' + formfield + '_id').val('');
		$(this).parent().remove();
		return false;
	});

	window.original_send_to_editor = window.send_to_editor;
	window.send_to_editor = function(html) {
		var itemurl, itemclass, itemClassBits, itemid, htmlBits, itemtitle,
				image, uploadStatus = true;

		if (formfield) {

			if ($(html).html(html).find('img').length > 0) {
				itemurl = $(html).html(html).find('img').attr('src'); // Use the URL to the size selected.
				itemclass = $(html).html(html).find('img').attr('class'); // Extract the ID from the returned class name.
				itemClassBits = itemclass.split(" ");
				itemid = itemClassBits[itemClassBits.length - 1];
				itemid = itemid.replace('wp-image-', '');
			} else {
				// It's not an image. Get the URL to the file instead.
				htmlBits = html.split("'"); // jQuery seems to strip out XHTML when assigning the string to an object. Use alternate method.
				itemurl = htmlBits[1]; // Use the URL to the file.
				itemtitle = htmlBits[2];
				itemtitle = itemtitle.replace('>', '');
				itemtitle = itemtitle.replace('</a>', '');
				itemid = ""; // TO DO: Get ID for non-image attachments.
			}

			image = /(jpe?g|png|gif|ico)$/gi;

			if (itemurl.match(image)) {
				uploadStatus = '<div class="img_status"><img src="' + itemurl + '" alt="" /><a href="#" class="cmb_remove_file_button" rel="' + formfield + '">Remove Image</a></div>';
			} else {
				// No output preview if it's not an image
				// Standard generic output if it's not an image.
				html = '<a href="' + itemurl + '" target="_blank" rel="external">View File</a>';
				uploadStatus = '<div class="no_image"><span class="file_link">' + html + '</span>&nbsp;&nbsp;&nbsp;<a href="#" class="cmb_remove_file_button" rel="' + formfield + '">Remove</a></div>';
			}

			$('#' + formfield).val(itemurl);
			$('#' + formfield + '_id').val(itemid);
			$('#' + formfield).siblings('.cmb_upload_status').slideDown().html(uploadStatus);
			tb_remove();

		} else {
			window.original_send_to_editor(html);
		}

		formfield = '';
	};
});