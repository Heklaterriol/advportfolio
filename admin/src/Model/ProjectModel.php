<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Application\ApplicationHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\Helper\TagsHelper;
use Joomla\CMS\MVC\Model\AdminModel;
use Joomla\CMS\Table\Table;
use Joomla\Registry\Registry;

/**
 * Project Model.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.Portfolio
 */
class ProjectModel extends AdminModel
{
	/** @var string		The prefix to use with controller messages. */
	protected $text_prefix	= 'COM_ADVPORTFOLIO_PROJECTS';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	bool	True if allowed to delete the record. Defaults to the permission set in the component.
	 */
	protected function canDelete($record)
	{
		if (!empty($record->id)) {
			if ($record->state != -2) {
				return;
			}

			$user = Factory::getApplication()->getIdentity();

			if ($record->catid) {
				return $user->authorise('core.delete', 'com_advportfolio.category.' . (int) $record->catid);
			} else {
				return parent::canDelete($record);
			}
		}
	}

	/**
	 * Method to test whether a record can have its state changed.
	 *
	 * @param	object	A record object.
	 * @return	bool	True if allowed to change the state of the record. Defaults to the permission set in the component.
	 */
	protected function canEditState($record)
	{
		$user	= Factory::getApplication()->getIdentity();

		if (!empty($record->catid)) {
			return $user->authorise('core.edit.state', 'com_advportfolio.category.' . (int) $record->catid);
		} else {
			return parent::canEditState($record);
		}
	}

	/**
	 * Returns a reference to a Table object, always creating it.
	 *
	 * @param	type	The table type to instantiate
	 * @param	string	A prefix for the table class name. Optional.
	 * @pararm	array	Configuration array for model. Optional.
	 * @return	Table				A database object
	 */
	public function getTable($type = 'Project', $prefix = 'Administrator', $config = array())
	{
		return parent::getTable($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	mixed				A Form object on success, false on failure.
	 */
	public function getForm($data = array(), $loadData = true)
	{
		// Initialize variables.
		$app	= Factory::getApplication();

		// Get the form.
		$form	= $this->loadForm('com_advportfolio.project', 'project', array('control' => 'jform', 'load_data' => $loadData));
		if (empty($form)) {
			return false;
		}

		// Determine correct permissions to check.
		if ($this->getState('project.id')) {
			// Existing record. Can only edit in selected Categories.
			$form->setFieldAttribute('catid', 'action', 'core.edit');
		} else {
			// New record. Can only create in selected Categories.
			$form->setFieldAttribute('catid', 'action', 'core.create');
		}
		// Modify the form based on access controls.
		if (!$this->canEditState((object) $data)) {
			// Disable fields for display.
			$form->setFieldAttribute('ordering', 'disabled', 'true');
			$form->setFieldAttribute('state', 'disabled', 'true');

			// Disable field while saving.
			// The controller has already verified this is a record you can edit.
			$form->setFieldAttribute('ordering', 'filter', 'unset');
			$form->setFieldAttribute('state', 'filter', 'unset');
		}

		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return mixed	The data for the form.
	 */
	protected function loadFormData()
	{
		$app = Factory::getApplication();

		// Check the session for previously entered form data.
		$data	= Factory::getApplication()->getUserState('com_advportfolio.edit.project.data', array());

		if (empty($data)) {
			$data	= $this->getItem();

			// Prime some default values.
			if ($this->getState('project.id') == 0) {
				$app	= Factory::getApplication();
				$data->set('catid', $app->getInput()->getInt('catid', $app->getUserState('com_advportfolio.projects.filter.category_id')));
			}
		}

		return $data;
	}

	/**
	 * Method to get a single record.
	 *
	 * @param	int		The id of the primary key.
	 * @return	mixed	Object on success, false, on failure.
	 */
	public function getItem($pk = null)
	{
		if ($item	= parent::getItem($pk)) {
			// Convert the metadata field to an array.
			$registry = new Registry();
			$registry->loadString($item->metadata);
			$item->metadata = $registry->toArray();

			// Convert the images field to an array.
			$registry = new Registry();
			$registry->loadString($item->images);
			$item->images = $registry->toArray();

			// Get tags
//			$item->tags	= $this->_getTags((int) $item->id);
			if (!empty($item->id))
			{
				$item->tags = new TagsHelper();
				$item->tags->getTagIds($item->id, 'com_advportfolio.project');
				$item->metadata['tags'] = $item->tags;
			}
		}

		return $item;
	}

	/**
	 * Prepare and sanitize the table prior to saving.
	 */
	protected function prepareTable($table)
	{
		$date	= Factory::getDate();
		$user	= Factory::getApplication()->getIdentity();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= ApplicationHelper::stringURLSafe($table->alias);

		if (empty($table->alias)) {
			$table->alias	= ApplicationHelper::stringURLSafe($table->title);
		}

		if (empty($table->id)) {
			// Set the values

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db		= $this->getDatabase();
				$db->setQuery('SELECT MAX(ordering) FROM #__advportfolio_projects');
				$max	= $db->loadResult();

				$table->ordering	= $max + 1;
			}
		} else {
			// Set the values
			$table->modified	= $date->toSql();
			$table->modified_by	= $user->get('id');
		}
	}

	/**
	 * A protected method to get a set of ordering conditions.
	 *
	 * @param	object	A record object.
	 * @return	array	An array of conditions to add to ordering queries.
	 */
	protected function getReorderConditions($table)
	{
		$condition		= array();
		$condition[]	= 'catid = ' . (int) $table->catid;

		return $condition;
	}
}