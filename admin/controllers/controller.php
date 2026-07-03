<?php
/**
 * @package     AdvPortfolio
 * @subpackage  Admin.Controller
 * @copyright   Copyright (C) 2026 Hekla Terriol. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Advanced Portfolio Component Admin Controller.
 *
 * @package     AdvPortfolio.Admin
 * @subpackage  Controller
 */
class AdvPortfolioController extends BaseController
{
	public function display($cachable = false, $urlparams = [])
	{
		return parent::display($cachable, $urlparams);
	}
}