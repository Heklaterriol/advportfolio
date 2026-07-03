<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSMVCModelBaseDatabaseModel;
use JoomlaCMSFactory;
use JoomlaCMSFilesystemFile;
use JoomlaCMSFilesystemFolder;
use JoomlaCMSImageImage as JImage;

class AdvPortfolioModelImageHandler extends BaseDatabaseModel
{
	public function uploadImage($file)
	{
		$app = Factory::getApplication();
		$config = $app->getConfig();
		if (!$config->get('upload_enable')) { $this->setError(Text::_('COM_ADVPORTFOLIO_UPLOAD_DISABLED')); return false; }
		$maxSize = $config->get('upload_maxsize', 0) * 1024 * 1024;
		if ($file['size'] > $maxSize) { $this->setError(Text::_('COM_ADVPORTFOLIO_FILE_TOO_LARGE')); return false; }
		$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
		if (!in_array($file['type'], $allowedTypes)) { $this->setError(Text::_('COM_ADVPORTFOLIO_INVALID_FILE_TYPE')); return false; }
		$uploadDir = $config->get('upload_path', 'images');
		$uploadPath = JPATH_ROOT . '/' . $uploadDir . '/advportfolio';
		if (!Folder::exists($uploadPath)) { Folder::create($uploadPath, 0755); }
		$filename = File::makeSafe($file['name']);
		$ext = File::getExt($filename);
		$basename = File::stripExt($filename);
		$newFilename = $basename . '_' . uniqid() . '.' . $ext;
		$destination = $uploadPath . '/' . $newFilename;
		if (!File::upload($file['tmp_name'], $destination)) { $this->setError(Text::_('COM_ADVPORTFOLIO_UPLOAD_FAILED')); return false; }
		$thumbnailPath = $uploadPath . '/thumbnails/' . $newFilename;
		$this->createThumbnail($destination, $thumbnailPath, 200, 200);
		return [
			'filename' => $newFilename,
			'path' => 'images/advportfolio/' . $newFilename,
			'thumbnail' => 'images/advportfolio/thumbnails/' . $newFilename,
			'size' => $file['size'],
			'type' => $file['type']
		];
	}

	protected function createThumbnail($sourcePath, $destPath, $width, $height)
	{
		try {
			$thumbDir = dirname($destPath);
			if (!Folder::exists($thumbDir)) { Folder::create($thumbDir, 0755); }
			$image = new JImage($sourcePath);
			if (!$image->resize($width, $height, true, JImage::SCALE_INSIDE)) { return false; }
			return $image->toFile($destPath, JImage::QUALITY_HIGH);
		} catch (Exception $e) { $this->setError($e->getMessage()); return false; }
	}

	public function deleteImage($filename)
	{
		$config = Factory::getApplication()->getConfig();
		$uploadDir = $config->get('upload_path', 'images');
		$uploadPath = JPATH_ROOT . '/' . $uploadDir . '/advportfolio';
		$mainPath = $uploadPath . '/' . $filename;
		if (File::exists($mainPath)) { File::delete($mainPath); }
		$thumbPath = $uploadPath . '/thumbnails/' . $filename;
		if (File::exists($thumbPath)) { File::delete($thumbPath); }
		return true;
	}
}