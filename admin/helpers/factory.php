<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSFactory;
use JoomlaCMSLanguageText;

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