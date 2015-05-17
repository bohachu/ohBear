jQuery.noConflict();

function file_rm_ajax() {
	jQuery(document).on("click", "input[name^='file_rm']", function() {

		var $fileId = jQuery(this).attr("id");
		var type = jQuery(this).data('type');
//		var $fileName = jQuery("#"+$fileId).val();
//		jQuery("#"+$fileId).remove();



		jQuery.ajax({
			type: "post",
			url: $AjaxUrl,
			data: {
				action: "file_rm",
				file_id: $fileId,
				_ajax_nonce: $ajaxNonce},
			beforeSend: function() {
				jQuery("." + $fileId).css({display: ""});
			}, //fadeIn loading just when link is clicked
			success: function() { //so, if data is retrieved, store it in html
				jQuery("#file_deleted_" + $fileId).fadeOut("fast"); //animation
				jQuery("#ox_img_frame_" + $fileId).css({display: ""});

				if (type != 'favicon')
				{
					jQuery("#image_" + $fileId).parent().fadeOut("fast");
				}
				else
				{
					jQuery("#image_" + $fileId).parent().find('img').fadeOut("fast");
				}
			}
		}); //close jQuery.ajax
		return false;
	});
}

function file_up_ajax()
{
	jQuery(document).on("click", "input.button[id^='file_up_']", function() {
		var $this = jQuery(this), tbframeInterval;
		var option_id = $this.attr('id').replace(/^file_up_/, '');
		tb_show('', 'media-upload.php?post_id=0&type=image&TB_iframe=true&width=670&height=600');
		tbframeInterval = setInterval(function() {
			jQuery('#TB_iframeContent').contents().find('.savesend .button').val('Use This Image');
			jQuery('#TB_iframeContent').contents().find('#go_button').val('Use This Image');
		}, 1000);

		// Send img url
		window.send_to_editor = function(html) {
			var imgUrl = '';
			clearInterval(tbframeInterval);

			var HTMLObj = jQuery(html);

			if (HTMLObj.attr('href'))
			{
				imgUrl = HTMLObj.attr('href');
			}
			else
			{
				imgUrl = HTMLObj.attr('src');
			}

			if (!imgUrl || imgUrl.length == 0)
			{
				imgUrl = jQuery(html).attr('src');
			}

			if (imgUrl && imgUrl.length)
			{
				jQuery.ajax({
					type: "post",
					url: $AjaxUrl,
					data: {
						action: "file_up",
						field_id: option_id,
						src: imgUrl,
						_ajax_nonce: $ajaxNonce},
					success: function() {
						var img = jQuery('#image_file_rm_' + option_id);
						var type = $this.data('type');
						if (type != 'favicon' && img && img.length)
						{
							img.attr('src', imgUrl);
							img.attr('title', '<img src="' + imgUrl + '" alt=\'\' />');
							img.parent().show();
							jQuery("#file_deleted_file_rm_" + option_id).fadeIn("fast"); //animation
						}
						else
						{
							if (type == 'favicon') {
								var html = '<img src="' + imgUrl + '" alt="" width="16" height="16" id="image_file_rm_' + option_id + '" class="ox_img"><div class="image-label float-left"><input type="button" id="file_up_' + option_id + '" class="button" value="Upload" data-type="favicon"></div><div id="file_deleted_file_rm_' + option_id + '" class="float-left"><input type="button" name="file_rm_' + option_id + '" id="file_rm_' + option_id + '" class="button" value="Delete" data-type="favicon"></div>';
								$this.closest('.favicon_bg').html(html);
								jQuery('#image_file_rm_' + option_id).show();
							}
							else {
								jQuery('<div class="float-left image_wrap">'
										+ '<img src="' + imgUrl + '" alt=""   id="image_file_rm_' + option_id + '" class="ox_img" title="&lt;img src=\"' + imgUrl + '\" alt=\'\' /&rt;"  />'
										+ '</div>'
										+ '<div id="file_deleted_file_rm_' + option_id + '">'
										+ '<div>'
										+ '<input type="button" name="file_rm_' + option_id + '" id="file_rm_' + option_id + '" class="button" value="Delete" />'
										+ '</div>'
										+ '</div>').insertBefore($this.closest('div.image-label'));
							}
						}
						/*{	
						 //custom upload
						 var imgWrapClass = '';
						 var delBtnClass = '';
						 if (obj.data('type')=='common') 
						 {
						 imgWrapClass='float-left image_wrap';
						 jQuery('<div class="'+imgWrapClass+'"><img src="'+imgUrl+'" alt="" id="image_file_rm_'+option_id+'" class="lf_img" /></div>'
						 +'<div id="file_deleted_file_rm_'+option_id+'" class="'+delBtnClass+'">'
						 +'<div><input type="button" name="file_rm_'+option_id+'" id="file_rm_'+option_id+'" class="button" value="Delete" /></div>'
						 +'</div>'
						 +'</div>').insertBefore(obj.closest('div.image-label'));
						 } else {
						 imgWrapClass='favicon_bg';
						 delBtnClass = 'float-left';
						 jQuery('<div class="'+imgWrapClass+'"><img src="'+imgUrl+'" alt="" id="image_file_rm_'+option_id+'" class="lf_img" /></div>'
						 +'<div id="file_deleted_file_rm_'+option_id+'" class="'+delBtnClass+'">'
						 +'<div><input type="button" name="file_rm_'+option_id+'" id="file_rm_'+option_id+'" class="button" value="Delete" /></div>'
						 +'</div>'
						 +'</div>').insertBefore(obj.closest('div.image-label'));
						 }
						 
						 }*/
					}
				}); //close jQuery.ajax
			}
			tb_remove();
		};

	});
}


function install_dummy() {
	jQuery("input[name^='install_dummy']").bind("click", function() {
		var dummy_type = jQuery("input:radio:checked").val();

		jQuery.ajax({
			type: "post",
			url: $AjaxUrl,
			dataType: 'json',
			data: {action: "install_dummy", dummy_type: dummy_type, _ajax_nonce: $ajaxNonce},
			beforeSend: function() {
				jQuery(".install_dummy_result").html('');
				jQuery(".install_dummy_loading").css({display: "block"});
				jQuery("input[name^='install_dummy']").attr('disabled', 'disabled');
				jQuery(".install_dummy_result").html("Importing dummy content...<br /> Please wait, it can take up to a few minutes.");

			}, //fadeIn loading just when link is clicked
			success: function(response) { //so, if data is retrieved, store it in html
				jQuery("input[name^='install_dummy']").removeAttr('disabled');
				var dummy_result = jQuery(".install_dummy_result");
				if (typeof response != 'undefined')
				{
					if (response.hasOwnProperty('status'))
					{
						switch (response.status)
						{
							case 'success':
								jQuery("input[name^='install_dummy']").remove();
								dummy_result.html('Completed');
								break;
							case 'error':
								dummy_result.html('<font color="red">' + response.data + '</font>');
								if (!response.hasOwnProperty('need_plugin'))
								{
									jQuery("input[name^='install_dummy']").remove();
								}
								break;
							default:
								break;
						}

					}
				}
				//				jQuery(".install_dummy_loading").css({display:"none"});
			},
			complete: function() {
				jQuery(".install_dummy_loading").css({display: "none"});
			}
		}); //close jQuery.ajax
		return false;
	});
}



jQuery(document).ready(function() {
	jQuery(":checkbox:not('.sd_check')").iButton();

	jQuery('.th_help[title], .th_img[title]').qtip({
		content: {
			text: false
		},
		style: {
			tip: "bottomLeft",
			classes: "ui-tooltip-dark"
		},
		position: {
			at: "top right",
			my: "bottom left"
		}
	}
	);


	function sidebar_rm_ajax() {
		jQuery("input[name^='sidebar_rm']").bind("click", function() {

			var $sidebarId = jQuery(this).attr("id");
			var $sidebarName = jQuery("#sidebar_generator_" + $sidebarId).val();
			jQuery("#sidebar_generator_" + $sidebarId).remove();

			var $arraySidebarInputs = new Array;
			jQuery("input[name^='sidebar_generator_']").each(function(id) {
				$updateSidebars = jQuery("input[name^='sidebar_generator_']").get(id);
				$arraySidebarInputs.push($updateSidebars.value);
			});

			var $sidebarInputsStr = $arraySidebarInputs.join(",");

			jQuery.ajax({
				type: "post",
				url: $AjaxUrl,
				data: {
					action: "sidebar_rm",
					sidebar: $sidebarInputsStr,
					sidebar_id: $sidebarId,
					sidebar_name: $sidebarName,
					_ajax_nonce: $ajaxNonce
				},
				beforeSend: function() {
					jQuery(".sidebar_rm_" + $sidebarId).css({display: ""}); //fadeIn loading just when link is clicked
				},
				success: function(html) { //so, if data is retrieved, store it in html
					jQuery("#sidebar_cell_" + $sidebarId).fadeOut("fast"); //animation
				}
			}); //close jQuery.ajax
			return false;
		});
	}
//Google fonts preview
	function changegfont() {
		var str = "";
		jQuery("[id$=_gfont] option:selected").each(function() {
			str += jQuery(this).text() + "";
		});
		if (str && str.length)
		{
			var link = ("<link rel='stylesheet' type='text/css' href='//fonts.googleapis.com/css?family=" + str + "' media='screen' />");
			jQuery("head").append(link);
			jQuery(".gfont_preview").css("font-family", str);
		}

	}
	jQuery("[id$=_gfont]").closest("div").before('<div class="gfont_preview">The quick brown fox jumps over the lazy dog</div>');
	changegfont();
	jQuery("[id$=_gfont]").change(function() {
		changegfont();
	});


	jQuery("[id$=_gfont]").keyup(function() {
		changegfont();
	});


	jQuery("[id$=_gfont]").keydown(function() {
		changegfont();
	});

	/*
	 * Toggling group of elements.
	 */
	jQuery('label.toggle').click(function() {
		var ul = jQuery(this).closest('li').find('ul:first');
		if (ul && ul.length > 0)
		{
			jQuery(this).toggleClass('down');
			ul.toggle('slow');
		}
	});

// Reset button check
	jQuery('.th_reset').click(function(e) {
		e.preventDefault();
		var result = confirm("Reset all options?");
		if (result == true) {
			jQuery('#th_reset').submit();
		}
	});

//Theme options conditions

//on load options

	jQuery('#ch_boxedbackground, #ch_boxedpattern, #file_up_ch_boxedpattern, #ch_boxedpattern_repeat, #ch_boxedpattern_x, #ch_boxedpattern_y, #ch_gfont, #ch_global_slider_alias,#ch_global_slider_cat, #ch_global_slider_count, #ch_global_slider_timeout, #ch_global_slider_speed, #ch_global_slider_navigation, #ch_global_slider_fixedheight, #ch_global_slider_padding, #ch_global_slider_pause, #ch_global_slider_autoscroll').closest('li').css('display', 'none');

	//Slideshow page
	if (jQuery('#ch_global_slider').val() === 'revSlider') {
		jQuery('#ch_global_slider_alias').closest('li').slideDown(300);
	} else if (jQuery('#ch_global_slider').val() === 'jCycle') {
		jQuery('#ch_global_slider_cat, #ch_global_slider_count, #ch_global_slider_timeout, #ch_global_slider_speed, #ch_global_slider_navigation, #ch_global_slider_fixedheight, #ch_global_slider_padding, #ch_global_slider_pause, #ch_global_slider_autoscroll').closest('li').slideDown(300);
	}

	jQuery('#ch_global_slider').on('change', function() {
		if (jQuery('#ch_global_slider').val() === 'revSlider') {
			jQuery('#ch_global_slider_alias').closest('li').slideDown(300);
			jQuery('#ch_global_slider_cat, #ch_global_slider_count, #ch_global_slider_timeout, #ch_global_slider_speed, #ch_global_slider_navigation, #ch_global_slider_fixedheight, #ch_global_slider_padding, #ch_global_slider_pause, #ch_global_slider_autoscroll').closest('li').css('display', 'none');
		} else if (jQuery('#ch_global_slider').val() === 'jCycle') {
			jQuery('#ch_global_slider_alias').closest('li').css('display', 'none');
			jQuery('#ch_global_slider_cat, #ch_global_slider_count, #ch_global_slider_timeout, #ch_global_slider_speed, #ch_global_slider_navigation, #ch_global_slider_fixedheight, #ch_global_slider_padding, #ch_global_slider_pause, #ch_global_slider_autoscroll').closest('li').slideDown(300);
		} else {
			jQuery('#ch_global_slider_alias, #ch_global_slider_cat, #ch_global_slider_count, #ch_global_slider_timeout, #ch_global_slider_speed, #ch_global_slider_navigation, #ch_global_slider_fixedheight, #ch_global_slider_padding, #ch_global_slider_pause, #ch_global_slider_autoscroll').closest('li').css('display', 'none');
		}
	});

	//Blog page
	jQuery('#ch_blog_revslider,#ch_blog_slider_cat, #ch_blog_slider_count, #ch_blog_slider_timeout, #ch_blog_slider_speed, #ch_blog_slider_navigation, #ch_blog_slider_fixedheight, #ch_blog_slider_padding, #ch_blog_slider_pause, #ch_blog_slider_autoscroll').closest('li').css('display', 'none');
	if (jQuery('#ch_blog_slider').val() === 'revSlider') {
		jQuery('#ch_blog_revslider').closest('li').slideDown(300);
	} else if (jQuery('#ch_blog_slider').val() === 'jCycle') {
		jQuery('#ch_blog_slider_cat, #ch_blog_slider_count, #ch_blog_slider_timeout, #ch_blog_slider_speed, #ch_blog_slider_navigation, #ch_blog_slider_fixedheight, #ch_blog_slider_padding, #ch_blog_slider_pause, #ch_blog_slider_autoscroll').closest('li').slideDown(300);
	}

	jQuery('#ch_blog_slider').on('change', function() {
		if (jQuery('#ch_blog_slider').val() === 'revSlider') {
			jQuery('#ch_blog_revslider').closest('li').slideDown(300);
			jQuery('#ch_blog_slider_cat, #ch_blog_slider_count, #ch_blog_slider_timeout, #ch_blog_slider_speed, #ch_blog_slider_navigation, #ch_blog_slider_fixedheight, #ch_blog_slider_padding, #ch_blog_slider_pause, #ch_blog_slider_autoscroll').closest('li').css('display', 'none');
		} else if (jQuery('#ch_blog_slider').val() === 'jCycle') {
			jQuery('#ch_blog_revslider').closest('li').css('display', 'none');
			jQuery('#ch_blog_slider_cat, #ch_blog_slider_count, #ch_blog_slider_timeout, #ch_blog_slider_speed, #ch_blog_slider_navigation, #ch_blog_slider_fixedheight, #ch_blog_slider_padding, #ch_blog_slider_pause, #ch_blog_slider_autoscroll').closest('li').slideDown(300);
		} else {
			jQuery('#ch_blog_revslider, #ch_blog_slider_cat, #ch_blog_slider_count, #ch_blog_slider_timeout, #ch_blog_slider_speed, #ch_blog_slider_navigation, #ch_blog_slider_fixedheight, #ch_blog_slider_padding, #ch_blog_slider_pause, #ch_blog_slider_autoscroll').closest('li').css('display', 'none');
		}
	});

	//General page
	if (!jQuery('#ch_gfontdisable').is(":checked")) {
		jQuery('#ch_gfont').closest('li').slideDown(300);
	}

	jQuery('#ch_gfontdisable').on('change', function() {
		if (!jQuery('#ch_gfontdisable').is(":checked")) {
			jQuery('#ch_gfont').closest('li').slideDown(300);
		} else {
			jQuery('#ch_gfont').closest('li').css('display', 'none');
		}
	});
	if (jQuery('#ch_boxed').is(":checked")) {
		jQuery('#ch_boxedbackground, #ch_boxedpattern,#file_up_ch_boxedpattern, #ch_boxedpattern_repeat, #ch_boxedpattern_x, #ch_boxedpattern_y').closest('li').slideDown(300);
	}

	jQuery('#ch_boxed').on('change', function() {
		if (jQuery('#ch_boxed').is(":checked")) {
			jQuery('#ch_boxedbackground, #ch_boxedpattern, #file_up_ch_boxedpattern, #ch_boxedpattern_repeat, #ch_boxedpattern_x, #ch_boxedpattern_y').closest('li').slideDown(300);
		} else {
			jQuery('#ch_boxedbackground, #ch_boxedpattern, #file_up_ch_boxedpattern, #ch_boxedpattern_repeat, #ch_boxedpattern_x, #ch_boxedpattern_y').closest('li').css('display', 'none');
		}
	});




	sidebar_rm_ajax();
	file_up_ajax()
	file_rm_ajax();
	install_dummy();
});