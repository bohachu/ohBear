<?php

/**
 * 'General' admin menu page
 */
class Admin_Theme_Item_General extends Admin_Theme_Menu_Item
{

	public function __construct($parent_slug = '')
	{

		$this->setPageTitle(__('General', 'churchope'));
		$this->setMenuTitle(__('General', 'churchope'));
		$this->setCapability('administrator');
		$this->setMenuSlug(SHORTNAME . '_general');
		parent::__construct($parent_slug);
		$this->init();
	}

	public function init()
	{

		$option = new Admin_Theme_Element_Pagetitle();
		$option->setName(__('General Settings', 'churchope'));
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Checkbox();
		$option->setDescription(__('Check to disable Google fonts', 'churchope'))
				->setName(__('Disable Google fonts', 'churchope'))
				->setCustomized()  // Show this element on WP Customize Admin menu
				->setId(SHORTNAME . "_gfontdisable");
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Select_Gfont();
		$option->setName(__('Choose a Font', 'churchope'))
				->setDescription(__('Choose a Font for titles, etc.', 'churchope'))
				->setId(SHORTNAME . "_gfont")
				->setStd('Open Sans')
				->setCustomized();
		$this->addOption($option);
		$option = null;



		$option = new Admin_Theme_Element_Separator();
		$this->addOption($option);
		$option = null;


		$option = new Admin_Theme_Element_File();
		$option->setName(__('Favicon URL', 'churchope'))
				->setDescription(__('Type full URL like a <em>http://url.com/favicon.ico</em>', 'churchope'))
				->setId(SHORTNAME . "_favicon")
				->setStd(get_stylesheet_directory_uri() . '/images/favicon.ico');
		$this->addOption($option);
		$option = null;

		
		$option = new Admin_Theme_Element_Separator();
		$this->addOption($option);
		$option = null;
		
		$option = new Admin_Theme_Element_Checkbox();
		$option->setName(__('Disable responsive', 'churchope'))
				->setDescription(__('Check to disable responsive support', 'churchope'))
				->setId(SHORTNAME . "_responsive")
				->setStd('');
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Separator();
		$this->addOption($option);
		$option = null;


		$option = new Admin_Theme_Element_Checkbox();
		$option->setName(__('Use Boxed Layout', 'churchope'))
				->setDescription(__('Check to use boxed version', 'churchope'))
				->setId(SHORTNAME . "_boxed")
				->setStd('');
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Colorchooser();
		$option->setName(__('Site background color for boxed version', 'churchope'))
				->setDescription(__('Please select your custom color for site background', 'churchope'))
				->setId(SHORTNAME . "_boxedbackground")
				->setStd("#F1F1F1");
		$this->addOption($option);


		$option = new Admin_Theme_Element_File();
		$option->setName(__('Use custom pattern image for  boxed version', 'churchope'))
				->setDescription(__('You can upload custom pattern image.', 'churchope'))
				->setId(SHORTNAME . "_boxedpattern")
				->setStd('');
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Select();
		$option->setName(__('Custom pattern repeat', 'churchope'))
				->setDescription(__('Custom pattern repeat settings', 'churchope'))
				->setId(SHORTNAME . "_boxedpattern_repeat")
				->setStd('repeat')
				->setOptions(array('repeat', 'no-repeat', 'repeat-x', 'repeat-y'));
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Select();
		$option->setName(__('Custom pattern horizontal position', 'churchope'))
				->setDescription(__('Custom pattern horizontal position', 'churchope'))
				->setId(SHORTNAME . "_boxedpattern_x")
				->setStd('0')
				->setOptions(array('0', '50%', '100%'));
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Select();
		$option->setName(__('Custom pattern vertical position', 'churchope'))
				->setDescription(__('Custom pattern vertical position', 'churchope'))
				->setId(SHORTNAME . "_boxedpattern_y")
				->setStd('0')
				->setOptions(array('0', '50%', '100%'));
		$this->addOption($option);
		$option = null;


		$option = new Admin_Theme_Element_Separator();
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Colorchooser();
		$option->setName(__('Content text color', 'churchope'))
				->setDescription(__('Please select your custom color for all elements on light background.', 'churchope'))
				->setId(SHORTNAME . "_textcolor")
				->setStd("#797979");
		$this->addOption($option);

		$option = new Admin_Theme_Element_Colorchooser();
		$option->setName(__('Content headings color', 'churchope'))
				->setDescription(__('Please select your custom color for all elements on dark background (footer area).', 'churchope'))
				->setId(SHORTNAME . "_headingscolor")
				->setStd('#545454');
		$this->addOption($option);

		$option = new Admin_Theme_Element_Colorchooser();
		$option->setName(__('Content links color', 'churchope'))
				->setDescription(__('Please select your custom color for all elements on dark background (footer area).', 'churchope'))
				->setId(SHORTNAME . "_linkscolor")
				->setStd('#c62b02');
		$this->addOption($option);

		if (isset($_GET['preview']))
		{
			$option = new Admin_Theme_Element_Checkbox();
			$option->setName(__('Show preview switcher', 'churchope'))
					->setDescription(__('Check to show preview color switcher', 'churchope'))
					->setId(SHORTNAME . "_preview")
					->setStd('');
			$this->addOption($option);
			$option = null;
		}
	}

}

?>