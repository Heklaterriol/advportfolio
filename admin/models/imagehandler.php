<?php
/**
 * @package     AdvPortfolio
 * @subpackage  Admin.Models
 * @copyright   Copyright (C) 2026 Hekla Terriol. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Filesystem\File;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Image\Image;

class AdvPortfolioModelImageHandler extends BaseDatabaseModel
{
	public function uploadImage($file)
	{
		$app = Factory::getApplication();
		$config = $app->getConfig();
		if (!$config->get('upload_enable')) {
			$this->setError('COM_ADVPORTFOLIO_UPLOAD_DISABLED');
			return false;
		}
		$maxSize = $config->get('upload_maxsize', 0) * 1024 * 1024;
		if ($file['size'] > $maxSize) {
			$this->setError('COM_ADVPORTFOLIO_FILE_TOO_LARGE');
			return false;
		}
		$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
		if (!in_array($file['type'], $allowedTypes)) {
			$this->setError('COM_ADVPORTFOLIO_INVALID_FILE_TYPE');
			return false;
		}
		$uploadDir = $config->get('upload_path', 'images');
		$uploadPath = JPATH_ROOT . '/' . $uploadDir . '/advportfolio';
		if (!Folder::exists($uploadPath)) {
			Folder::create($uploadPath, 0755);
		}
		$filename = File::makeSafe($file['name']);
		$ext = File::getExt($filename);
		$basename = File::stripExt($filename);
		$newFilename = $basename . '_' . uniqid() . '.' . $ext;
		$destination = $uploadPath . '/' . $newFilename;
		if (!File::upload($file['tmp_name'], $destination)) {
			$this->setError('COM_ADVPORTFOLIO_UPLOAD_FAILED');
			return false;
		}
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
			if (!Folder::exists($thumbDir)) {
				Folder::create($thumbDir, 0755);
			}
			$image = new Image($sourcePath);
			if (!$image->resize($width, $height, true, Image::SCALE_INSIDE)) {
				return false;
			}
			return $image->toFile($destPath, Image::QUALITY_HIGH);
		} catch (Exception $e) {
			$this->setError($e->getMessage());
			return false;
		}
	}

	public function deleteImage($filename)
	{
		$config = Factory::getApplication()->getConfig();
		$uploadDir = $config->get('upload_path', 'images');
		$uploadPath = JPATH_ROOT . '/' . $uploadDir . '/advportfolio';
		$mainPath = $uploadPath . '/' . $filename;
		if (File::exists($mainPath)) {
			File::delete($mainPath);
		}
		$thumbPath = $uploadPath . '/thumbnails/' . $filename;
		if (File::exists($thumbPath)) {
			File::delete($thumbPath);
		}
		return true;
	}
}