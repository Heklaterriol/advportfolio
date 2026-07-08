<?php
/**
 * Advanced Portfolio HTML Helper.
 *
 * NOTE: This class is intentionally used from both the Administrator and Site
 * side of the component. This mirrors the original Joomla 3 architecture,
 * where site/advportfolio.php registered this class via
 * JHtml::addIncludePath(JPATH_COMPONENT_ADMINISTRATOR . '/helpers/html')
 * for cross-client access. No architectural change was made during the
 * Joomla 6 migration; direct namespaced calls simply replace the old
 * JHtml::_('advportfolio.*') dynamic dispatch mechanism, which no longer
 * exists in Joomla 6.
 *
 * @package		Joomla.Administrator
 * @subpackage	Skyline.Portfolio
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\Filesystem\File;

class HtmlAdvportfolioHelper
{
	static $loaded;

	/**
	 * Method to load fancybox.
	 */
	static function modal()
	{
		if (isset(self::$loaded[__METHOD__])) {
			return;
		}

		HTMLHelper::_('script', 'com_advportfolio/jquery.fancybox.js', false, true);
		HTMLHelper::_('stylesheet', 'com_advportfolio/jquery.fancybox.css', false, true);

		$document	= Factory::getApplication()->getDocument();
		$document->addScriptDeclaration("
			(function($) {
				$('.sl_modal').fancybox();
			})(jQuery);
		");

		self::$loaded[__METHOD__]	= true;
	}


	public static function image($image, $width = null, $height = null, $alt = '', $quality = 100, $singleQuote = false, $attributes = '')
	{
		$folder			= JPATH_ROOT . '/images/advportfolio/images';
		$cacheFolder	= JPATH_ROOT . '/images/advportfolio/imagecache';
		$cachePatth		= Uri::root(true) . '/images/advportfolio/imagecache';

		if (!File::exists($folder . '/' . $image)) {
			return false;
		}

		// build cache file.
		$cache	= ImageLibHelper::resize($folder . '/' . $image, $cacheFolder, $width, $height, $quality);

		if (!$cache) {
			return false;
		}

		$cacheImage	= $cachePatth . '/' . $cache;

		if (!$alt) {
			return $cacheImage;
		}

		$img	= '<img src="' . $cacheImage . '" alt="' . $alt . '" ' . $attributes . ' />';

		if ($singleQuote) {
			str_replace('"', '\'', $img);
		}

		return $img;
	}
}