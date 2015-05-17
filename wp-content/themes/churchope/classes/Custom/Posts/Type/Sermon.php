<?php

class Custom_Posts_Type_Sermon extends Custom_Posts_Type_Default
{
	const POST_TYPE = 'th_sermons';
	const TAXONOMY = 'th_sermons_cat';
	const TAXONOMY_SPEAKER = 'th_sermons_speaker';
	
	protected $post_slug_option	= '_sermon';
	protected $tax_slug_option	= '_sermon_cat';
	protected $tax_slug_option_speaker	= '_sermon_speaker';

	protected $post_type_name	= self::POST_TYPE;
	
	protected $taxonomy_name = self::TAXONOMY;
	protected $taxonomy_name_speaker = self::TAXONOMY_SPEAKER;

	const DEFAULT_TAX_SLUG = 'th_sermon_cat';
	
	const DEFAULT_SPEAKER_SLUG = 'th_sermon_speaker';
	
	const DEFAULT_POST_SLUG = 'th_sermon';
	
	
	
	function __construct()
	{
		$this->setDefaultPostSlug(self::DEFAULT_POST_SLUG);
		$this->setDefaultTaxSlug(self::DEFAULT_TAX_SLUG);
		parent::__construct();
	}
	
	protected function init()
	{
		register_post_type($this->getPostTypeName(), array(
					'labels'				=> $this->getPostLabeles(),
					'public'				=> true,
					'show_ui'				=> true,
					'_builtin'				=> false,
					'capability_type'		=> 'post',
					'_edit_link'			=> 'post.php?post=%d',
					'rewrite'				=> array("slug" =>  $this->getPostSlug()), 
					'hierarchical'			=> false,
					'menu_icon'				=> get_template_directory_uri() . '/backend/img/i_sermons.png',
					'query_var'				=> true,
					'publicly_queryable'	=> true,
					'exclude_from_search'	=> false,
					'supports'				=> array('title', 'editor', 'thumbnail', 'excerpt', 'comments')
		));


		register_taxonomy($this->getTaxonomyName(),$this->getPostTypeName(),
					array(
					'hierarchical'			=> true,
					'labels'				=> $this->getTaxLabels(),
					'show_ui'				=> true,
					'query_var'				=> true,
					'rewrite'				=> array('slug' => $this->getTaxSlug()),
					'show_admin_column'		=> true,

		));
		
		register_taxonomy($this->getTaxonomyNameSpeaker(),$this->getPostTypeName(),
					array(
					'hierarchical'			=> true,
					'labels'				=> $this->getTaxSpeakerLabels(),
					'show_ui'				=> true,
					'query_var'				=> true,
					'rewrite'				=> array('slug' => $this->getTaxSpeakerSlugOptionName()),
					'show_admin_column'		=> true,
		));
		
		
		
	}
	////////////////////////////////////////////
	public function run()
	{
		//add_filter("manage_edit-{$this->getPostTypeName()}_columns", array(&$this, "th_post_type_columns"));
		add_filter('wp_insert_post_data', array(&$this, 'default_comments_off'));
		//add_action("manage_posts_custom_column", array(&$this, "th_post_type_custom_columns"));
		//add_action('restrict_manage_posts', array(&$this, 'th_post_type_restrict_manage_posts'));
		add_action('request', array(&$this, 'th_request'));
		add_action('init', array(&$this, "thInit"));
		//add_action( 'init', array(&$this,'sermon_rewrites_init' ));
		//add_filter( 'query_vars', array(&$this, 'sermon_query_vars'));
		add_action('template_redirect', array(&$this,'template_redirect_file'));

		
		$this->addCustomMetaBox( new Custom_MetaBox_Item_Sermon($this->getTaxonomyName()) );

		$this->addCustomMetaBox( new Custom_MetaBox_Item_Speaker($this->getTaxonomyNameSpeaker()) );
		
	}

	/**
	 * The name of the taxonomy.
	 * @return string
	 */
	public function getTaxonomyNameSpeaker()
	{
		return $this->taxonomy_name_speaker;
	}
	
	
	/**
	 * Get name of option under which value saved in DB
	 * @return string
	 */
	public function getTaxSpeakerSlugOptionName()
	{
		
		$tax_slug = get_option(SHORTNAME.$this->tax_slug_option_speaker);

		if ($tax_slug == '')
		{
			$tax_slug = self::DEFAULT_SPEAKER_SLUG;
		}

		return $tax_slug;	
	}

	function sermon_rewrites_init(){
		add_rewrite_rule(
			"{$this->getTaxSlug()}/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/?$",
			'index.php?pagename=customsermonslist&sermon_year=$matches[1]&sermon_month=$matches[2]&sermon_day=$matches[3]',
			'top' );
			
		add_rewrite_rule(
			"{$this->getTaxSlug()}/([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})/page/(\d+)/?$",
			'index.php?pagename=customsermonslist&sermon_year=$matches[1]&sermon_month=$matches[2]&sermon_day=$matches[3]&page=$matches[4]',
			'top' );
	}


	function sermon_query_vars( $query_vars ){
		$query_vars[] = 'sermon_year';
		$query_vars[] = 'sermon_month';
		$query_vars[] = 'sermon_day';
//		$query_vars[] = 'sermon_pagination';
		
		return $query_vars;
	}
	
	function template_redirect_file()
	{
		if (get_query_var('pagename') == 'customsermonslist')
		{
			status_header( 200 );
			locate_template(array('page-customsermonslist.php'), true, true);
			exit();
		}
	}


	function thInit()
	{
		global $thsermon;
		$thsermon = $this;
	}

	function th_request($request)
	{
		if (is_admin()
				&& $GLOBALS['PHP_SELF'] == '/wp-admin/edit.php'
				&& isset($request['post_type'])
				&& $request['post_type'] == $this->getPostTypeName())
		{
			$th_sermons_cat = (isset($request[$this->getTaxonomyName()]) ? $request[$this->getTaxonomyName()] : NULL);
			$term = get_term($th_sermons_cat, $this->getTaxonomyName());
			$request['term'] = isset($term->slug);
		}
		return $request;
	}

	function th_post_type_restrict_manage_posts()
	{
		global $typenow;

		if ($typenow == $this->getPostTypeName())
		{
			$filters = array($this->getTaxonomyName());

			foreach ($filters as $tax_slug)
			{
				// retrieve the taxonomy object
				$tax_obj = get_taxonomy($tax_slug);
				$tax_name = $tax_obj->labels->name;
				// retrieve array of term objects per taxonomy
				$terms = get_terms($tax_slug);

				// output html for taxonomy dropdown filter
				echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
				echo "<option value=''>".__('Show All','churchope')." ". $tax_name."</option>";
				$th_slider_tax_slug = (isset($_GET[$tax_slug]) ? $_GET[$tax_slug] : NULL);
				foreach ($terms as $term)
				{
					// output each select option line, check against the last $_GET to show the current option selected
					echo '<option value=' . $term->slug, $th_slider_tax_slug == $term->slug ? ' selected="selected"' : '', '>' . $term->name . ' (' . $term->count . ')</option>';
				}
				echo "</select>";
			}
		}
	}

	function th_post_type_columns($columns)
	{

		$columns = array(
			"cb" => "<input type=\"checkbox\" />",
			"title" => __("Sermon Item Title", 'churchope'),
			"thsermons_preview" => __("Image preview", 'churchope'),
			"thsermons_categories" => __("Assign to Sermons Category(s)", 'churchope'),
			"thsermons_speakers" => __("Assign to Sermons Speaker(s)", 'churchope'),
		);

		return $columns;
	}

	function th_post_type_custom_columns($column)
	{
		global $post;
		switch ($column)
		{

			case "thsermons_preview":
				?>
				<?php if (has_post_thumbnail()) : ?>
					<a href="post.php?post=<?php echo $post->ID ?>&action=edit"><?php the_post_thumbnail('sermon_widget'); ?></a>
					<?php
				endif;
				break;

			case "thsermons_categories":
				$kgcs = get_the_terms(0, $this->getTaxonomyName());
				if (!empty($kgcs))
				{
					$kgcs_html = array();
					foreach ($kgcs as $kgc)
						array_push($kgcs_html, $kgc->name);

					echo implode($kgcs_html, ", ");
				}
				break;

			case "thsermons_speakers":
				$kgcs = get_the_terms(0, $this->getTaxonomyNameSpeaker());
				//var_dump($kgcs);
				if (!empty($kgcs))
				{
					$kgcs_html = array();
					foreach ($kgcs as $kgc)
						array_push($kgcs_html, "<a href='".get_term_link( $kgc, $this->getTaxonomyNameSpeaker())."'>".$kgc->name."</a>");

					echo implode($kgcs_html, ", ");
				}
				break;
		}
	}

	protected function getPostLabeles()
	{

		$labels = array(
			'name'				=> __('Sermons',  'churchope'),
			'all_items'			=> __('Sermon Posts',  'churchope'),
			'singular_name'		=> __('Sermon',  'churchope'),
			'add_new'			=> __('Add New',  'churchope'),
			'add_new_item'		=> __('Add New Item', 'churchope'),
			'edit_item'			=> __('Edit Item', 'churchope'),
			'new_item'			=> __('New Item', 'churchope'),
			'view_item'			=> __('View Item', 'churchope'),
			'search_items'		=> __('Search Items', 'churchope'),
			'not_found'			=> __('No items found', 'churchope'),
			'not_found_in_trash' => __('No items found in Trash', 'churchope'),
			'parent_item_colon'	=> ''
		);
		
		return $labels;
	}

	protected function getTaxLabels()
	{
		$labels = array(
			'name'					=> __('Sermon Categories', 'churchope'),
			'singular_name'			=> __('Sermon Category',  'churchope'),
			'search_items'			=> __('Search Sermons Categories', 'churchope'),
			'popular_items'			=> __('Popular Sermons Categories', 'churchope'),
			'all_items'				=> __('All Sermons Categories', 'churchope'),
			'parent_item'			=> null,
			'parent_item_colon'		=> null,
			'edit_item'				=> __('Edit Sermons Category', 'churchope'),
			'update_item'			=> __('Update Sermons Category', 'churchope'),
			'add_new_item'			=> __('Add New Sermons Category', 'churchope'),
			'new_item_name'			=> __('New Sermons Category Name', 'churchope'),
			'add_or_remove_items'	=> __('Add or remove Sermons Categories', 'churchope'),
			'choose_from_most_used' => __('Choose from the most used Sermons Categories', 'churchope'),
			'separate_items_with_commas' => __('Separate Sermons Categories with commas', 'churchope'),
		);
		return $labels;
	}

	protected function getTaxSpeakerLabels()
	{
		$labels = array(
			'name'					=> __('Sermon Speakers', 'churchope'),
			'singular_name'			=> __('Sermon Speaker',  'churchope'),
			'search_items'			=> __('Search Sermons Speaker', 'churchope'),
			'popular_items'			=> __('Popular Sermons Speakers', 'churchope'),
			'all_items'				=> __('All Sermons Speakers', 'churchope'),
			'parent_item'			=> null,
			'parent_item_colon'		=> null,
			'edit_item'				=> __('Edit Sermons Speaker', 'churchope'),
			'update_item'			=> __('Update Sermons Speaker', 'churchope'),
			'add_new_item'			=> __('Add New Sermons Speaker', 'churchope'),
			'new_item_name'			=> __('New Sermons Speaker Name', 'churchope'),
			'add_or_remove_items'	=> __('Add or remove Sermons Speakers', 'churchope'),
			'choose_from_most_used' => __('Choose from the most used Sermons Speakers', 'churchope'),
			'separate_items_with_commas' => __('Separate Sermons Speakers with commas', 'churchope'),
		);
		return $labels;
	}
}
?>