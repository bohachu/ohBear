<?php

/**
 * Social Links Widget. 
 */
class Widget_SocialLinks extends Widget_Default
{
	const HIDE			= 'hide_icon';
	const TARGET			= 'target_blank';
	const TITLE			= 'title';
        
        const RSS			= 'rss_feed';
	const RSS_TITLE			= 'rss_feed_title';
        
        const FACEBOOK			= 'facebook_account';
	const FACEBOOK_TITLE            = 'facebook_account_title';
 
        const TWITTER			= 'twitter_account';
	const TWITTER_TITLE		= 'twitter_account_title';
        
	const DRIBBLE			= 'dribble_account';
	const DRIBBLE_TITLE		= 'dribble_account_title';
        
	const FLIKER			= 'flicker_account';
	const FLIKER_TITLE		= 'flicker_account_title';
        
	const VIMEO			= 'vimeo_account';
	const VIMEO_TITLE		= 'vimeo_account_title';
        
        const EMAIL			= 'email_to';
	const EMAIL_TITLE		= 'email_to_title';
                
	const YOUTUBE			= 'youtube_account';
	const YOUTUBE_TITLE		= 'youtube_account_title';
        
        const PINTEREST			= 'pinterest_account';
	const PINTEREST_TITLE           = 'pinterest_account_title';
        
        const GOOGLE_PLUS		= 'google_plus_account';
	const GOOGLE_PLUS_TITLE         = 'google_plus_account_title';
            
        const LINKED_IN			= 'linked_in_account';
	const LINKED_IN_TITLE           = 'linked_in_account_title';
        
            //////////////////////////////////////
           
        const PICASA                    = 'picasa_account';
	const PICASA_TITLE              = 'picasa_account_title';
        
        const DIGG			= 'digg_account';
	const DIGG_TITLE		= 'digg_account_title';
        
        const PLURK			= 'plurk_account';
	const PLURK_TITLE		= 'plurk_account_title';
        
        const TRIPADVISOR		= 'tripadvisor_account';
	const TRIPADVISOR_TITLE		= 'tripadvisor_account_title';
        
        const YAHOO			= 'yahoo_account';
	const YAHOO_TITLE		= 'yahoo_account_title';
        
        const DELICIOUS			= 'delicious_account';
	const DELICIOUS_TITLE		= 'delicious_account_title';
        
        const DEVIANART			= 'devianart_account';
	const DEVIANART_TITLE		= 'devianart_account_title';
        
        const TUMBLR			= 'tumblr_account';
	const TUMBLR_TITLE		= 'tumblr_account_title';
        
        const SKYPE			= 'skype_account';
	const skype_TITLE		= 'skype_account_title';
        
        const APPLE			= 'apple_account';
	const APPLE_TITLE		= 'apple_account_title';
        
        const AIM			= 'aim_account';
	const AIM_TITLE                 = 'aim_account_title';
        
        const PAYPAL			= 'paypal_account';
	const PAYPAL_TITLE		= 'paypal_account_title';
        
        const BLOGGER			= 'blogger_account';
	const BLOGGER_TITLE		= 'blogger_account_title';
        
        const BEHANCE			= 'behance_account';
	const BEHANCE_TITLE		= 'behance_account_title';
        
        const MYSPACE			= 'myspace_account';
	const MYSPACE_TITLE		= 'myspace_account_title';
        
        const STUMBLE			= 'stumble_account';
	const STUMBLE_TITLE		= 'stumble_account_title';
        
        const FORRST			= 'forrst_account';
	const FORRST_TITLE		= 'forrst_account_title';
        
        const IMDB			= 'imdb_account';
	const IMDB_TITLE		= 'imdb_account_title';
        
        const INSTAGRAM			= 'instagram_account';
	const INSTAGRAM_TITLE		= 'instagram_account_title';
	
	
	function __construct()
	{
		$this->setClassName('widget_social_links');
		$this->setName(__('Social Links','churchope'));
		$this->setDescription(__('Show social network links','churchope'));
		$this->setIdSuffix('social-links');
		$this->setWidth(400);
		parent::__construct();
	}
	
	public function widget($args, $instance)
	{
		$frontend_html = '';
		$social_link_list = $this->getFields(); // array 'id'->'link'
		
		if(isset($instance[self::TITLE]))
		{
			$title = apply_filters( 'widget_title', $instance[self::TITLE] );			
		}	
		
		$link_class  = (isset($instance[self::HIDE]) && $instance[self::HIDE])?' no_icon':'';
		
		$target  = (isset($instance[self::TARGET]) && $instance[self::TARGET])?' target="_blank"':'';
		
		$frontend_html = $args['before_widget'];
		if ( $title )
		{
			$frontend_html .= $args['before_title'] . $title . $args['after_title'];
		}
		
		$frontend_html .= '<ul>';
		foreach($instance as $id=>$account)
		{
			if($id != self::TITLE) // Not show title in link list
			{
				if(strlen($account) && isset($social_link_list[$id]))
				{
					$http = 'http://';
					$frontend_html .= '<li>';
					if($id == self::EMAIL)
					{
						$http = '';
					}
					
					if($id == self::LINKED_IN)
					{
						$frontend_html .= sprintf('<a href="%s%s" class="%s%s" %s>%s</a>',
													$http,
													$this->getLinkedInProfile($account),
													$id,
													$link_class,
													$target,
													$instance[$id.'_title']);
					}
					elseif($id != self::RSS)
					{
						$frontend_html .= sprintf('<a href="%s%s%s" class="%s%s" %s>%s</a>',
													$http,
													$social_link_list[$id]['link'],
													$account,
													$id,
													$link_class,
													$target,
													$instance[$id.'_title']);
					}
					else
					{
						//Preventing the repetition http://
						if(preg_match('/^http:\/\//', $account))
						{
							$http = '';
						}
						
						$frontend_html .= sprintf('<a href="%s%s/feed" class="%s%s" %s>%s</a>',
													$http,
													$account,
													$id,
													$link_class,
													$target,
													$instance[$id.'_title']);
						
					}
					
					$frontend_html .= '</li>';
				}
			}
		}
		$frontend_html .= '</ul>';
		$frontend_html .= $args['after_widget'];
		
		echo $frontend_html;
	}
	
	public function form($instance)
	{
		$instance	 = wp_parse_args((array) $instance, $this->getDefaultFieldValues()); ?>
		<p>
			<label for="<?php echo $this->get_field_id(self::HIDE); ?>"><?php _e('Hide icon:', 'churchope'); ?>
				<input id="<?php echo $this->get_field_id(self::HIDE); ?>"
					   name="<?php echo $this->get_field_name(self::HIDE); ?>"
					   type="checkbox" <?php echo esc_attr(isset($instance[self::HIDE]) && $instance[self::HIDE]) ? 'checked="checked"' : ''; ?> />
			</label>
		</p
		
		<p>
			<label for="<?php echo $this->get_field_id(self::TARGET); ?>"><?php _e('Open link in a new tab:', 'churchope'); ?>
				<input id="<?php echo $this->get_field_id(self::TARGET); ?>"
					   name="<?php echo $this->get_field_name(self::TARGET); ?>"
					   type="checkbox" <?php echo esc_attr(isset($instance[self::TARGET]) && $instance[self::TARGET]) ? 'checked="checked"' : ''; ?> />
			</label>
		</p>
		
		<?php
		foreach($this->getFields() as $field_id => $details):?>
			<?php if($field_id != self::TITLE):?>
				<p style='clear: both; margin-bottom: 15px;overflow:hidden;'>
						<label for="<?php echo $this->get_field_id($field_id.'_title'); ?>" style="width: 190px; float: left; margin-right: 8px;"><?php _e('Title', 'churchope'); ?>
							<input class="widefat" id="<?php echo $this->get_field_id($field_id.'_title'); ?>" name="<?php echo $this->get_field_name($field_id.'_title'); ?>" type="text" value="<?php echo esc_attr($instance[$field_id.'_title']); ?>" />
						</label>
					<label for="<?php echo $this->get_field_id($field_id); ?>" style="width: 190px; float: left; margin-right: 8px;"><?php echo $details['link']; ?>
						<input class="widefat" id="<?php echo $this->get_field_id($field_id); ?>" name="<?php echo $this->get_field_name($field_id); ?>" type="text" value="<?php echo esc_attr($instance[$field_id]); ?>" />
					</label>
				</p>
			<?php else:?>
				<p>
					<label for="<?php echo $this->get_field_id($field_id); ?>"><?php echo $details['link']; ?>
						<input class="widefat" id="<?php echo $this->get_field_id($field_id); ?>" name="<?php echo $this->get_field_name($field_id); ?>" type="text" value="<?php echo esc_attr($instance[$field_id]); ?>" />
					</label>
				</p>
			<?php endif;?>
		<?php endforeach; ?>
		<?php
	}

	public function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance[self::HIDE] = strip_tags( $new_instance[self::HIDE] );
		$instance[self::TARGET] = strip_tags( $new_instance[self::TARGET] );
		
		foreach($this->getFields() as $field_id => $title)
		{
//			if(isset($new_instance[$field_id]))
			{
				$instance[$field_id] = strip_tags( trim($new_instance[$field_id] ));
				if(!in_array($field_id, array(self::TITLE, self::HIDE, self::TARGET)))
					$instance[$field_id.'_title'] = strip_tags( trim($new_instance[$field_id.'_title'] ));
			}
		}
		return $instance;
	}
	
	
	private function getFields()
	{
		$fields = array (			
                        self::TITLE		=> array ('link'	=> 'Widget Title'),
                        self::RSS		=> array ('title'	=> 'RSS',           'link'	=> get_site_url().'/feed/'),
                        self::FACEBOOK		=> array ('title'	=> 'Facebook',      'link'	=> 'Facebook.com/'),
                        self::TWITTER		=> array ('title'	=> 'Twitter',       'link'	=> 'twitter.com/'),			
                        self::DRIBBLE		=> array ('title'	=> 'Dribbble',      'link'	=> 'dribbble.com/'),
                        self::FLIKER		=> array ('title'	=> 'Flicker',       'link'	=> 'flickr.com/photos/'),
                        self::VIMEO		=> array ('title'	=> 'Vimeo',         'link'	=> 'Vimeo.com/'),
                        self::EMAIL		=> array ('title'	=> 'Email',         'link'	=> 'Mailto:'),
                        self::YOUTUBE		=> array ('title'	=> 'YouTube',       'link'	=> 'youtube.com/'),
                        self::PINTEREST		=> array ('title'	=> 'Pinterest',     'link'	=> 'pinterest.com/'),
                        self::GOOGLE_PLUS	=> array ('title'	=> 'Google Plus',   'link'	=> 'Plus.google.com/'),		
                        self::LINKED_IN		=> array ('title'	=> 'LinkedIn',      'link'	=> 'linkedin.com'),
                            
                        self::PICASA            => array ('title'       => 'Picasa',        'link'      => 'picasa.google.com/'),
                        self::DIGG		=> array ('title'       => 'Digg',          'link'      => 'digg.com/'),
                        self::PLURK             => array ('title'       => 'Plurk',         'link'      => 'plurk.com/'),
                        self::TRIPADVISOR	=> array ('title'       => 'Tripadvisor',   'link'      => 'tripadvisor.com/'),
                        self::YAHOO		=> array ('title'       => 'Yahoo',         'link'      => 'yahoo.com/'),
                        self::DELICIOUS		=> array ('title'       => 'Delicious',     'link'      => 'delicious.com/'),
                        self::DEVIANART		=> array ('title'       => 'Devianart',     'link'      => 'deviantart.com/'),
                        self::TUMBLR		=> array ('title'       => 'Tumblr',        'link'      => 'tumblr.com/'),
                        self::SKYPE		=> array ('title'       => 'Skype',         'link'      => 'skype.com/'),
                        self::APPLE		=> array ('title'       => 'Apple',         'link'      => 'apple.com/'),
                        self::AIM		=> array ('title'       => 'Aim',           'link'      => 'aim.com/'),
                        self::PAYPAL		=> array ('title'       => 'Paypal',        'link'      => 'paypal.com/'),
                        self::BLOGGER		=> array ('title'       => 'Blogger',       'link'      => 'blogger.com/'),
                        self::BEHANCE		=> array ('title'       => 'Behance',       'link'      => 'behance.net/'),
                        self::MYSPACE		=> array ('title'       => 'Myspace',       'link'      => 'myspace.com/'),
                        self::STUMBLE		=> array ('title'       => 'Stumble',       'link'      => 'stumbleupon.com/'),
                        self::FORRST		=> array ('title'       => 'Forrst',        'link'      => 'forrst.com/'),
                        self::IMDB		=> array ('title'       => 'Imdb',          'link'      => 'imdb.com/'),
                        self::INSTAGRAM		=> array ('title'       => 'Instagram',     'link'      => 'instagram.com/'),
		);
		return $fields;
	}
	
	private function getDefaultFieldValues()
	{
		$list = array (
			self::TITLE			=> 'Follow us',
			self::HIDE			=> '',
			self::TARGET			=> '',
                        
                        self::RSS			=> get_site_url().'/feed',
			self::RSS_TITLE			=> 'RSS',
                        
                        self::FACEBOOK			=> 'themoholics',
			self::FACEBOOK_TITLE            => 'Facebook',
                    
                        self::TWITTER			=> 'themoholics',
			self::TWITTER_TITLE		=> 'Twitter',
                        
                        self::DRIBBLE			=> 'themoholics',
			self::DRIBBLE_TITLE		=> 'Dribbble',
			
                        self::FLIKER			=> 'themoholics',
			self::FLIKER_TITLE		=> 'Flicker',
                    
			self::VIMEO			=> 'themoholics',
			self::VIMEO_TITLE		=> 'Vimeo',
                    
                        self::EMAIL			=> 'themoholics',
			self::EMAIL_TITLE		=> 'Email',
                    
                        self::YOUTUBE			=> 'themoholics',
			self::YOUTUBE_TITLE		=> 'YouTube',
                    
                        self::PINTEREST			=> 'themoholics',
			self::PINTEREST_TITLE           => 'Pinterest',
                    
			self::GOOGLE_PLUS		=> 'themoholics',
			self::GOOGLE_PLUS_TITLE         => 'Google Plus',                 
			                    
			self::LINKED_IN			=> 'themoholics',
			self::LINKED_IN_TITLE           => 'LinkedIn',
			                                              
                            //////////////////////////////////////
                    
                        self::PICASA                    => 'themoholics',
                        self::PICASA_TITLE              => 'Picasa',

                        self::DIGG			=> 'themoholics',
                        self::DIGG_TITLE		=> 'Digg',

                        self::PLURK			=> 'themoholics',
                        self::PLURK_TITLE		=> 'Plurk',

                        self::TRIPADVISOR		=> 'themoholics',
                        self::TRIPADVISOR_TITLE		=> 'Tripadvisor',

                        self::YAHOO			=> 'themoholics',
                        self::YAHOO_TITLE		=> 'Yahoo',

                        self::DELICIOUS			=> 'themoholics',
                        self::DELICIOUS_TITLE		=> 'Delicious',

                        self::DEVIANART			=> 'themoholics',
                        self::DEVIANART_TITLE		=> 'Devianart',

                        self::TUMBLR			=> 'themoholics',
                        self::TUMBLR_TITLE		=> 'Tumblr',

                        self::SKYPE			=> 'themoholics',
                        self::skype_TITLE		=> 'Skype',

                        self::APPLE			=> 'themoholics',
                        self::APPLE_TITLE		=> 'Apple',

                        self::AIM			=> 'themoholics',
                        self::AIM_TITLE                 => 'Aim',

                        self::PAYPAL			=> 'themoholics',
                        self::PAYPAL_TITLE		=> 'Paypal',

                        self::BLOGGER			=> 'themoholics',
                        self::BLOGGER_TITLE		=> 'Blogger',

                        self::BEHANCE			=> 'themoholics',
                        self::BEHANCE_TITLE		=> 'Behance',

                        self::MYSPACE			=> 'themoholics',
                        self::MYSPACE_TITLE		=> 'Myspace',

                        self::STUMBLE			=> 'themoholics',
                        self::STUMBLE_TITLE		=> 'Stumble',

                        self::FORRST			=> 'themoholics',
                        self::FORRST_TITLE		=> 'Forrst',

                        self::IMDB			=> 'themoholics',
                        self::IMDB_TITLE		=> 'Imdb',

                        self::INSTAGRAM			=> 'themoholics',
                        self::INSTAGRAM_TITLE		=> 'Instagram',
		);
		
		return $list;
	}
	
	/**
	 * 
	 * @param string $user_data user data saved like Linked in account
	 * @return string
	 */
	private function getLinkedInProfile($user_data)
	{
		$default_fields = $this->getFields();
		$full_link = '';
		
		if(isset($default_fields[self::LINKED_IN]['link']))
		{
			$linked_in = $default_fields[self::LINKED_IN]['link'];
			if(preg_match('/^\//', $user_data)) // first slash
			{
				$full_link = $linked_in . $user_data;
			}
			else
			{
				$full_link = $linked_in . '/pub/'.$user_data;
			}
		}
		return $full_link;
	}
}

?>