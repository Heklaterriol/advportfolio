<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSMVCModelBaseDatabaseModel;
use JoomlaCMSHelperStringHelper;

class AdvPortfolioHelper
{
	public static function getModel($type, $config = [])
	{
		return BaseDatabaseModel::getInstance($type, 'AdvPortfolioModel', $config);
	}

	public static function getImages($value)
	{
		$items = [];
		if (is_array($value)) {
			if (isset($value['image']) && count($value['image'])) {
				for ($i = 0, $n = count($value['image']); $i < $n; $i++) {
					$item = new stdClass();
					$item->image = $value['image'][$i];
					$item->title = $value['title'][$i];
					if ($item->image) { $items[] = $item; }
				}
			}
		} elseif (is_object($value)) {
			if (isset($value->image) && count($value->image)) {
				for ($i = 0, $n = count($value->image); $i < $n; $i++) {
					$item = new stdClass();
					$item->image = $value->image[$i];
					$item->title = $value->title[$i];
					if ($item->image) { $items[] = $item; }
				}
			}
		}
		return $items;
	}

	public static function renderImage($image, $width = null, $alt = null, $title = null)
	{
		if (empty($image)) { return ''; }
		$imagePath = $image;
		$attributes = [];
		if ($width) { $attributes['width'] = (int) $width; }
		if ($alt) { $attributes['alt'] = $alt; } else { $attributes['alt'] = ''; }
		if ($title) { $attributes['title'] = $title; }
		$html = '<img src="' . htmlspecialchars($imagePath, ENT_QUOTES, 'UTF-8') . '"';
		foreach ($attributes as $key => $value) {
			$html .= ' ' . $key . '="' . htmlspecialchars($value, ENT_QUOTES, 'UTF-8') . '"';
		}
		$html .= ' />';
		return $html;
	}

	public static function poweredBy()
	{
		return '<div style="text-align: center; padding-top: 20px;"><a style="display: inline; visibility: visible; text-decoration: none;" target="_blank" rel="nofollow noopener noreferrer" href="http://extstore.com">Powered by ExtStore Advanced Portfolio</a></div>';
	}
}