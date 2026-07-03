<?php
/**
 * @package     AdvPortfolio
 * @subpackage  Admin.Models
 * @copyright   Copyright (C) 2026 Hekla Terriol. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ListModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;

class AdvPortfolioModelProjects extends ListModel
{
	public function __construct($config = [])
	{
		if (empty($config['filter_fields'])) {
			$config['filter_fields'] = [
				'id', 'a.id', 'title', 'a.title', 'alias', 'a.alias',
				'checked_out', 'a.checked_out', 'checked_out_time', 'a.checked_out_time',
				'catid', 'a.catid', 'category_title', 'state', 'a.state',
				'access', 'a.access', 'access_level', 'created', 'a.created',
				'created_by', 'a.created_by', 'ordering', 'a.ordering',
				'language', 'a.language'
			];
		}
		parent::__construct($config);
	}

	protected function populateState($ordering = null, $direction = null)
	{
		$app = Factory::getApplication();
		$this->setState('filter.search', $this->getUserStateFromRequest($this->context . '.filter.search', 'filter_search'));
		$this->setState('filter.access', $this->getUserStateFromRequest($this->context . '.filter.access', 'filter_access', null, 'int'));
		$this->setState('filter.state', $this->getUserStateFromRequest($this->context . '.filter.state', 'filter_state', '', 'string'));
		$this->setState('filter.category_id', $this->getUserStateFromRequest($this->context . '.filter.category_id', 'filter_category_id', ''));
		$this->setState('filter.language', $this->getUserStateFromRequest($this->context . '.filter.language', 'filter_language', ''));
		$this->setState('filter.tag', $this->getUserStateFromRequest($this->context . '.filter.tag', 'filter_tag', ''));
		$this->setState('params', ComponentHelper::getParams('com_advportfolio'));
		parent::populateState($ordering ? $ordering : 'a.ordering', $direction ? $direction : 'asc');
	}

	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('filter.search');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.state');
		$id .= ':' . $this->getState('filter.category_id');
		$id .= ':' . $this->getState('filter.language');
		return parent::getStoreId($id);
	}
}