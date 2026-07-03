<?php
/**
 * @package     AdvPortfolio
 * @subpackage  Admin.Helpers
 * @copyright   Copyright (C) 2026 Hekla Terriol. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

class AdvPortfolioFactory
{
	public static function getFooter()
	{
		return '<div style="text-align: center; padding-top: 20px;"><a style="display: inline; visibility: visible; text-decoration: none;" target="_blank" rel="nofollow noopener noreferrer" href="http://extstore.com">Powered by ExtStore Advanced Portfolio</a></div>';
	}

	public static function getHelper($name)
	{
		$className = 'AdvPortfolioHelper' . ucfirst($name);
		if (class_exists($className)) {
			return new $className;
		}
		return null;
	}
}