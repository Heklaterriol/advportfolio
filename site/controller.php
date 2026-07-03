<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSMVCControllerBaseController;

/**
 * Advanced Portfolio Component Controller.
 *
 * @package		Joomla.Site
 * @subpackage	AdvPortfolio
 */
class AdvPortfolioController extends BaseController
{
	public function display($cachable = false, $urlparams = [])
	{
		$cachable = true;
		$vName = $this->input->getCmd('view', 'projects');
		$this->input->set('view', $vName);
		return parent::display($cachable, $urlparams);
	}
}