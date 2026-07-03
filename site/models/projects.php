<?php
/**
 * @copyright    Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSMVCModelListModel;
use JoomlaCMSFactory;
use JoomlaCMSLanguageLanguageHelper;
use JoomlaCMSHelperTagsHelper;

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
		$params = $app->getParams();
		$menuParams = new JoomlaRegistryRegistry;
		if ($menu = $app->getMenu()->getActive()) {
			$menuParams->loadString($menu->params);
		}
		$mergedParams = clone $menuParams;
		$mergedParams->merge($params);
		$this->setState('params', $mergedParams);
		$this->setState('list.limit', $app->getInput()->get('limit', $params->get('limit', $app->get('list_limit', 0)), 'uint'));
		$this->setState('list.start', $app->getInput()->get('limitstart', 0, 'uint'));
		$this->setState('list.ordering', $this->_buildContentOrderBy());
		$this->setState('list.direction', '');
		$this->setState('filter.category_id', $params->get('catids'));
		$this->setState('filter.access', !$params->get('show_noauth'));
		$this->setState('list.filter', $app->getInput()->getString('filter-search'));
		$this->setState('filter.language', LanguageHelper::isMultilang());
		$this->setState('layout', $app->getInput()->get('layout'));
	}

	protected function getStoreId($id = '')
	{
		$id .= ':' . $this->getState('list.filter');
		$id .= ':' . $this->getState('filter.access');
		$id .= ':' . $this->getState('filter.state');
		$id .= ':' . serialize($this->getState('filter.project_id'));
		$id .= ':' . serialize($this->getState('filter.category_id'));
		$id .= ':' . $this->getState('filter.language');
		return parent::getStoreId($id);
	}

	protected function getListQuery()
	{
		$db = $this->getDatabase();
		$query = $db->getQuery(true);
		$query->select($this->getState('list.select', 'a.id, a.title, a.alias, a.checked_out, a.checked_out_time, a.catid, a.state, a.access, a.ordering, a.language, a.created, a.short_description, a.thumbnail, a.link'));
		$query->select('CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(':', a.id, a.alias) ELSE a.id END AS slug');
		$query->from('#__advportfolio_projects AS a');
		$query->select('c.title AS category_title');
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');
		if ($access = $this->getState('filter.access')) {
			$user = Factory::getApplication()->getIdentity();
			$groups = implode(', ', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
			$query->where('c.access IN (' . $groups . ')');
		}
		$published = $this->getState('filter.published', 1);
		if (is_numeric($published)) {
			$query->where('a.state = ' . $published);
		}
		$projectId = $this->getState('filter.project_id');
		if (is_numeric($projectId)) {
			$type = $this->getState('filter.project_id.include', true) ? '= ' : '<> ';
			$query->where('a.id ' . $type . (int) $projectId);
		}
		$categoryId = $this->getState('filter.category_id');
		if (is_numeric($categoryId)) {
			$includeSubcategories = $this->getState('filter.subcategories', false);
			$categoryEquals = 'a.catid = ' . (int) $categoryId;
			if ($includeSubcategories) {
				$levels = (int) $this->getState('filter.max_category_levels', '1');
				$subQuery = $db->getQuery(true);
				$subQuery->select('sub.id');
				$subQuery->from('#__categories as sub');
				$subQuery->join('INNER', '#__categories as this ON sub.lft > this.lft AND sub.rgt < this.rgt');
				$subQuery->where('this.id = ' . (int) $categoryId);
				if ($levels >= 0) {
					$subQuery->where('sub.level <= this.level + ' . $levels);
				}
				$query->where('(' . $categoryEquals . ' OR a.catid IN (' . (string) $subQuery . '))');
			} else {
				$query->where($categoryEquals);
			}
		}
		if ($language = $this->getState('filter.language')) {
			$query->where('a.language in (' . $db->quote(Factory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');
		}
		$query->order($this->getState('list.ordering', 'a.ordering') . ' ' . $this->getState('list.direction', 'ASC'));
		return $query;
	}

	protected function _buildContentOrderBy()
	{
		$app = Factory::getApplication();
		$db = $this->getDatabase();
		$params = $this->state->params;
		$itemid = $app->getInput()->get('id', 0, 'int') . ':' . $app->getInput()->get('Itemid', 0, 'int');
		$orderCol = $app->getUserStateFromRequest('com_advportfolio.projects.' . $itemid . '.filter_order', 'filter_order', '', 'string');
		$orderDirn = $app->getUserStateFromRequest('com_advportfolio.projects.' . $itemid . '.filter_order_Dir', 'filter_order_Dir', '', 'cmd');
		$orderby = ' ';
		if (!in_array($orderCol, $this->filter_fields)) {
			$orderCol = null;
		}
		if (!in_array(strtoupper($orderDirn), ['ASC', 'DESC', ''])) {
			$orderDirn = 'ASC';
		}
		if ($orderCol && $orderDirn) {
			$orderby .= $db->escape($orderCol) . ' ' . $db->escape($orderDirn) . ', ';
		}
		$orderby = $params->get('orderby', 'rdate');
		switch ($orderby) {
			case 'date': $orderby = 'a.created'; break;
			case 'rdate': $orderby = 'a.created DESC '; break;
			case 'alpha': $orderby = 'a.title'; break;
			case 'ralpha': $orderby = 'a.title DESC'; break;
			case 'order':
			default: $orderby = 'c.lft, a.ordering'; break;
		}
		return $orderby;
	}

	public function getItems()
	{
		$items = parent::getItems();
		foreach ($items as &$item) {
			$item->tags = [];
			$tags = new TagsHelper;
			$itemTags = $tags->getItemTags('com_advportfolio.project', $item->id);
			foreach ($itemTags as $tag) {
				$item->tags[] = $tag->title;
			}
		}
		return $items;
	}
}