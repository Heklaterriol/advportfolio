<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Site\Service;

defined('_JEXEC') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Component\Router\RouterBase;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Multilanguage;

/**
 * Routing class from com_advportfolio
 *
 * @package     Joomla.Site
 * @subpackage  com_advportfolio
 */
class Router extends RouterBase
{
	protected static $lookup = array();

	/**
	 * Build the route for the com_advportfolio component
	 *
	 * @param   array  &$query  An array of URL arguments
	 *
	 * @return  array  The URL arguments to use to assemble the subsequent URL.
	 */
	public function build(&$query)
	{
		$segments = array();

		// Get a menu item based on Itemid or currently active
		$app = Factory::getApplication();
		$menu = $app->getMenu();
		$params = ComponentHelper::getParams('com_advportfolio');
		$advanced = $params->get('sef_advanced_link', 0);

		// We need a menu item.  Either the one specified in the query, or the current active one if none specified
		if (empty($query['Itemid'])) {
			$menuItem = $menu->getActive();
		} else {
			$menuItem = $menu->getItem($query['Itemid']);
		}

		$mView = (empty($menuItem->query['view'])) ? null : $menuItem->query['view'];
		$mId = (empty($menuItem->query['id'])) ? null : $menuItem->query['id'];

		if (isset($query['view'])) {
			$view = $query['view'];

			if (empty($query['Itemid']) || empty($menuItem) || $menuItem->component != 'com_advportfolio') {
				$segments[] = $query['view'];
			}

			// We need to keep the view for forms since they never have their own menu item
			if ($view != 'form') {
				unset($query['view']);
			}
		}

		// Are we dealing with an project that is attached to a menu item?
		if (isset($query['view']) && ($mView == $query['view']) and (isset($query['id'])) and ($mId == (int) $query['id'])) {
			unset($query['view']);
			unset($query['catid']);
			unset($query['id']);

			return $segments;
		}

		if (isset($view) and ($view == 'category' or $view == 'project')) {
			if ($mId != (int) $query['id'] || $mView != $view) {
				if ($view == 'project' && isset($query['catid'])) {
					$catid = $query['catid'];
				}
				elseif (isset($query['id'])) {
					$catid = $query['id'];
				}

				$menuCatid = $mId;
				$categories = Factory::getApplication()->bootComponent('com_advportfolio')->getCategory();
				$category = $categories->get($catid);

				if ($category) {
					// TODO Throw error that the category either not exists or is unpublished
					$path = $category->getPath();
					$path = array_reverse($path);

					$array = array();

					foreach ($path as $id) {
						if ((int) $id == (int) $menuCatid) {
							break;
						}

						if ($advanced) {
							list($tmp, $id) = explode(':', $id, 2);
						}

						$array[] = $id;
					}

					$segments = array_merge($segments, array_reverse($array));
				}

				if ($view == 'project') {
					if ($advanced) {
						list($tmp, $id) = explode(':', $query['id'], 2);
					} else {
						$id = $query['id'];
					}

					$segments[] = $id;
				}
			}

			unset($query['id']);
			unset($query['catid']);
		}

		if (isset($query['layout'])) {
			if (!empty($query['Itemid']) && isset($menuItem->query['layout'])) {
				if ($query['layout'] == $menuItem->query['layout']) {
					unset($query['layout']);
				}
			} else {
				if ($query['layout'] == 'default') {
					unset($query['layout']);
				}
			}
		}

		$total = count($segments);

		for ($i = 0; $i < $total; $i++) {
			$segments[$i] = str_replace(':', '-', $segments[$i]);
		}

		return $segments;
	}

	/**
	 * Parse the segments of a URL.
	 *
	 * @param   array  &$segments  The segments of the URL to parse.
	 *
	 * @return  array  The URL attributes to be used by the application.
	 */
	public function parse(&$segments)
	{
		$total = count($segments);
		$vars = array();

		for ($i = 0; $i < $total; $i++) {
			$segments[$i] = preg_replace('/-/', ':', $segments[$i], 1);
		}

		// Get the active menu item.
		$app = Factory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getActive();
		$params = ComponentHelper::getParams('com_advportfolio');
		$advanced = $params->get('sef_advanced_link', 0);

		// Count route segments
		$count = count($segments);

		// Standard routing for projects.
		if (!isset($item)) {
			$vars['view'] = $segments[0];
			$vars['id'] = $segments[$count - 1];

			return $vars;
		}

		// From the categories view, we can only jump to a category.
		$id = (isset($item->query['id']) && $item->query['id'] > 1) ? $item->query['id'] : 'root';

		$category = Factory::getApplication()->bootComponent('com_advportfolio')->getCategory()->get($id);

		$categories = ($category) ? $category->getChildren() : array();
		$vars['catid'] = $id;
		$vars['id'] = $id;
		$found = 0;

		foreach ($segments as $segment) {
			foreach ($categories as $category) {
				if (($category->slug == $segment) || ($advanced && $category->alias == str_replace(':', '-', $segment))) {
					$vars['id'] = $category->id;
					$vars['view'] = 'category';
					$categories = $category->getChildren();
					$found = 1;

					break;
				}
			}

			if ($found == 0) {
				if ($advanced) {
					$db = Factory::getContainer()->get('DatabaseDriver');
					$query = $db->getQuery(true)
						->select($db->quoteName('id'))
						->from('#__advportfolio_projects')
						->where($db->quoteName('catid') . ' = ' . (int) $vars['catid'])
						->where($db->quoteName('alias') . ' = ' . $db->quote($db->quote(str_replace(':', '-', $segment))));
					$db->setQuery($query);
					$id = $db->loadResult();
				} else {
					$id = $segment;
				}

				$vars['id'] = $id;
				$vars['view'] = 'project';

				break;
			}

			$found = 0;
		}

		return $vars;
	}

	/**
	 * @param    int    The route of the project item
	 */
	public static function getProjectRoute($id, $language = 0)
	{
		$needles = array(
			'project' => array((int) $id)
		);
		//Create the link
		$link = 'index.php?option=com_advportfolio&view=project&id=' . $id;

		if ($language && $language != "