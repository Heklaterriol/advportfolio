<?php
/**
 * @package     AdvPortfolio
 * @subpackage  Admin.Controllers
 * @copyright   Copyright (C) 2026 Hekla Terriol. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Response\JsonResponse;

class AdvPortfolioControllerImageHandler extends BaseController
{
	public function upload()
	{
		Factory::getApplication()->checkToken('get');
		$file = $this->input->files->get('file');
		if (empty($file) || !isset($file['tmp_name'])) {
			$response = new JsonResponse(['success' => false, 'message' => Text::_('COM_ADVPORTFOLIO_NO_FILE_UPLOADED')], Text::_('COM_ADVPORTFOLIO_UPLOAD_ERROR'), true);
			$response->send();
			return;
		}
		$model = $this->getModel('ImageHandler');
		$result = $model->uploadImage($file);
		if ($result === false) {
			$response = new JsonResponse(['success' => false, 'message' => $model->getError()], Text::_('COM_ADVPORTFOLIO_UPLOAD_ERROR'), true);
			$response->send();
			return;
		}
		$response = new JsonResponse(['success' => true, 'file' => $result], Text::_('COM_ADVPORTFOLIO_UPLOAD_SUCCESS'), true);
		$response->send();
	}

	public function delete()
	{
		Factory::getApplication()->checkToken('get');
		$filename = $this->input->get('filename', '', 'string');
		if (empty($filename)) {
			$response = new JsonResponse(['success' => false, 'message' => Text::_('COM_ADVPORTFOLIO_NO_FILENAME_PROVIDED')], Text::_('COM_ADVPORTFOLIO_DELETE_ERROR'), true);
			$response->send();
			return;
		}
		$model = $this->getModel('ImageHandler');
		$result = $model->deleteImage($filename);
		if ($result === false) {
			$response = new JsonResponse(['success' => false, 'message' => $model->getError()], Text::_('COM_ADVPORTFOLIO_DELETE_ERROR'), true);
			$response->send();
			return;
		}
		$response = new JsonResponse(['success' => true, 'message' => Text::_('COM_ADVPORTFOLIO_IMAGE_DELETED')], Text::_('COM_ADVPORTFOLIO_DELETE_SUCCESS'), true);
		$response->send();
	}
}