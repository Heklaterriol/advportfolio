<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Project Model.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.Portfolio
 */
class AdvPortfolioModelProject extends JModelAdmin {
	/** @var string		The prefix to use with controller messages. */
	protected $text_prefix	= 'COM_ADVPORTFOLIO_PROJECTS';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param	object	A record object.
	 * @return	bool	True if allowed to delete the record. Defaults to the permission set in the component.
	 */
	protected function canDelete($record) {
		if (!empty($record->id)) {
			if ($record->state != -2) {
				return;
			}

			$user = JFactory::getUser();

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
	protected function canEditState($record) {
		$user	= JFactory::getUser();

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
	 * @return	JTable	A database object
	 */
	public function getTable($type = 'Project', $prefix = 'AdvPortfolioTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get the record form.
	 *
	 * @param	array	$data		An optional array of data for the form to interogate.
	 * @param	boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return	JForm				A JForm object on success, false on failure.
	 */
	public function getForm($data = array(), $loadData = true) {
		// Initialize variables.
		$app	= JFactory::getApplication();

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
	protected function loadFormData() {
		$app = JFactory::getApplication();

		// Check the session for previously entered form data.
		$data	= JFactory::getApplication()->getUserState('com_advportfolio.edit.project.data', array());

		if (empty($data)) {
			$data	= $this->getItem();

			// Prime some default values.
			if ($this->getState('project.id') == 0) {
				$app	= JFactory::getApplication();
				$data->set('catid', $app->input->getInt('catid', $app->getUserState('com_advportfolio.projects.filter.category_id')));
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
	public function getItem($pk = null) {
		if ($item	= parent::getItem($pk)) {
			// Convert the metadata field to an array.
			$registry = new JRegistry();
			$registry->loadString($item->metadata);
			$item->metadata = $registry->toArray();

			// Convert the images field to an array.
			$registry = new JRegistry();
			$registry->loadString($item->images);
			$item->images = $registry->toArray();

			// Get tags
//			$item->tags	= $this->_getTags((int) $item->id);
			if (!empty($item->id))
			{
				$item->tags = new JHelperTags;
				$item->tags->getTagIds($item->id, 'com_advportfolio.project');
				$item->metadata['tags'] = $item->tags;
			}
		}

		return $item;
	}

	/**
	 * Prepare and sanitize the table prior to saving.
	 */
	protected function prepareTable($table) {
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();

		$table->title		= htmlspecialchars_decode($table->title, ENT_QUOTES);
		$table->alias		= JApplication::stringURLSafe($table->alias);

		if (empty($table->alias)) {
			$table->alias	= JApplication::stringURLSafe($table->title);
		}

		if (empty($table->id)) {
			// Set the values

			// Set ordering to the last item if not set
			if (empty($table->ordering)) {
				$db		= JFactory::getDbo();
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
	protected function getReorderConditions($table) {
		$condition		= array();
		$condition[]	= 'catid = ' . (int) $table->catid;

		return $condition;
	}

	/**
	 * Get tag title value.
	 * @param	int $project_id
	 * @return	array
	 */
//	private function _getTags($project_id = null) {
//		$model	= AdvPortfolioFactory::getModel('tags', array('ignore_request' => true));
//
//		$model->setState('filter.project_id', $project_id);
//		$model->setState('list.limit', 9999);
//		$model->setState('list.limitstart', 0);
//		$model->setState('list.ordering', 'title');
//		$model->setState('list.direction', 'asc');
//
//		$items	= $model->getItems();
//		$values	= array();
//
//		if (is_array($items) > 0) {
//			foreach ($items as $item) {
//				$values[] = $item->title;
//			}
//		}
//
//		return $values;
//	}

	/**
	 * Method to save project tag map.
	 */
//	public function saveTagMap($tag_id) {
//		$project_id		= (int) $this->getState('project.id');
//		$tag_id			= (int) $tag_id;
//
//		if ($project_id && $tag_id) {
//			// check if map doesn't exist
//			$db		= $this->getDbo();
//			$query	= $db->getQuery(true);
//			$query->select('COUNT(*)')
//				->from('#__advportfolio_projecttag_map')
//				->where('project_id = ' . $project_id . ' AND tag_id = ' . $tag_id)
//			;
//
//			$db->setQuery($query);
//
//			if (!$db->loadResult()) {
//				$query	= $db->getQuery(true);
//				$query->insert('#__advportfolio_projecttag_map (project_id, tag_id)')
//					->values($project_id . ', ' . $tag_id)
//				;
//				$db->setQuery($query);
//				$db->query();
//			}
//		}
//	}

	/**
	 * Method to delete project tag map
	 */
//	public function deleteTagMap($ids) {
//		$project_id		= (int) $this->getState('project.id');
//
//		if ($project_id) {
//			$db		= $this->getDbo();
//			$query	= $db->getQuery(true);
//			$query->delete('#__advportfolio_projecttag_map')
//				->where('project_id = ' . $project_id)
//			;
//
//			if (count($ids)) {
//				$query->where('tag_id NOT IN (' . implode(', ', $ids) . ')');
//			}
//
//			$db->setQuery($query);
//			$db->query();
//		}
//	}
}