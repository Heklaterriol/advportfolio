<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Client\ClientHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Filter\InputFilter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Session\Session;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;
use Joomla\Filesystem\Path;

/**
 * Image Handler Controller.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.AdvPortfolio
 */
class ImagehandlerController extends BaseController
{
	/**
	 * Method to upload an image.
	 */
	public function upload()
	{
		// check for request forgeries
		Session::checkToken() or jexit('Invalid Token');

		// initialize variables
		$user		= Factory::getApplication()->getIdentity();
		$app		= Factory::getApplication();

		// Get some data from the request
		$file		= $app->getInput()->files->get('image', [], 'array');
		$image_id	= $this->input->get('image_id');
		$folder		= $this->input->getString('folder');
		$filter		= new InputFilter();
		$folder		= $filter->clean($folder, 'path');

		// set FTP credentials, if given
		ClientHelper::setCredentialsFromRequest('ftp');

		// set the target directory
		$base_path	= JPATH_ROOT . '/images/advportfolio/images/';
		$return_url	= Route::_('index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=' . $image_id . '&folder=' . $folder, false);

		// check if valid upload file
		if (!isset($file['name']) || !\Joomla\Component\Advportfolio\Administrator\Helper\ImageLibHelper::check($file)) {
			$app->redirect($return_url, Text::_('COM_ADVPORTFOLIO_INVALID_IMAGE'));
		}

		// sanitize the image name
		$file_name	= \Joomla\Component\Advportfolio\Administrator\Helper\ImageLibHelper::sanitize($base_path, $file['name']);
		$file_path	= $base_path . ($folder ? $folder . '/' : '') . $file_name;

		// upload the image
		if (!File::upload($file['tmp_name'], $file_path)) {
			$app->redirect($return_url, Text::_('COM_ADVPORTFOLIO_IMAGE_UPLOAD_FAILED'));
		} else {
			$app->redirect($return_url, Text::_('COM_ADVPORTFOLIO_IMAGE_UPLOAD_SUCCESS'));
		}
	}

	/**
	 * Method to ajax upload an image.
	 */
	public function ajaxUpload()
	{
		// check for request forgeries
		Session::checkToken() or jexit('Invalid Token');

		// initialize variables
		$user		= Factory::getApplication()->getIdentity();
		$app		= Factory::getApplication();

		// Get some data from the request
		$file		= $app->getInput()->files->get('image', [], 'array');
		$image_id	= $this->input->get('image_id');
		$folder		= $this->input->getString('folder');
		$filter		= new InputFilter();
		$folder		= $filter->clean($folder, 'path');

		// set FTP credentials, if given
		ClientHelper::setCredentialsFromRequest('ftp');

		// set the target directory
		$base_path	= JPATH_ROOT . '/images/advportfolio/images/';
		$return_url	= Route::_('index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=' . $image_id . '&folder=' . $folder, false);

		// check if valid upload file
		if (!isset($file['name']) || !\Joomla\Component\Advportfolio\Administrator\Helper\ImageLibHelper::check($file)) {
			exit;
		}

		// sanitize the image name
		$file_name	= \Joomla\Component\Advportfolio\Administrator\Helper\ImageLibHelper::sanitize($base_path, $file['name']);
		$file_path	= $base_path . ($folder ? $folder . '/' : '') . $file_name;

		// upload the image
		File::upload($file['tmp_name'], $file_path);

		exit;
	}

	/**
	 * Method to delete images.
	 */
	public function delete()
	{
		// initialize variables
		$user		= Factory::getApplication()->getIdentity();
		$app		= Factory::getApplication();

		// Get some data from the request
		$image_id	= $this->input->getString('file_id');
		$images		= $this->input->get('rm', [], 'array');

		// set FTP credentials, if given
		ClientHelper::setCredentialsFromRequest('ftp');

		// set the target directory
		$base_path	= JPATH_ROOT . '/images/advportfolio/images/';
		$return_url	= Route::_('index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=' . $image_id, false);

		if (count($images)) {
			$return_url	= Route::_('index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=' . $image_id . '&folder=' . dirname($images[0]), false);
			$filter		= new InputFilter();
			foreach ($images as $image) {
				if ($image !== $filter->clean($image, 'path')) {
					$app->enqueueMessage(Text::_('COM_ADVPORTFOLIO_IMAGE_UNABLE_DELETE') . ' ' . htmlspecialchars($image, ENT_COMPAT, 'UTF-8'), 'warning');
					$app->redirect($return_url);
				}

				$full_path	= Path::clean($base_path . $image);
				if (is_file($full_path)) {
					File::delete($full_path);
				}
			}
		}

		$app->redirect($return_url, Text::_('COM_ADVPORTFOLIO_IMAGE_DELETE_SUCCESS'));
	}

	/**
	 * Create new folder.
	 */
	public function createFolder()
	{
		$file_id	= $this->input->getCmd('image_id');
		$folder		= $this->input->getString('folder');
		$filter		= new InputFilter();
		$folder		= $filter->clean($folder, 'path');
		$newFolder	= $this->input->getString('new_folder');
		$newFolder	= Folder::makeSafe($newFolder);
		$basePath	= JPATH_ROOT . '/images/advportfolio/images/';

		if ($newFolder) {
			Folder::create(Folder::makeSafe($basePath . '/' . ($folder ? $folder . '/' : '') . $newFolder));
		}

		$this->setRedirect(Route::_('index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=' . $file_id . '&folder=' . $folder, false), Text::_('COM_ADVPORTFOLIO_FOLDER_CREATE_SUCCESS'));
	}
}