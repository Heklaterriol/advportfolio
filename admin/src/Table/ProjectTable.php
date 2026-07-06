<?php
/**
 * @copyright    Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\Table;

defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Table\Table;
use Joomla\Database\DatabaseInterface;
use Joomla\Registry\Registry;
use Joomla\Utilities\ArrayHelper;

/**
 * Advanced Portfolio Component - Project Table Class.
 *
 * @package        Joomla.Administrator
 * @subpakage    Skyline.Portfolio
 */
class ProjectTable extends Table
{
	/**
	 * Constructor.
	 *
	 * @param    DatabaseInterface    A database connector object.
	 */
	protected $tagsHelper = null;

	public function __construct(DatabaseInterface $db)
	{
		parent::__construct('#__advportfolio_projects', 'id', $db);

		$this->tagsHelper = new TagsHelper();
		$this->tagsHelper->typeAlias = 'com_advportfolio.project';
	}

	/**
	 * Overloaded bind function to pre-process the params.
	 *
	 * @param    array        Named array
	 * @return    null|string    null is operation was satisfactory, otherwise returns an error.
	 * @see        Table::bind
	 */
	public function bind($array, $ignore = '')
	{
		if (isset($array['params']) && is_array($array['params'])) {
			$registry = new Registry();
			$registry->loadArray($array['params']);
			$array['params'] = (string)$registry;
		}

		if (isset($array['metadata']) && is_array($array['metadata'])) {
			$registry = new Registry();
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string)$registry;
		}

		if (isset($array['images']) && is_array($array['images'])) {
			$registry = new Registry();
			$registry->loadArray($array['images']);
			$array['images'] = (string)$registry;
		}

		return parent::bind($array, $ignore);
	}

	/**
	 * Overload the store method for the Project table.
	 *
	 * @param    boolean    Toggle whether null values should be updated.
	 * @return    boolean    True on success, false on failure.
	 */
	public function store($updateNulls = false)
	{
		$date = Factory::getDate();
		$user = Factory::getApplication()->getIdentity();

		if ($this->id) {
			// Existing item
			$this->modified = $date->toSql();
			$this->modified_by = $user->get('id');
		} else {
			// New Project. A Project created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.
			if (!(int)$this->created) {
				$this->created = $date->toSql();
			}

			if (empty($this->created_by)) {
				$this->created_by = $user->get('id');
			}

		}

		// Verify that the alias is unique
		$table = Table::getInstance('Project', 'Administrator');
		if ($table->load(array('alias' => $this->alias, 'catid' => $this->catid)) && ($table->id != $this->id || $this->id == 0)) {
			$this->setError(Text::_('COM_ADVPORTFOLIO_PROJECT_ERROR_UNIQUE_ALIAS'));

			return false;
		}

		// Attempt to store the user data.
		$this->tagsHelper->preStoreProcess($this);
		$result = parent::store($updateNulls);

		return $result && $this->tagsHelper->postStoreProcess($this);
	}

	/**
	 * Overloaded check method to ensure data integrity.
	 *
	 * @return    boolean        True on success.
	 */
	public function check()
	{
		// check for valid name
		if (trim($this->title) == '') {
			$this->setError(Text::_('COM_ADVPORTFOLIO_PROJECT_ERROR_TABLES_TITLE'));

			return false;
		}

		// check for existing name
		$query = 'SELECT id'
				. ' FROM #__advportfolio_projects'
				. ' WHERE title = ' . $this->_db->quote($this->title) . ' AND catid = ' . (int)$this->catid;
		$this->_db->setQuery($query);

		$xid = intval($this->_db->loadResult());
		if ($xid && $xid != intval($this->id)) {
			$this->setError(Text::_('COM_ADVPORTFOLIO_PROJECT_ERROR_TABLES_NAME'));

			return false;
		}

		// check for valid video link
		$youtube_regex	= '/(youtube\.com|youtu\.be|youtube-nocookie\.com)\/(watch\?v=|v\/|u\/|embed\/?)?(videoseries\?list=(.*)|[\w-]{11}|\?listType=(.*)&list=(.*)).*/i';
		$vimeo_regex	= '/(?:vimeo(?:pro)?.com)\/(?:[^\d]+)?(\d+)(?:.*)/';

		if ($this->type == 1 && !preg_match($youtube_regex, $this->video_link) && !preg_match($vimeo_regex, $this->video_link)) {
			$this->setError(Text::_('COM_ADVPORTFOLIO_PROJECT_ERROR_VIDEO_LINK'));

			return false;
		} else {
			if ($this->type == 1 && !preg_match('/^(http:\/\/)/', $this->video_link)) {
				$this->video_link = $this->video_link;
			}
		}

		if (empty($this->alias)) {
			$this->alias = $this->title;
		}

		$this->alias = ApplicationHelper::stringURLSafe($this->alias);
		if (trim(str_replace('-', '', $this->alias)) == '') {
			$this->alias = Factory::getDate()->format('Y-m-d-H-i-s');
		}

		// clean up keywords -- eliminate extra spaces between phrases
		// and cr (\r) and lf (\n) characters from string
		if (!empty($this->metakey)) {
			// only process if not empty
			$bad_characters = array("\n", "\r", '"', '<', '>');
			$after_clean = str_ireplace($bad_characters, '', $this->metakey);
			$keys = explode(',', $after_clean);
			$clean_keys = array();

			foreach ($keys as $key) {
				if (trim($key)) {
					$clean_keys[] = trim($key);
				}
			}

			$this->metakey = implode(', ', $clean_keys);
		}

		return true;
	}

	public function delete($pk = null)
	{
		$result = parent::delete($pk);

		return $result && $this->tagsHelper->deleteTagData($this, $pk);
	}

	/**
	 * Method to set the publishing state for a row or list of rows in the database
	 * table. The method respects checked out rows by other users and will attempt
	 * to checkin rows that it can after adjustments are made.
	 *
	 * @param    mixed    An optional array of primary key values to update. If not
	 *                    set the instance property value is used.
	 * @param    int        The publishing state. eg. [0 = unpublished, 1 = published]
	 * @param    int        The user id of the user performing the operation.
	 * @return    bool    True on success.
	 */
	public function publish($pks = null, $state = 1, $userId = 0)
	{
		// Initialize variables.
		$k = $this->_tbl_key;

		// Sanitize input.
		ArrayHelper::toInteger($pks);
		$userId = (int)$userId;
		$state = (int)$state;

		// If there are no primary keys set check to see if the instance key is set.
		if (empty($pks)) {
			if ($this->$k) {
				$pks = array($this->$k);
			} else {
				$this->setError(Text::_('JLIB_DATABASE_ERROR_NO_ROWS_SELECTED'));

				return false;
			}
		}

		// Build the WHERE clause for the primary keys.
		$where = $k . ' = ' . implode(' OR ' . $k . ' = ', $pks);

		// Determine if there is checkin support for the table.
		if (!property_exists($this, 'checked_out') || !property_exists($this, 'checked_out_time')) {
			$checkin = '';
		} else {
			$checkin = ' AND (checked_out = 0 OR checked_out = ' . (int)$userId . ')';
		}

		// UPdate the publishing state for rows with the given primary keys.
		$query = 'UPDATE `' . $this->_tbl . '`'
				. ' SET `state` = ' . (int)$state
				. ' WHERE (' . $where . ')'
				. $checkin;
		$this->_db->setQuery($query);

		try {
			$this->_db->execute();
		} catch (\RuntimeException $e) {
			$this->setError($e->getMessage());

			return false;
		}

		// If checkin is supported and all rows were adjusted, check them in.
		if ($checkin && (count($pks) == $this->_db->getAffectedRows())) {
			// Checkin the rows.
			foreach ($pks as $pk) {
				$this->checkin($pk);
			}
		}

		// If the Table instance value is in the list of primary keys that were set, set the instance.
		if (in_array($this->$k, $pks)) {
			$this->state = $state;
		}

		$this->setError('');

		return true;
	}
}