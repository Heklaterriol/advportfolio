<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSMVCControllerBaseController;
use JoomlaCMSFactory;
use JoomlaCMSLanguageText;
use JoomlaCMSRouterRoute;
use JoomlaCMSResponseJsonResponse;

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