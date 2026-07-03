<?php
/**
 * @package     AdvPortfolio
 * @subpackage  Admin.Controllers
 * @copyright   Copyright (C) 2026 Hekla Terriol. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\FormController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

class AdvPortfolioControllerProject extends FormController
{
	public function __construct($config = [])
	{
		parent::__construct($config);
	}

	public function add()
	{
		$this->setView('project');
		parent::add();
	}

	public function edit($key = null, $urlVar = null)
	{
		$this->setView('project');
		parent::edit($key, $urlVar);
	}

	public function save($key = null, $urlVar = null)
	{
		Factory::getApplication()->checkToken();
		$model = $this->getModel();
		$data = $this->input->post->get('jform', [], 'array');
		if (!$model->save($data)) {
			$this->setRedirect(Route::_('index.php?option=' . $this->option . '&view=' . $this->view_item . $this->getRedirectToItemAppend($recordId), false));
			return false;
		}
		$this->setRedirect(Route::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
		return true;
	}

	public function cancel($key = null)
	{
		$app = Factory::getApplication();
		$app->redirect(Route::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
	}

	public function delete()
	{
		Factory::getApplication()->checkToken();
		$model = $this->getModel();
		$pks = $this->input->post->get('cid', [], 'array');
		if (!$model->delete($pks)) {
			$this->setMessage($model->getError(), 'error');
		} else {
			$this->setMessage(Text::plural('COM_ADVPORTFOLIO_N_ITEMS_DELETED', count($pks)));
		}
		$this->setRedirect(Route::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
	}
}