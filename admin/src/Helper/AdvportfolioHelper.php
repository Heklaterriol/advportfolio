<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Object\CMSObject;
use Joomla\CMS\Table\Table;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Registry\Registry;

/**
 * Advanced Portfolio Helper.
 *
 * @package		Joomla.Administrator
 * @subpackage	Skyline.Portfolio
 */
class AdvportfolioHelper
{
	/**
	 * Configure the Linkbar.
	 *
	 * @param	string	The name of the active view.
	 */
	public static function addSubmenu($vName = '')
	{
		Sidebar::addEntry(
			Text::_('COM_ADVPORTFOLIO_SUBMENU_DASHBOARD'),
			'index.php?option=com_advportfolio&view=dashboard',
			$vName == 'dashboard'
		);

		Sidebar::addEntry(
			Text::_('COM_ADVPORTFOLIO_SUBMENU_PROJECTS'),
			'index.php?option=com_advportfolio&view=projects',
			$vName == 'projects'
		);

		Sidebar::addEntry(
			Text::_('COM_ADVPORTFOLIO_SUBMENU_CATEGORIES'),
			'index.php?option=com_categories&extension=com_advportfolio',
			$vName == 'categories'
		);

//		Sidebar::addEntry(
//			Text::_('COM_ADVPORTFOLIO_SUBMENU_TAGS'),
//			'index.php?option=com_advportfolio&view=tags',
//			$vName == 'tags'
//		);

		if ($vName == 'categories') {
			ToolbarHelper::title(
				Text::sprintf('COM_CATEGORIES_CATEGORIES_TITLE', Text::_('COM_ADVPORTFOLIO')),
				'advportfolio-categories'
			);
		}
	}

	/**
	 * Gets a list of the actions that can be performed.
	 *
	 * @param	int		The category ID.
	 * @return	CMSObject
	 */
	public static function getActions($categoryId = 0)
	{
		$user	= Factory::getApplication()->getIdentity();
		$result	= new CMSObject();

		if (empty($categoryId)) {
			$assetName	= 'com_advportfolio';
		} else {
			$assetName	= 'com_advportfolio.category.' . (int) $categoryId;
		}

		$actions	= array(
			'core.admin', 'core.manage', 'core.create', 'core.edit', 'core.edit.own', 'core.edit.state', 'core.delete',
		);

		foreach ($actions as $action) {
			$result->set($action, $user->authorise($action, $assetName));
		}

		return $result;
	}

	/**
	 * Format file size to display
	 * @param	int	$size
	 * @return	string
	 */
	public static function fileSize($size)
	{
		if ($size < 1024) {
			return Text::sprintf('%d bytes', $size);
		} else {
			if ($size > 1024 && $size < 1048576) {
				return Text::sprintf('%01.2f KB', $size / 1024.0);
			} else {
				return Text::sprintf('%01.2f MB', $size / 1048576.0);
			}
		}
	}

	/**
	 * Method to get value of images field.
	 *
	 * @params	string	$value	Raw data string.
	 * @return	array	Array of images.
	 */
	public static function getImages($value)
	{
		$items	= array();

		if (is_array($value)) {
			if (isset($value['image']) && count($value['image'])) {
				for ($i = 0, $n = count($value['image']); $i < $n; $i++) {
					$item			= new \stdClass();
					$item->image	= $value['image'][$i];
					$item->title	= $value['title'][$i];

					if ($item->image) {
						$items[]		= $item;
					}
				}
			}
		} else if (is_object($value)) {
			if (isset($value->image) && count($value->image)) {
				for ($i = 0, $n = count($value->image); $i < $n; $i++) {
					$item			= new \stdClass();
					$item->image	= $value->image[$i];
					$item->title	= $value->title[$i];

					if ($item->image) {
						$items[]		= $item;
					}
				}
			}
		}

		return $items;
	}

	/**
	 * Get credits footer string.
	 * @return	string
	 */
	public static function getFooter()
	{
		return '<p class="sl_copyright"><span class="sl_title">Advanced Portfolio - Version ' . self::getVersion() . '</span> Copyright &copy; 2013 by <strong>Skyline Technology Ltd - <a href="http://extstore.com" target="_blank">http://extstore.com</a></strong></p>';
}
	/**
	 * Get current version of component.
	 */
	public static function getVersion()
	{
		$table		= Table::getInstance('Extension');
		$table->load(array('name' => 'com_advportfolio'));
		$registry	= new Registry($table->manifest_cache);

		return $registry->get('version');
	}
}