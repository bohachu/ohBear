<?php

/**
 * Class for Text Html element 
 */
class Admin_Theme_Element_Editor extends Admin_Theme_Menu_Element
{

	protected $option = array(
		'type' => Admin_Theme_Menu_Element::TYPE_TEXT,
	);

	public function render()
	{
		ob_start();
		echo $this->getElementHeader();
		wp_editor($this->getCurrentValue(), $this->getId(), array('media_buttons' => false,
			'tinymce' => array(
				'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,spellchecker,wp_fullscreen,wp_adv',
				'theme_advanced_buttons3' => $this->options,
//																		'theme_advanced_buttons3' =>  "",
			)
		));
		echo $this->getElementFooter();

		$html = ob_get_clean();
		return $html;
	}

	private function getCurrentValue()
	{
		return get_option($this->getId());
	}

}

?>
