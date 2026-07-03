<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSMVCControllerAdminController;
use JoomlaCMSFactory;
use JoomlaCMSLanguageText;
use JoomlaCMSRouterRoute;

class AdvPortfolioControllerProjects extends AdminController
{
	public function __construct($config = [])
	{
		parent::__construct($config);
	}

	public function saveOrderAjax()
	{
		$pks = $this->input->post->get('cid', [], 'array');
		$order = $this->input->post->get('order', [], 'array');
		$pks = array_map('intval', $pks);
		$order = array_map('intval', $order);
		$model = $this->getModel();
		$model->saveorder($pks, $order);
		Factory::getApplication()->close();
	}

	public function publish()
	{
		Factory::getApplication()->checkToken();
		$model = $this->getModel();
		$pks = $this->input->post->get('cid', [], 'array');
		$value = $this->input->getInt('value', 1);
		if (!$model->publish($pks, $value)) {
			$this->setMessage($model->getError(), 'error');
		} else {
			$ntext = $this->getTextPrefix('COM_ADVPORTFOLIO');
			$this->setMessage(Text::plural($ntext . '_N_ITEMS_PUBLISHED', count($pks)));
		}
		$this->setRedirect(Route::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
	}

	public function archive()
	{
		Factory::getApplication()->checkToken();
		$model = $this->getModel();
		$pks = $this->input->post->get('cid', [], 'array');
		if (!$model->archive($pks)) {
			$this->setMessage($model->getError(), 'error');
		} else {
			$ntext = $this->getTextPrefix('COM_ADVPORTFOLIO');
			$this->setMessage(Text::plural($ntext . '_N_ITEMS_ARCHIVED', count($pks)));
		}
		$this->setRedirect(Route::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
	}

	public function trash()
	{
		Factory::getApplication()->checkToken();
		$model = $this->getModel();
		$pks = $this->input->post->get('cid', [], 'array');
		if (!$model->trash($pks)) {
			$this->setMessage($model->getError(), 'error');
		} else {
			$ntext = $this->getTextPrefix('COM_ADVPORTFOLIO');
			$this->setMessage(Text::plural($ntext . '_N_ITEMS_TRASHED', count($pks)));
		}
		$this->setRedirect(Route::_('index.php?option=' . $this->option . '&view=' . $this->view_list, false));
	}
}