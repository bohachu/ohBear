<?php

/**
 * 'Blog' admin menu page
 */
class Admin_Theme_Item_Blog extends Admin_Theme_Menu_Item
{

	public function __construct($parent_slug = '')
	{

		$this->setPageTitle(__('Blog', 'churchope'));
		$this->setMenuTitle(__('Blog', 'churchope'));
		$this->setCapability('administrator');
		$this->setMenuSlug(SHORTNAME . '_blog');
		parent::__construct($parent_slug);

		$this->init();
	}

	public function init()
	{

		$option = new Admin_Theme_Element_Pagetitle();
		$option->setName(__('Blog Settings', 'churchope'));
		$this->addOption($option);
		$option = null;



		$option = new Admin_Theme_Element_Select();
		$option->setName(__('Sidebar position for blog listing', 'churchope'))
				->setDescription(__('Choose a sidebar position for blog listing', 'churchope'))
				->setId(SHORTNAME . "_post_listing_layout")
				->setStd('none')
				->setOptions(array("none", "left", "right"));
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_SelectSidebar();
		$option->setName(__('Sidebar for blog listing', 'churchope'))
				->setDescription(__('Choose a sidebar for blog listing', 'churchope'))
				->setId(SHORTNAME . "_post_listing_sidebar");
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Separator();
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Checkbox();
		$option->setDescription(__('Hide date from post', 'churchope'))
				->setName(__('Check to Hide Post Date', 'churchope'))
				->setId(SHORTNAME . "_hidedate");
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Checkbox();
		$option->setDescription(__('Hide feature images from posts listing', 'churchope'))
				->setName(__('Check to Hide Blog Listing Post Thumbnails', 'churchope'))
				->setId(SHORTNAME . "_hidethumb");
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Checkbox();
		$option->setDescription(__('Disable excerpts', 'churchope'))
				->setName(__('Check to Hide Excerpts on Blog Listing', 'churchope'))
				->setId(SHORTNAME . "_excerpt");
		$this->addOption($option);
		$option = null;
		
		$option = new Admin_Theme_Element_Checkbox();
		$option->setDescription(__('Disable categories from single post', 'churchope'))
				->setName(__('Check to Hide Categories from Single Post', 'churchope'))
				->setId(SHORTNAME . "_disable_cats");
		$this->addOption($option);
		$option = null;
		
		$option = new Admin_Theme_Element_Checkbox();
		$option->setDescription(__('Disable tags from single post', 'churchope'))
				->setName(__('Check to Hide Tags from Single Post', 'churchope'))
				->setId(SHORTNAME . "_disable_tags");
		$this->addOption($option);
		$option = null;
	

		$option = new Admin_Theme_Element_Checkbox();
		$option->setDescription(__('Disable about author box', 'churchope'))
				->setName(__('Check to Hide About Author Box on Post Entry', 'churchope'))
				->setId(SHORTNAME . "_authorbox");
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Separator();
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Select();
		$option->setName(__('Sidebar position for single post', 'churchope'))
				->setDescription(__('Choose a sidebar position for single post', 'churchope'))
				->setId(SHORTNAME . "_post_layout")
				->setStd('none')
				->setOptions(array("none", "left", "right"));
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_SelectSidebar();
		$option->setName(__('Sidebar for single post', 'churchope'))
				->setDescription(__('Choose a sidebar for single post', 'churchope'))
				->setId(SHORTNAME . "_post_sidebar");
		$this->addOption($option);
		$option = null;

		$option = new Admin_Theme_Element_Separator();
		$this->addOption($option);
		$option = null;

		if (!get_option("page_on_front"))
		{
			$option = new Admin_Theme_Element_Select();
			$option->setName(__('Select a  slideshow type', 'churchope'))
					->setDescription(__('Select a global slideshow type ', 'churchope'))
					->setId(SHORTNAME . "_blog_slider")
					->setStd('Use global')
					->setOptions(array('Use global', 'Disable', 'jCycle', 'revSlider'));
			$this->addOption($option);
			$option = null;

			$option = new Admin_Theme_Element_Select_Slider();
			$option->setName('Select one of Revolution sliders')
					->setDescription('Select a  Revolution slider')
					->setId(SHORTNAME . "_blog_revslider");
			$this->addOption($option);
			$option = null;

			$option = new Admin_Theme_Element_Select_Taxonomy();
			$option->setName(__('Select a slideshow category', 'churchope'))
					->setDescription(__('Select a slideshow category', 'churchope'))
					->setId(SHORTNAME . "_blog_slider_cat")
					->setStd('')
					->setTaxonomy(Custom_Posts_Type_Slideshow::TAXONOMY);
			$this->addOption($option);
			$option = null;

			$option = new Admin_Theme_Element_Text();
			$option->setName(__('How many slides to display', 'churchope'))
					->setDescription(__('Set a number of how many slides you want to use at current slider', 'churchope'))
					->setId(SHORTNAME . "_blog_slider_count")
					->setStd(4);
			$this->addOption($option);
			$option = null;


			$option = new Admin_Theme_Element_Separator();
			$this->addOption($option);
			$option = null;


			$option = new Admin_Theme_Element_Text();
			$option->setName(__('Slideshow timeout', 'churchope'))
					->setDescription(__('Milliseconds between slide transitions (0 to disable auto advance)', 'churchope'))
					->setId(SHORTNAME . "_blog_slider_timeout")
					->setStd(6000);
			$this->addOption($option);
			$option = null;

			$option = new Admin_Theme_Element_Text();
			$option->setName(__('Slideshow speed', 'churchope'))
					->setDescription(__('Speed of the transition(Milliseconds)', 'churchope'))
					->setId(SHORTNAME . "_blog_slider_speed")
					->setStd(1000);
			$this->addOption($option);
			$option = null;

			$option = new Admin_Theme_Element_Checkbox();
			$option->setName(__('Next/Prev navigation', 'churchope'))
					->setDescription(__('Check to show Next/Prev navigation for slideshow', 'churchope'))
					->setId(SHORTNAME . "_blog_slider_nav")
					->setStd('');
			$this->addOption($option);
			$option = null;

			$option = new Admin_Theme_Element_Text();
			$option->setName(__('Slideshow fixed height', 'churchope'))
					->setDescription(__('Set custom fixed slideshow height. Write only number of pixels!', 'churchope'))
					->setId(SHORTNAME . "_blog_slider_height")
					->setStd('');
			$this->addOption($option);
			$option = null;

			$option = new Admin_Theme_Element_Checkbox();
			$option->setName(__('Remove top and bottom paddings from slideshow', 'churchope'))
					->setDescription(__('Check to remove top and bottom paddings from slideshow', 'churchope'))
					->setId(SHORTNAME . "_blog_slider_padding")
					->setStd('');
			$this->addOption($option);
			$option = null;


			$option = new Admin_Theme_Element_Checkbox();
			$option->setName(__('Slideshow pause on hover', 'churchope'))
					->setDescription(__('Check to enable slideshow pause on hover', 'churchope'))
					->setId(SHORTNAME . "_blog_slider_pause")
					->setStd('');
			$this->addOption($option);
			$option = null;

			$option = new Admin_Theme_Element_Checkbox();
			$option->setName(__('Disable autoplay', 'churchope'))
					->setDescription(__('Check to disable Slideshow autoplay', 'churchope'))
					->setId(SHORTNAME . "_blog_slider_autoplay")
					->setStd('');
			$this->addOption($option);
			$option = null;
			
			
			$option = new Admin_Theme_Element_Text();
				$option->setName(__('Blog page title', 'churchope'))
						->setDescription(__('Title will be shown on any blog post', 'churchope'))
						->setId(SHORTNAME."_blog_title")
						->setStd("");
				$this->addOption($option);
				$option = null;
		}
	}

}

?>