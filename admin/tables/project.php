<?php
/**
 * @copyright    Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSTableTable;
use JoomlaCMSFactory;
use JoomlaCMSLanguageText;
use JoomlaCMSHelperStringHelper;
use JoomlaCMSHelperTagsHelper;
use JoomlaRegistryRegistry;

class AdvPortfolioTableProject extends Table
{
	public function __construct($db)
	{
		parent::__construct('#__advportfolio_projects', 'id', $db);
		$this->tagsHelper = new TagsHelper;
		$this->tagsHelper->typeAlias = 'com_advportfolio.project';
	}

	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new Registry;
			$registry->loadArray($array['params']);
			$array['params'] = (string) $registry;
		}
		if (isset($array['metadata']) && is_array($array['metadata'])) {
			$registry = new Registry;
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}
		if (isset($array['images']) && is_array($array['images'])) {
			$registry = new Registry;
			$registry->loadArray($array['images']);
			$array['images'] = (string) $registry;
		}
		return parent::bind($array, $ignore);
	}

	public function store($updateNulls = false)
	{
		$date = Factory::getDate();
		$user = Factory::getApplication()->getIdentity();
		if ($this->id) {
			$this->modified = $date->toSql();
			$this->modified_by = $user->id;
		} else {
			if (!(int) $this->created) {
				$this->created = $date->toSql();
			}
			if (empty($this->created_by)) {
				$this->created_by = $user->id;
			}
		}
		$table = Table::getInstance('Project', 'AdvPortfolioTable');
		if ($table->load(['alias' => $this->alias, 'catid' => $this->catid]) && ($table->id != $this->id || $this->id == 0)) {
			$this->setError(Text::_('COM_ADVPORTFOLIO_PROJECT_ERROR_UNIQUE_ALIAS'));
			return false;
		}
		$this->tagsHelper->preStoreProcess($this);
		$result = parent::store($updateNulls);
		return $result && $this->tagsHelper->postStoreProcess($this);
	}

	public function check()
	{
		if (trim($this->title) == '') {
			$this->setError(Text::_('COM_ADVPORTFOLIO_PROJECT_ERROR_TABLES_TITLE'));
			return false;
		}
		$query = $this->_db->getQuery(true)
			->select($this->_db->quoteName('id'))
			->from($this->_db->quoteName('#__advportfolio_projects'))
			->where($this->_db->quoteName('title') . ' = ' . $this->_db->quote($this->title))
			->where($this->_db->quoteName('catid') . ' = ' . (int) $this->catid);
		$this->_db->setQuery($query);
		$xid = (int) $this->_db->loadResult();
		if ($xid && $xid != (int) $this->id) {
			$this->setError(Text::_('COM_ADVPORTFOLIO_PROJECT_ERROR_TABLES_NAME'));
			return false;
		}
		$youtubeRegex = '/(youtube.com|youtu.be|youtube-nocookie.com)/(watch?v=|v/|u/|embed/?)?(videoseries?list=(.*)|[w-]{11}|?listType=(.*)&list=(.*)).*/i';
		$vimeoRegex = '/(?:vimeo(?:pro)?.com)/(?:[^d]+)?(d+)(?:.*)/';
		if ($this->type == 1 && !preg_match($youtubeRegex, $this->video_link) && !preg_match($vimeoRegex, $this->video_link)) {
			$this->setError(Text::_('COM_ADVPORTFOLIO_PROJECT_ERROR_VIDEO_LINK'));
			return false;
		}
		if (empty($this->alias)) {
			$this->alias = $this->title;
		}
		$this->alias = StringHelper::stringURLSafe($this->alias);
		if (trim(str_replace('-', '', $this->alias)) == '') {
			$this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
		}
		if (!empty($this->metakey)) {
			$badCharacters = ["
", "", '"', '<', '>'];
			$afterClean = StringHelper::str_ireplace($badCharacters, '', $this->metakey);
			$keys = explode(',', $afterClean);
			$cleanKeys = [];
			foreach ($keys as $key) {
				if (trim($key)) {
					$cleanKeys[] = trim($key);
				}
			}
			$this->metakey = implode(', ', $cleanKeys);
		}
		return true;
	}

	public function delete($pk = null)
	{
		$result = parent::delete($pk);
		return $result && $this->tagsHelper->deleteTagData($this, $pk);
	}

	public function publish($pks = null, $state = 1, $userId = 0)
	{
		$k = $this->_tbl_key;
		$pks = array_map('intval', (array) $pks);
		$userId = (int) $userId;
		$state = (int) $state;
		if (empty($pks)) {
			if ($this->$k) {
				$pks = [$this->$k];
			} else {
				$this->setError(Text::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));
				return false;
			}
		}
		$where = $k . ' = ' . implode(' OR ' . $k . ' = ', $pks);
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
			$checkin = '';
		} else {
			$checkin = ' AND (checked_out = 0 OR checked_out = ' . (int) $userId . ')';
		}
		$query = $this->_db->getQuery(true)
			->update($this->_db->quoteName($this->_tbl))
			->set($this->_db->quoteName('state') . ' = ' . (int) $state)
			->where('(' . $where . ')')
			->where($checkin);
		$this->_db->setQuery($query);
		try {
			$this->_db->execute();
		} catch (RuntimeException $e) {
			$this->setError($e->getMessage());
			return false;
		}
		if ($this->_db->getErrorNum()) {
			$this->setError($this->_db->getErrorMsg());
			return false;
		}
		if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
			foreach ($pks as $pk) {
				$this->checkin($pk);
			}
		}
		if (in_array($this->$k, $pks)) {
			$this->state = $state;
		}
		$this->setError('');
		return true;
	}
}