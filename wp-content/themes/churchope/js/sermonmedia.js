var sermonMedia = {
	vimeo: {},
	youtube: {},
	selfhosted:{},
	addVideoID : function(player, id){
		if(player == 'youtube')
		{
			this.youtube[id] = id;
		}
		else if(player == 'vimeo')
		{
			this.vimeo[id] = id;
		}
		else
		{
			this.selfhosted[id] = id;
		}
	},
	play:function(player, id) {
		if(player == 'youtube')
		{
			this.playYoutube(id);
		}
		else if(player == 'vimeo')
		{
			this.playVimeo(id);
		}
		else
		{
			this.playSelfhosted(id);
		}
	},
	playVimeo:function(id) {
		var vp = $f(jQuery('#'+id).get(0));
		if(vp)
		{
			vp.api('play');
		}
	},
	playYoutube:function(id) {
		var ytplayer1 = document.getElementById('id'+id);
		if(ytplayer1)
		{
			ytplayer1.playVideo();
		}
	},
	playSelfhosted:function(id) {
		jQuery(id).jPlayer('play');
	},
	addAudio:function(player, id ) {
		this.selfhosted[id] = id;
	},
	pauseOther:function(exceptID)
	{
		this.pauseYouTube(exceptID);
		this.pauseVimeo(exceptID);
		this.pauseSelfhosted(exceptID);
	},
	pauseYouTube: function(exceptID){
		for (id in this.youtube)
		{
			var ytplayer1 = document.getElementById(id);
			if(ytplayer1)
			{
				if(typeof ytplayer1.getPlayerState == 'function')
				{
					var ytp1State = ytplayer1.getPlayerState();
					if(ytp1State == 1 && (ytplayer1.id != exceptID))
					{
						ytplayer1.pauseVideo();
					}
				}
			}
		}
	},
	pauseVimeo: function(exceptID){
		for (id in this.vimeo)
		{
			if(id != exceptID )
			{
				var vp = $f(jQuery('#'+id).get(0));
				if(vp)
				{
					vp.api('pause');
				}
			}
		}
	},
	pauseSelfhosted: function(exceptID){
		for (id in this.selfhosted)
		{
			if(id != exceptID )
			{
				jQuery('#'+id).jPlayer("pause"); // pause all players except this one.;
			}
		}
	}
};

// single sermons tabs
function toggleMedia(player, id)
{
	jQuery('.sermon_attrs').find('#audio, #video, a.video, a.audio').toggleClass('active');
	sermonMedia.pauseOther('');
	setTimeout(function() { sermonMedia.play(player, id) }, 1000);
}

jQuery(document).ready(function() {
	jQuery('.single_sermons_meta a.audio').click(function(e){
		e.preventDefault();
		if (jQuery(this).hasClass('active')) {
			return;
		}
		var id = jQuery(this).closest('.sermon_attrs').find('.jp-jplayer').attr('id');
		toggleMedia('', '#'+id);
	});

	jQuery('.single_sermons_meta a.video').click(function(e){
		e.preventDefault();
		if (jQuery(this).hasClass('active')) {
			return;
		}
		var id/*player*/ = jQuery(this).closest('.sermon_attrs').find('#video').attr('data-player'); 
		toggleMedia('', '#'+id);
	});
});

jQuery(window).load(function(){

	 // Play audio
	if (window.location.hash == '#audio' || jQuery('.single_sermons_meta a.audio').hasClass('autoplay')) {
		var id = jQuery('.single_sermons_meta a.audio').closest('.sermon_attrs').find('.jp-jplayer').attr('id');
		toggleMedia('', '#'+id);
	}

	// Play video
	if (window.location.hash == '#video') {
		var player = jQuery('#video').attr('data-player');
		var id = jQuery('#video').find('iframe').attr('id');
		if(typeof id == 'undefined') {
			id = jQuery('#video').find('object').attr('id').substr(2);
		}
		setTimeout(function() { sermonMedia.play(player, id) }, 1000);
		
	}
});