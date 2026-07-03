<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Image Handler Controller.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.AdvPortfolio
 */
class AdvPortfolioControllerImageHandler extends JControllerLegacy {

	/**
	 * Method to upload an image.
	 */
	public function upload() {
		// check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		// initialize variables
		$user		= JFactory::getUser();
		$app		= JFactory::getApplication();

		// Get some data from the request
		$file		= JRequest::getVar('image', '', 'files', 'array');
		$image_id	= $this->input->get('image_id');
		$folder		= $this->input->getString('folder');
		$filter		= new JFilterInput();
		$folder		= $filter->clean($folder, 'path');

		// set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');

		// set the target directory
		$base_path	= JPATH_ROOT . '/images/advportfolio/images/';
		$return_url	= JRoute::_('index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=' . $image_id . '&folder=' . $folder, false);

		// check if valid upload file
		if (!isset($file['name']) || !AdvPortfolioImageLib::check($file)) {
			$app->redirect($return_url, JText::_('COM_ADVPORTFOLIO_INVALID_IMAGE'));
		}

		// sanitize the image name
		$file_name	= AdvPortfolioImageLib::sanitize($base_path, $file['name']);
		$file_path	= $base_path . ($folder ? $folder . '/' : '') . $file_name;

		// upload the image
		if (!JFile::upload($file['tmp_name'], $file_path)) {
			$app->redirect($return_url, JText::_('COM_ADVPORTFOLIO_IMAGE_UPLOAD_FAILED'));
		} else {
			$app->redirect($return_url, JText::_('COM_ADVPORTFOLIO_IMAGE_UPLOAD_SUCCESS'));
		}
	}

	/**
	 * Method to ajax upload an image.
	 */
	public function ajaxUpload() {
		// check for request forgeries
		JSession::checkToken() or jexit('Invalid Token');

		// initialize variables
		$user		= JFactory::getUser();
		$app		= JFactory::getApplication();

		// Get some data from the request
		$file		= JRequest::getVar('image', '', 'files', 'array');
		$image_id	= $this->input->get('image_id');
		$folder		= $this->input->getString('folder');
		$filter		= new JFilterInput();
		$folder		= $filter->clean($folder, 'path');

		// set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');

		// set the target directory
		$base_path	= JPATH_ROOT . '/images/advportfolio/images/';
		$return_url	= JRoute::_('index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=' . $image_id . '&folder=' . $folder, false);

		// check if valid upload file
		if (!isset($file['name']) || !AdvPortfolioImageLib::check($file)) {
			exit;
		}

		// sanitize the image name
		$file_name	= AdvPortfolioImageLib::sanitize($base_path, $file['name']);
		$file_path	= $base_path . ($folder ? $folder . '/' : '') . $file_name;

		// upload the image
		JFile::upload($file['tmp_name'], $file_path);

		exit;
	}

	/**
	 * Method to delete images.
	 */
	public function delete() {
		// initialize variables
		$user		= JFactory::getUser();
		$app		= JFactory::getApplication();

		// Get some data from the request
		$image_id	= $this->input->getString('file_id');
		$images		= $this->input->get('rm', array(), 'array');

		// set FTP credentials, if given
		JClientHelper::setCredentialsFromRequest('ftp');

		// set the target directory
		$base_path	= JPATH_ROOT . '/images/advportfolio/images/';
		$return_url	= JRoute::_('index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=' . $image_id, false);

		if (count($images)) {
			$return_url	= JRoute::_('index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=' . $image_id . '&folder=' . dirname($images[0]), false);
			$filter		= new JFilterInput();
			foreach ($images as $image) {
				if ($image !== $filter->clean($image, 'path')) {
					JError::raiseWarning(100, JText::_('COM_ADVPORTFOLIO_IMAGE_UNABLE_DELETE') . ' ' . htmlspecialchars($image, ENT_COMPAT, 'UTF-8'));
					$app->redirect($return_url);
				}

				$full_path	= JPath::clean($base_path . $image);
				if (is_file($full_path)) {
					JFile::delete($full_path);
				}
			}
		}

		$app->redirect($return_url, JText::_('COM_ADVPORTFOLIO_IMAGE_DELETE_SUCCESS'));
	}

	/**
	 * Create new folder.
	 */
	public function createFolder() {
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');

		$file_id	= $this->input->getCmd('image_id');
		$folder		= $this->input->getString('folder');
		$filter		= new JFilterInput();
		$folder		= $filter->clean($folder, 'path');
		$newFolder	= $this->input->getString('new_folder');
		$newFolder	= JFolder::makeSafe($newFolder, 'path');
		$basePath	= JPATH_ROOT . '/images/advportfolio/images/';

		if ($newFolder) {
			JFolder::create(JFolder::makeSafe($basePath . '/' . ($folder ? $folder . '/' : '') . $newFolder));
		}

		$this->setRedirect(JRoute::_('index.php?option=com_advportfolio&view=imagehandler&tmpl=component&image_id=' . $file_id . '&folder=' . $folder, false), JText::_('COM_ADVPORTFOLIO_FOLDER_CREATE_SUCCESS'));
	}
}