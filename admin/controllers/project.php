<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSMVCControllerFormController;
use JoomlaCMSFactory;
use JoomlaCMSLanguageText;
use JoomlaCMSRouterRoute;

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