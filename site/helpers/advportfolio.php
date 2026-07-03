<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Advanced Portfolio Helper.
 *
 * @package		Joomla.Site
 * @subpackage	Skyline.Portfolio
 */
class AdvPortfolioHelper {

	/**
	 * Get model of component
	 */
	public static function getModel($type, $config = array()) {
		return JModelLegacy::getInstance($type, 'AdvPortfolioModel', $config);
	}

	/**
	 * Method to get value of images field.
	 *
	 * @params	string	$value	Raw data string.
	 * @return	array	Array of images.
	 */
	public static function getImages($value) {
		$items	= array();

		if (is_array($value)) {
			if (isset($value['image']) && count($value['image'])) {
				for ($i = 0, $n = count($value['image']); $i < $n; $i++) {
					$item			= new stdClass();
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
					$item			= new stdClass();
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

	public static function poweredBy() {
		return '<div style="text-align: center; padding-top: 20px;"><a style="display: inline; visibility: visible; text-decoration: none;" target="_blank" rel="follow" href="http://extstore.com">Powered by ExtStore Advanced Portfolio</a></div>';
	}

}