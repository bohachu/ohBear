<?php

class Import_Theme_Revolution implements Import_Theme_Item {

	/**
	 * Path in theme with revolution slider import/export files 
	 */
	const REVSLIDER_IMPORT_DIR = '/backend/dummy/revslides/';

	/**
	 * RevSlider import file extension
	 */
	const IMPORT_FILENAME_EXTENSION = 'txt';
	
	public function import()
	{
		$slidesImportFileList = $this->getImportFilesList($this->getRevslidesImportDir());
		if($slidesImportFileList)
		{
			foreach ($slidesImportFileList as $importFilePath)
			{
				$slide = new Import_Theme_RevSlider();
				$slide->setImportFilePath($importFilePath);
				$slide->import();
			}
		}
	}

	/**
	 * Get Absolute path to dir with theme revslider import files
	 * @return string
	 */
	private function getRevslidesImportDir() {
		return get_template_directory() . self::REVSLIDER_IMPORT_DIR;
	}

	/**
	 * Get list of revslider import files in dir
	 * @param string $path path to dir
	 * @return array with file path
	 */
	private function getImportFilesList($path) {
		$dir		= scandir($path);
		$arrFiles	= array();

		foreach($dir as $file){
			if($file != '.'
					&& $file != '..'
					&& pathinfo($file, PATHINFO_EXTENSION) == self::IMPORT_FILENAME_EXTENSION )
			{
				$filepath = $path . DIRECTORY_SEPARATOR . $file;
				if(is_file($filepath))
				{
					$arrFiles[] = $filepath;
				}
			}
		}
		return($arrFiles);
	}

	
}
?>
