<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSMVCModelAdminModel;
use JoomlaCMSFactory;
use JoomlaCMSTableTable;

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
			$registry = new JoomlaRegistryRegistry($item->params);
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
			$registry = new JoomlaRegistryRegistry($data['params']);
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