<?php
/**
 * 'Update' admin menu page
 */
class Admin_Theme_Item_Update extends Admin_Theme_Menu_Item
{
	public function __construct($parent_slug = '')
	{
		
		$this->setPageTitle(__('Theme updater','churchope'));
		$this->setMenuTitle(__('Theme Updater','churchope'));
		$this->setCapability('administrator');
		$this->setMenuSlug(SHORTNAME.'_update');
		parent::__construct($parent_slug);
		
		$this->init();
	}

	public function init()
	{
		$option = null;
		$option = new Admin_Theme_Element_Pagetitle();
		$option->setName(__('User Account Information','churchope'));
		$this->addOption($option);
		
		$option = new Admin_Theme_Element_Info();
		$option->setName(__('Don\'t know where to get your API key? Please, visit this link: <a href="http://www.themeforest.net/help/api" target="_blank">Get API Key</a>','churchope'));
		$this->addOption($option);
		
		$option = null;
		
		$option = new Admin_Theme_Element_Text();
		$option->setName(__('Marketplace Username','churchope'))
				->setDescription(__('Please provide Username for theme update','churchope'))
				->setId(SHORTNAME."_envato_nick")
				->setStd('');
		$this->addOption($option);
		$option = null;
		
		
		
		
		$option = new Admin_Theme_Element_Text();
		$option->setName(__('Secret API Key','churchope'))
				->setDescription(__('Please provide API Key for theme update','churchope'))
				->setId(SHORTNAME."_envato_api")
				->setStd('');
		$this->addOption($option);
		$option = null;
		
		
		$option = new Admin_Theme_Element_Checkbox();
		$option->setDescription ('Check for skipping theme backup before update')
				->setName ('Skip backup theme before update')
				->setId (SHORTNAME."_envato_skip_backup");
		$this->addOption($option);
		$option = null;
	}
}
?>