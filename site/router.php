<?php
/**
 * @package     AdvPortfolio
 * @subpackage  Site
 * @copyright   Copyright (C) 2026 Hekla Terriol. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Categories\Categories;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Router\Router;
use Joomla\CMS\Uri\Uri;

class AdvPortfolioRouter extends Router
{
	public function build(&$query)
	{
		$segments = [];
		$app = Factory::getApplication();
		$menu = $app->getMenu();
		$params = ComponentHelper::getParams('com_advportfolio');
		$advanced = $params->get('sef_advanced_link', 0);
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
			if ($view != 'form') {
				unset($query['view']);
			}
		}
		if (isset($query['view']) && ($mView == $query['view']) && (isset($query['id'])) && ($mId == (int) $query['id'])) {
			unset($query['view']);
			unset($query['catid']);
			unset($query['id']);
			return $segments;
		}
		if (isset($view) && ($view == 'category' || $view == 'project')) {
			if ($mId != (int) $query['id'] || $mView != $view) {
				if ($view == 'project' && isset($query['catid'])) {
					$catid = $query['catid'];
				} elseif (isset($query['id'])) {
					$catid = $query['id'];
				}
				$menuCatid = $mId;
				$categories = Categories::getInstance(['extension' => 'com_advportfolio']);
				$category = $categories->get($catid);
				if ($category) {
					$path = $category->getPath();
					$path = array_reverse($path);
					$array = [];
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

	public function parse(&$uri, $setVars = false)
	{
		$segments = $uri->getPath();
		$segments = array_filter(explode('/', $segments));
		$total = count($segments);
		$vars = [];
		for ($i = 0; $i < $total; $i++) {
			$segments[$i] = preg_replace('/-/', ':', $segments[$i], 1);
		}
		$app = Factory::getApplication();
		$menu = $app->getMenu();
		$item = $menu->getActive();
		$params = ComponentHelper::getParams('com_advportfolio');
		$advanced = $params->get('sef_advanced_link', 0);
		$count = count($segments);
		if (!isset($item)) {
			$vars['view'] = $segments[0];
			$vars['id'] = $segments[$count - 1];
			return $vars;
		}
		$id = (isset($item->query['id']) && $item->query['id'] > 1) ? $item->query['id'] : 'root';
		$category = Categories::getInstance(['extension' => 'com_advportfolio'])->get($id);
		$categories = ($category) ? $category->getChildren() : [];
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
						->where($db->quoteName('alias') . ' = ' . $db->quote(str_replace(':', '-', $segment)));
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
}
function AdvPortfolioBuildRoute(&$query) {
	$router = new AdvPortfolioRouter;
	return $router->build($query);
}
function AdvPortfolioParseRoute($uri) {
	$router = new AdvPortfolioRouter;
	return $router->parse($uri);
}