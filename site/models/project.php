<?php
/**
 * @package     AdvPortfolio
 * @subpackage  Site.Models
 * @copyright   Copyright (C) 2026 Hekla Terriol. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;

class AdvPortfolioModelProject extends ItemModel
{
	protected $typeAlias = 'com_advportfolio.project';

	public function getItem($pk = null)
	{
		$item = parent::getItem($pk);
		if ($item) {
			$tagsHelper = new TagsHelper;
			$item->tags = $tagsHelper->getItemTags('com_advportfolio.project', $item->id);
		}
		return $item;
	}

	public function getNextItem()
	{
		$db = $this->getDatabase();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__advportfolio_projects AS a');
		$query->where('a.catid = ' . (int) $this->getState('item.catid'));
		$query->where('a.state = 1');
		$query->where('a.id > ' . (int) $this->getState('item.id'));
		$query->order('a.ordering ASC, a.id ASC');
		$query->setLimit(1);
		$db->setQuery($query);
		$result = $db->loadObject();
		if ($result) {
			$tagsHelper = new TagsHelper;
			$result->tags = $tagsHelper->getItemTags('com_advportfolio.project', $result->id);
		}
		return $result;
	}

	public function getPrevItem()
	{
		$db = $this->getDatabase();
		$query = $db->getQuery(true);
		$query->select('a.*');
		$query->from('#__advportfolio_projects AS a');
		$query->where('a.catid = ' . (int) $this->getState('item.catid'));
		$query->where('a.state = 1');
		$query->where('a.id < ' . (int) $this->getState('item.id'));
		$query->order('a.ordering DESC, a.id DESC');
		$query->setLimit(1);
		$db->setQuery($query);
		$result = $db->loadObject();
		if ($result) {
			$tagsHelper = new TagsHelper;
			$result->tags = $tagsHelper->getItemTags('com_advportfolio.project', $result->id);
		}
		return $result;
	}

	public function getReturnPage()
	{
		return base64_decode($this->getState('return_page'));
	}
}