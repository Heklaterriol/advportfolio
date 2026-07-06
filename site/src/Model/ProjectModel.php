<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Site\Model;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\ItemModel;
use Joomla\Component\Advportfolio\Site\Helper\AdvportfolioHelper;
use Joomla\Registry\Registry;

/**
 * Project Model.
 *
 * @package		Joomla.Site
 * @subpakage	Skyline.Portfolio
 */
class ProjectModel extends ItemModel
{
	/**
	 * @var		string	Model context string.
	 */
	protected $_context = 'com_advportfolio.project';

	/**
	 * Method to auto-populate the model state.
	 *
	 * Note. Calling getState in this method will result in recursion.
	 */
	protected function populateState()
	{
		$app = Factory::getApplication();

		// Load state from the request.
		$pk = $app->getInput()->getInt('id');
		$this->setState('project.id', $pk);

		$offset = $app->getInput()->getUInt('limitstart');
		$this->setState('list.offset', $offset);

		// Load the parameters.
		$params = $app->getParams();
		$this->setState('params', $params);

		// TODO: Tune these values based on other permissions.
		$this->setState('filter.published', 1);
		$this->setState('filter.archived', 2);
		$this->setState('filter.access', !$params->get('show_noauth'));
	}


	/**
	 * Method to get an object.
	 *
	 * @param	integer	The id of the object to get.
	 * @return	mixed	Object on success, false on failure.
	 */
	public function &getItem($id = null)
	{
		$pk	= (!empty($pk)) ? $pk : (int) $this->getState('project.id');

		if ($this->_item === null) {
			$this->_item = array();
		}

		if (!isset($this->_item[$pk])) {
			try {
				$db		= $this->getDatabase();
				$query	= $db->getQuery(true);

				$query->select(
					$this->getState('item.select', 'a.*')
				);
				$query->from('#__advportfolio_projects AS a');
				$query->where('a.id = ' . (int) $pk);

				// Join over the Categories.
				$query->select('c.title AS category_title, c.alias AS category_alias, c.access AS category_access');
				$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

				// Join on user table.
				$query->select('u.name AS author');
				$query->join('LEFT', '#__users AS u on u.id = a.created_by');

//				// Join over the Tags.
//				$query->select('GROUP_CONCAT(DISTINCT(t.title) ORDER BY t.title) AS tags');
//				$query->join('LEFT', '#__advportfolio_projecttag_map AS m ON a.id = m.project_id');
//				$query->join('LEFT', '#__advportfolio_tags AS t ON m.tag_id = t.id');
//				$query->group('a.id');

				// Filter by published state.
				$published	= $this->getState('filter.published');
				$archived	= $this->getState('filter.archived');

				if (is_numeric($published)) {
					$query->where('(a.state = ' . (int) $published . ' OR a.state = ' . (int) $archived . ')');
				}

				// Filter by access level.
//				if ($access = $this->getState('filter.access')) {
//					$user	= Factory::getApplication()->getIdentity();
//					$groups	= implode(', ', $user->getAuthorisedViewLevels());
//					$query->where('a.access IN (' . $groups . ')');
//					$query->where('c.access IN (' . $groups . ')');
//				}

				$db->setQuery($query);
				$data	= $db->loadObject();

				if (empty($data)) {
					throw new \Exception(Text::_('COM_ADVPORTFOLIO_ERROR_PROJECT_NOT_FOUND'), 404);
				}

				// Check for published state if filter set.
				if (((is_numeric($published)) || (is_numeric($archived))) && (($data->state != $published) && ($data->state != $archived))) {
					throw new \Exception(Text::_('COM_ADVPORTFOLIO_ERROR_PROJECT_NOT_FOUND'), 404);
				}

				// Convert parameter fields to objects
				$registry	= new Registry();
				$params		= clone $this->getState('params');
				$registry->loadString($data->params);
				$params->merge($registry);
				$data->params	= $params;

				$registry	= new Registry();
				$registry->loadString($data->metadata);
				$data->metadata	= $registry;

				$registry	= new Registry();
				$registry->loadString($data->images);
				$data->images	= AdvportfolioHelper::getImages($registry->toObject());

				// Compute view access permissions.
				if ($access = $this->getState('filter.access')) {
					// If the access filter has been set, we already know this user can view.
					$data->params->set('access-view', true);
				} else {
					// If no access filter is set, the layout takes some responsibility for display of limited information.
					$user = Factory::getApplication()->getIdentity();
					$groups = $user->getAuthorisedViewLevels();

					if ($data->catid == 0 || $data->category_access === null) {
						$data->params->set('access-view', in_array($data->access, $groups));
					} else {
						$data->params->set('access-view', in_array($data->access, $groups) && in_array($data->category_access, $groups));
					}
				}

				$this->_item[$pk] = $data;

			} catch(\Exception $e) {
				if ($e->getCode() == 404) {
					// Need to go thru the error handler to allow Redirect to work.
					throw new \Exception($e->getMessage(), 404);
				} else {
					$this->setError($e);
					$this->_item[$pk]	= false;
				}
			}
		}

		return $this->_item[$pk];
	}

	/**
	 * Method to get next project.
	 */
	public function getNextItem($pk = null)
	{
		$pk	= (!empty($pk)) ? $pk : (int) $this->getState('project.id');

		return $this->_getNearItem($pk);
	}

	/**
	 * Method to get prev project.
	 */
	public function getPrevItem($pk = null)
	{
		$pk	= (!empty($pk)) ? $pk : (int) $this->getState('project.id');

		return $this->_getNearItem($pk, false);
	}

	/**
	 * Method to get next/prev project.
	 */
	protected function _getNearItem($id, $next = true)
	{
		$db			= $this->getDatabase();
		$query		= $db->getQuery(true);
		$condition	= $next ? '> ' : '< ';
		$order		= $next ? 'a.id' : 'a.id DESC';

		$query->select('a.id, a.title, a.alias');
		$query->select('CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(\':\', a.id, a.alias) ELSE a.id END AS slug');

		$query->from('#__advportfolio_projects AS a');
		$query->where('a.id ' . $condition . (int) $id);
		$query->order($order);

		// Join over the Categories.
		$query->join('LEFT', '#__categories AS c ON c.id = a.catid');

		// Filter by published state.
		$published	= $this->getState('filter.published');
		$archived	= $this->getState('filter.archived');

		if (is_numeric($published)) {
			$query->where('(a.state = ' . (int) $published . ' OR a.state = ' . (int) $archived . ')');
		}

		// Filter by access level.
		if ($access = $this->getState('filter.access')) {
			$user	= Factory::getApplication()->getIdentity();
			$groups	= implode(', ', $user->getAuthorisedViewLevels());
			$query->where('a.access IN (' . $groups . ')');
			$query->where('c.access IN (' . $groups . ')');
		}

		$db->setQuery($query);
		$data	= $db->loadObject();

		return $data;
	}
}