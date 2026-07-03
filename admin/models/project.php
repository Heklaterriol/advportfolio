<?php
/**
 * @package     AdvPortfolio
 * @subpackage  Admin.Models
 * @copyright   Copyright (C) 2026 Hekla Terriol. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;

class AdvPortfolioModelProject extends AdminModel
{
	protected $typeAlias = 'com_advportfolio.project';

	public function getForm($data = [], $loadData = true)
	{
		$form = $this->loadForm('com_advportfolio.project', 'project', ['control' => 'jform', 'load_data' => $loadData]);
		if (empty($form)) {
			return false;
		}
		return $form;
	}

	protected function loadFormData()
	{
		$data = Factory::getApplication()->getUserState('com_advportfolio.edit.project.data', []);
		if (empty($data)) {
			$data = $this->getItem();
		}
		return $data;
	}

	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);
		if ($item && property_exists($item, 'params')) {
			$registry = new Registry($item->params);
			$item->params = $registry;
		}
		return $item;
	}

	public function save($data)
	{
		$app = Factory::getApplication();
		$user = $app->getIdentity();
		if (!isset($data['created_by']) || empty($data['created_by'])) {
			$data['created_by'] = $user->id;
		}
		$data['modified_by'] = $user->id;
		$data['modified'] = Factory::getDate()->toSql();
		if (!isset($data['id']) || empty($data['id'])) {
			$data['created'] = Factory::getDate()->toSql();
		}
		if (isset($data['params']) && is_array($data['params'])) {
			$registry = new Registry($data['params']);
			$data['params'] = $registry->toString();
		}
		return parent::save($data);
	}

	public function delete(&$pks)
	{
		$pks = (array) $pks;
		$table = $this->getTable();
		foreach ($pks as $pk) {
			if (!$table->load($pk)) {
				$this->setError($table->getError());
				return false;
			}
		}
		return parent::delete($pks);
	}
}