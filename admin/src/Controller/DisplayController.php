<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\Controller;

defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Router\Route;

/**
 * Advanced Portfolio Controller.
 *
 * @package		Joomla.Administrator
 * @subpackage	Skyline.Portfolio
 */
class DisplayController extends BaseController
{
	/**
	 * Method to display a view.
	 *
	 * @param	bool 			$cachable	If true, the view output will be cached.
	 * @param	bool 			$urlparams	An array of safe url parameters and their variable types, for valid values see {@link \Joomla\CMS\Filter\InputFilter::clean()}.
	 * @return	BaseController	This object to support chaining.
	 */
	public function display($cachable = false, $urlparams = false)
	{
		$view		= $this->input->get('view', 'dashboard');
		$this->input->set('view', $view);
		$layout		= $this->input->get('layout', 'default');
		$id			= $this->input->getInt('id');

		// Check for edit form.
		if ($layout == 'edit') {
			switch ($view) {
				case 'project':
					$vName	= 'projects';
					break;
				case 'tag':
					$vName	= 'tags';
					break;
			}

			if (!$this->checkEditId('com_advportfolio.edit.' . $view, $id)) {
				// Somehow the person just went to the form - we don't allow that.
				$this->setError(Text::sprintf('JLIB_APPLICATION_ERROR_UNHELD_ID', $id));
				$this->setMessage($this->getError(), 'error');
				$this->setRedirect(Route::_('index.php?option=com_advportfolio&view=' . $vName, false));

				return false;
			}
		}

		parent::display();

		return $this;
	}
}