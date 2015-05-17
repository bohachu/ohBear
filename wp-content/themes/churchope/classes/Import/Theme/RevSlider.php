<?php

if (!class_exists('RevSlider'))
{
	/**
	 * RevSlider classes
	 */
	$template_directory = get_template_directory();
	
	require_once $template_directory . '/lib/revslider/revslider.php';

}

/**
 * Class for adding import from file functinaliti to Revolution slider importer
 */
final class Import_Theme_RevSlider extends RevSlider
{
	/**
	 * slider params array key
	 */
	const SLIDES ='slides';

	/**
	 * slider params array key
	 */
	const PARAMS = 'params';

	/**
	 * Path inside theme with dummy images
	 */
	const DUMMY_IMG_PATH = '/backend/dummy/images/';

	/**
	 * Full path to import file
	 * @var string
	 */
	private $import_file_path = '';

	private $dummy_img_url = '';

	public function __construct()
	{
		parent::__construct();
		$this->setDummyImgDir();
	}

	/**
	 * Method for create slider and update it with import data
	 */
	public function import()
	{
		$alias = $this->getBaseName();
		if( ! $this->isAliasExistsInDB($alias))
		{
			/**
			 * create slide with default setting if not exist
			 */
			$defaultOptions = $this->getDefaultSliderSettings($alias);
			
			UniteBaseAdminClassRev::requireSettings("slider_settings");
			$settingsMain = UniteBaseAdminClassRev::getSettings("slider_main");
			$settingsParams = UniteBaseAdminClassRev::getSettings("slider_params");
			
			$slideID = $this->createSliderFromOptions($defaultOptions,$settingsMain,$settingsParams);
		}
		else
		{
			/**
			 * init slider by alias if exist
			 */
			$this->initByAlias($alias);
			$slideID = $this->getID();
		}
		
		if($slideID){
			return $this->importSlideFromFile($slideID, $this->getImportFilePath());
		}
		return false;
	}

	/**
	 * Import slide by file content data
	 * @param int $sliderID slider id
	 * @param string $filePath pabsolute path to file with data
	 * @return array
	 */
	public function importSlideFromFile($sliderID, $filePath)
	{
		try
		{
			$this->initByID($sliderID);

			//get content array
			$content = @file_get_contents($filePath);
			$arrSlider = @unserialize($content);

			if (empty($arrSlider))
				UniteFunctionsRev::throwError("Wrong export slider file format!");

			//update slider params

			$sliderParams				= $arrSlider[self::PARAMS];
			$storedParams				= $this->getParams();
			$sliderParams["title"]		= $storedParams["title"];
			$sliderParams["alias"]		= $storedParams["alias"];
			$sliderParams["shortcode"]	= $storedParams["shortcode"];
			if (isset($sliderParams["background_image"]))
				$sliderParams["background_image"] = UniteFunctionsWPRev::getImageUrlFromPath($sliderParams["background_image"]);

			$json_params = json_encode($sliderParams);
			$arrUpdate = array("params" => $json_params);
			$this->db->update(GlobalsRevSlider::$table_sliders, $arrUpdate, array("id" => $sliderID));
			//-------- Slides Handle -----------

			//create all slides
			$arrSlides = $arrSlider[self::SLIDES];
			$a = array();
			foreach ($arrSlides as $slide)
			{

				$params = $slide["params"];
				$layers = $slide["layers"];

				//convert params images:
				if (isset($params["image"]))
				{
					$a[] = $params["image"];
					$params["image"] = $this->sliderDummyImage($params["image"]);
//					$params["image"] = UniteFunctionsWPRev::getImageUrlFromPath($params["image"]);
				}

				//convert layers images:
				foreach ($layers as $key => $layer)
				{
					if (isset($layer["image_url"]))
					{
						$a[] = $layer["image_url"];
						$layer["image_url"] = $this->sliderDummyImage($layer["image_url"]);
//						$layer["image_url"] = UniteFunctionsWPRev::getImageUrlFromPath($layer["image_url"]);
						$layers[$key] = $layer;
					}
				}
				//create new slide
				$arrCreate = array();
				$arrCreate["slider_id"] = $sliderID;
				$arrCreate["slide_order"] = $slide["slide_order"];
				$arrCreate["layers"] = json_encode($layers);
				$arrCreate["params"] = json_encode($params);
				$this->db->insert(GlobalsRevSlider::$table_slides, $arrCreate);
			}
		}
		catch (Exception $e)
		{
			$errorMessage = $e->getMessage();
			return(array("success" => false, "error" => $errorMessage, "sliderID" => $sliderID));
		}

		return(array("success" => true, "sliderID" => $sliderID));
	}

	private function updateUploadsDir($arrSlider) {

		if(is_array($arrSlider))
		{
			var_dump(array_keys($arrSlider));
			die();
//			foreach ($arrSlider as )
		}

		return $arrSlider;
		
	}

	/**
	 * Check if exist slider with current alias
	 * @param string $alias
	 * @return boolean true if exist
	 */
	public function isAliasExistsInDB($alias){
		$alias = $this->db->escape($alias);

		$where = "alias='$alias'";
		if(!empty($this->id))
			$where .= " and id != '{$this->id}'";

		$response = $this->db->fetch(GlobalsRevSlider::$table_sliders,$where);
		return(!empty($response));

	}

	/**
	 * Get default theme slider settings for create new
	 * @param string $slug slider slug name
	 * @return array	
	 */
	private function getDefaultSliderSettings($slug) {
		return array (
				'params' =>
						array (
						),
				'main' =>
						array (
						  'title' => $slug,
						  'alias' => $slug,
						  'shortcode' => "[rev_slider $slug]",
						),
			'template' =>
						array (
						  'template' => 'false',						 
						),			
		);
	}


	/**
	 * Return file base name, and slider slug
	 */
	private function getBaseName() {
		return pathinfo($this->getImportFilePath(), PATHINFO_FILENAME);;
	}

	public function setImportFilePath($path) {
		$this->import_file_path = $path;
		return $this;
	}

	private function getImportFilePath() {
		return $this->import_file_path;
	}

	private function sliderDummyImage($url) {
		if($url)
		{
			if($fileName = pathinfo($url,  PATHINFO_BASENAME))
			{
				return $this->getDummyImgDir() . $fileName;
			}
		}
		return $url;
	}

	private function setDummyImgDir() {
		$this->dummy_img_url = get_template_directory_uri() . self::DUMMY_IMG_PATH;
	}

	private function getDummyImgDir() {
		return $this->dummy_img_url;
	}
}
?>