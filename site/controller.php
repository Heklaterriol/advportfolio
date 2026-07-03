<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

/**
 * Skyline Technology Ltd Advanced Portfolio Component Controller.
 *
 * @package		Joomla.Site
 * @subpakage	Skyline.Portfolio
 */
class AdvPortfolioController extends JControllerLegacy {

	/**
	 * Method to display a view.
	 *
	 * @param	boolean			If true, the view output will be cached
	 * @param	array			An array of safe url parameters and their variable types, for valid values see {@link JFilterInput::clean()}.
	 *
	 * @return	JController		This object to support chaining.
	 */
	public function display($cachable = false, $urlparams = false) {
		$cachable = true;

		$vName = $this->input->getCmd('view', 'projects');
		$this->input->set('view', $vName);

		return parent::display($cachable, $urlparams);

		return $this;
	}
}