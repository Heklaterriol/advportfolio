<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

class JHtmlAdvPortfolio {
	static $loaded;

	/**
	 * Method to load fancybox.
	 */
	static function modal() {
		if (isset(self::$loaded[__METHOD__])) {
			return;
		}

		JHtml::_('script', 'com_advportfolio/jquery.fancybox.js', false, true);
		JHtml::_('stylesheet', 'com_advportfolio/jquery.fancybox.css', false, true);

		$document	= JFactory::getDocument();
		$document->addScriptDeclaration("
			(function($) {
				$('.sl_modal').fancybox();
			})(jQuery);
		");

		self::$loaded[__METHOD__]	= true;
	}


	public static function image($image, $width = null, $height = null, $alt = '', $quality = 100, $singleQuote = false, $attributes = '') {
		require_once JPATH_ADMINISTRATOR . '/components/com_advportfolio/helpers/imagelib.php';

		$folder			= JPATH_ROOT . '/images/advportfolio/images';
		$cacheFolder	= JPATH_ROOT . '/images/advportfolio/imagecache';
		$cachePatth		= JUri::root(true) . '/images/advportfolio/imagecache';

		if (!JFile::exists($folder . '/' . $image)) {
			return false;
		}

		// build cache file.
		$cache	= AdvPortfolioImageLib::resize($folder . '/' . $image, $cacheFolder, $width, $height, $quality);

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