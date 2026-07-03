<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Skyline Portfolio Factory Class.
 * @package		Joomla.Administrator
 * @subpakage	Skyline.Portfolio
 */
class AdvPortfolioFactory {
	/**
	 * Get credits footer string.
	 * @return	string
	 */
	public static function getFooter() {
		return '<p class="sl_copyright"><span class="sl_title">Advanced Portfolio - Version ' . self::getVersion() . '</span> Copyright &copy; 2013 by <strong>Skyline Technology Ltd - <a href="http://extstore.com" target="_blank">http://extstore.com</a></strong></p>';
	}

	/**
	 * Get current version of component.
	 */
	public static function getVersion() {
		$table		= JTable::getInstance('Extension');
		$table->load(array('name' => 'com_advportfolio'));
		$registry	= new JRegistry($table->manifest_cache);

		return $registry->get('version');
	}

	/**
	 * Get model of component
	 */
	public static function getModel($type, $config = array()) {
		return JModelLegacy::getInstance($type, 'AdvPortfolioModel', $config);
	}
}