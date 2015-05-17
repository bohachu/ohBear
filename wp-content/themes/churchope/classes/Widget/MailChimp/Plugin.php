<?php

class Widget_MailChimp_Plugin {
	private $options;
	private static $instance;
	private static $mcapi;
	private static $prefix = SHORTNAME;

	
	private function __construct () {
		$this->get_options();
	}

	public static function get_instance () {
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	public function get_admin_notices () {
		global $blog_id;
		$notice = '<p>';
		$notice .= __('You\'ll need to set up the MailChimp signup widget plugin options before using it. ', 'churchope') . __('You can make your changes', 'churchope') . ' <a href="' . get_admin_url($blog_id) . 'admin.php?page='.SHORTNAME.'_mailchimp">' . __('here', 'churchope') . '.</a>';
		$notice .= '</p>';
		return $notice;
	}
	
	public function get_mcapi () {
		$api_key = $this->get_api_key();
		if (false == $api_key) {
			return false;
		} else {
			if (empty(self::$mcapi)) {
				self::$mcapi = new Widget_MailChimp_API($api_key);
			}
			return self::$mcapi;
		}
	}
	
	public function get_options () {

		$this->options['api-key'] = get_option(self::$prefix . '_mailchimp_key');
		return $this->options;
	}
	
	private function get_api_key () {
		if (is_array($this->options) && ! empty($this->options['api-key'])) {
			return $this->options['api-key'];
		} else {
			return false;
		}
	}
}
?>