<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\View\Dashboard;

defined('_JEXEC') or die;

use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Component\Advportfolio\Administrator\Helper\AdvportfolioHelper;

/**
 * Dashboard view.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.Portfolio
 */
class HtmlView extends BaseHtmlView
{
	/**
	 * Display the view.
	 */
	public function display($tpl = null)
	{
		AdvportfolioHelper::addSubmenu('dashboard');

		$this->addToolbar();
		$this->sidebar = Sidebar::render();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		ToolbarHelper::title(Text::_('COM_ADVPORTFOLIO_DASHBOARD_MANAGER'), 'dashboard.png');

		$canDo	= AdvportfolioHelper::getActions();

		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences('com_advportfolio');
		}
	}

	/**
	 * Display quick icon button.
	 *
	 * @param	string	$link
	 * @param	string	$image
	 * @param	string	$text
	 */
	protected function _quickIcon($link, $image, $text)
	{
		$button	= array(
			'link'	=> Route::_($link),
			'image'	=> 'com_advportfolio/' . $image,
			'text'	=> Text::_($text)
		);

		$this->button	= $button;
		echo $this->loadTemplate('button');
	}
}