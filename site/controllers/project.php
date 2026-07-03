<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;

/**
 * Controller for a single Project
 *
 * @package		Joomla.Site
 * @subpackage	AdvPortfolio
 */
class AdvPortfolioControllerProject extends BaseController
{
	/**
	 * Method to display a single project
	 *
	 * @param   boolean  $cachable  If true, the view output will be cached
	 * @param   array    $urlparams  An array of safe url parameters and their variable types
	 *
	 * @return  BaseController  This object to support chaining.
	 */
	public function display($cachable = false, $urlparams = [])
	{
		$app = Factory::getApplication();
		$input = $app->getInput();
		$view = $input->getCmd('view', 'project');
		$layout = $input->getCmd('layout', 'default');
		$id = $input->getInt('id');

		// Set the view and layout
		$input->set('view', $view);
		$input->set('layout', $layout);
		$input->set('id', $id);

		return parent::display($cachable, $urlparams);
	}
}