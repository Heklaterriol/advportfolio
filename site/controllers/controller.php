<?php
/**
 * @package     AdvPortfolio
 * @subpackage  Site.Controller
 * @copyright   Copyright (C) 2026 Hekla Terriol. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;

/**
 * Advanced Portfolio Component Controller.
 *
 * @package     AdvPortfolio.Site
 * @subpackage  Controller
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