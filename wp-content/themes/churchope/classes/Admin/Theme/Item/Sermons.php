<?php
/**
 * 'Header' admin menu page
 */
class Admin_Theme_Item_Sermons extends Admin_Theme_Menu_Item
{
	public function __construct($parent_slug = '')
	{
		
		$this->setPageTitle(__('Sermons','churchope'));
		$this->setMenuTitle(__('Sermons','churchope'));
		$this->setCapability('administrator');
		$this->setMenuSlug(SHORTNAME . '_sermons');
		parent::__construct($parent_slug);
		
		$this->init();
	}

	public function init()
	{
		$option = new Admin_Theme_Element_Pagetitle();
		$option->setName(__('Sermons Settings','churchope'));
		$this->addOption($option);
		$option = null;
		
		$option = new Admin_Theme_Element_Select();
		$option->setName(__('Sidebar position for sermons listing','churchope'))
				->setDescription(__('Choose a sidebar position for sermons listing','churchope'))
				->setId(SHORTNAME."_sermons_listing_layout")
				->setStd('none')
				->setOptions(array("none", "left", "right"));
		$this->addOption($option);
		$option = null;
		
		$option = new Admin_Theme_Element_SelectSidebar();
		$option->setName(__('Sidebar for sermons listing','churchope'))
				->setDescription(__('Choose a sidebar for sermons listing','churchope'))
				->setId(SHORTNAME."_sermons_listing_sidebar");					
		$this->addOption($option);
		$option = null;
		
				
		$option = new Admin_Theme_Element_Separator();
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Select();
		$option->setName(__('Sidebar position for single sermons','churchope'))
				->setDescription(__('Choose a sidebar position for single sermon','churchope'))
				->setId(SHORTNAME."_sermons_layout")
				->setStd('none')
				->setOptions(array("none", "left", "right"));
		$this->addOption($option);
		$option = null;
		
		$option = new Admin_Theme_Element_SelectSidebar();
		$option->setName(__('Sidebar for single sermons','churchope'))
				->setDescription(__('Choose a sidebar for single sermon','churchope'))
				->setId(SHORTNAME."_sermons_sidebar");					
		$this->addOption($option);
		$option = null;		
		
		$option = new Admin_Theme_Element_Separator();
		$this->addOption($option);
		$option = null;
		
		
		$custom_page = new Custom_Posts_Type_Sermon();
		$option = new Admin_Theme_Element_Text();
		$option->setName(__('Sermon Post slug','churchope'))
				->setDescription(__('Some description','churchope'))
				->setId($custom_page->getPostSlugOptionName())
				->setStd($custom_page->getDefaultPostSlug());
		$this->addOption($option);
		$option = null;
		
		$option = new Admin_Theme_Element_Text();
		$option->setName(__('Sermon Tax slug','churchope'))
				->setDescription(__('Some description','churchope'))
				->setId($custom_page->getTaxSlugOptionName())
				->setStd($custom_page->getDefaultTaxSlug());
		$this->addOption($option);
		
		$option = new Admin_Theme_Element_Text();
		$option->setName(__('Sermon Speaker slug','churchope'))
				->setDescription(__('Some description','churchope'))
				->setId(SHORTNAME."_sermon_speaker")
				->setStd("th_sermon_speaker");
		$this->addOption($option);
	}
	
	/**
	 * Save form and set option-flag for reinit rewrite rules on init
	 */
	public function saveForm()
	{
		parent::saveForm();
		$this->setNeedReinitRulesFlag();
	}
	
	/**
	 * Reset form and set option-flag for reinit rewrite rules on init
	 */
	public function resetForm()
	{
		parent::resetForm();
		$this->setNeedReinitRulesFlag();
	}
	
	/**
	 * save to DB flag of need do flush_rewrite_rules on next init
	 */
	private function setNeedReinitRulesFlag()
	{
		update_option(SHORTNAME.'_need_flush_rewrite_rules', '1');
	}
}
?>