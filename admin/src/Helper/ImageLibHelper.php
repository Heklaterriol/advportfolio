<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\Helper;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\Filesystem\File;
use Joomla\Filesystem\Folder;

/**
 * Image Library.
 * @package		Joomla.Administrator
 * @subpakage	Skyline.Portfolio
 */
class ImageLibHelper
{
	/**
	 * Get version of GD library.
	 * @access	public
	 * @since	1.0
	 */
	public static function getGDVersion($user_ver = 0)
	{
		if (!extension_loaded('gd')) {
			return;
		}

		static $gd_ver	= 0;

		// just accept the specified setting if it's 1.
		if ($user_ver == 1) {
			$gd_ver = 1;
			return 1;
		}

		// use static variable if function was cancelled previously.
		if ($user_ver != 2 && $gd_ver > 0) {
			return $gd_ver;
		}

		// use the gd_info() function if posible.
		if (function_exists('gd_info')) {
			$ver_info = gd_info();
			$match = null;
			preg_match('/\d/', $ver_info['GD Version'], $match);
			$gd_ver = $match[0];

			return $match[0];
		}

		// if phpinfo() is disabled use a specified / fail-safe choice...
		if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
			if ($user_ver == 2) {
				$gd_ver = 2;
				return 2;
			} else {
				$gd_ver = 1;
				return 1;
			}
		}
		// ...otherwise use phpinfo().
		ob_start();
		phpinfo(8);
		$info = ob_get_contents();
		ob_end_clean();
		$info = stristr($info, 'gd version');
		$match = null;
		preg_match('/\d/', $info, $match);
		$gd_ver = $match[0];

		return $match[0];
	}

	/**
	 * Get real image width and height to resize.
	 * @access	public
	 * @since	1.0
	 */
	public static function getSize($image, $width, $height)
	{
		$info = @getimagesize($image);	// width = info[0], height = info[1]

		if ($info[0] < $width && $info[1] < $height) {
			return array($info[0], $info[1]);
		}

		if ($info[0] / $width > $info[1] / $height) {
			$percentage = $width / $info[0];
		} else {
			$percentage = $height / $info[1];
		}

		return array(round($info[0] * $percentage), round($info[1] * $percentage));
	}

	/**
	 * Get real size.
	 * @access	public
	 * @since	1.0
	 */
	public static function imageResize($width, $height, $max_width, $max_height)
	{
		if ($width < $max_width && $height < $max_height) {
			return array($width, $height);
		}

		if ($width / $max_width > $height / $max_height) {
			$percentage = $max_width / $width;
		} else {
			$percentage = $max_height / $height;
		}

		return array(round($width * $percentage), round($height * $percentage));
	}

	/**
	 * Get image filename to upload.
	 * @access	public
	 * @since	1.0
	 */
	public static function sanitize($base_dir, $filename)
	{
		//check for any leading/trailing dots and remove them (trailing shouldn't be possible cause of the getEXT check)
		$filename = preg_replace("/^[.]*/", '', $filename);
		$filename = preg_replace("/[.]*$/", '', $filename); //shouldn't be necessary, see above

		//we need to save the last dot position cause preg_replace will also replace dots
		$lastdotpos = strrpos($filename, '.');

		//replace invalid characters
		$chars = '[^0-9a-zA-Z()_-]';
		$filename 	= strtolower(preg_replace("/$chars/", '_', $filename));

		//get the parts before and after the dot (assuming we have an extension...check was done before)
		$beforedot	= substr($filename, 0, $lastdotpos);
		$afterdot 	= substr($filename, $lastdotpos + 1);

		//make a unique filename for the image and check it is not already taken
		//if it is already taken keep trying till success
		$now = time();

		while (File::exists($base_dir . $beforedot . '_' . $now . '.' . $afterdot)) {
			$now++;
		}

		//create out of the seperated parts the new filename
		$filename = $beforedot . '_' . $now . '.' . $afterdot;

		return $filename;
	}

	/**
	 * Add image subfix.
	 * @access	public
	 * @since	1.0
	 */
	public static function addSubfix($filename, $subfix)
	{
		//check for any leading/trailing dots and remove them (trailing shouldn't be possible cause of the getEXT check)
		$filename = preg_replace("/^[.]*/", '', $filename);
		$filename = preg_replace("/[.]*$/", '', $filename); //shouldn't be necessary, see above

		//we need to save