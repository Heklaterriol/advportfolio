<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSMVCControllerBaseController;

/**
 * Advanced Portfolio Component Admin Controller.
 *
 * @package		Joomla.Administrator
 * @subpackage	AdvPortfolio
 */
class AdvPortfolioController extends BaseController
{
	public function display($cachable = false, $urlparams = [])
	{
		return parent::display($cachable, $urlparams);
	}
}