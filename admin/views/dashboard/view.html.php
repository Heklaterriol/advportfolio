<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * Dashboard view.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.Portfolio
 */
class AdvPortfolioViewDashboard extends JViewLegacy {
	/**
	 * Display the view.
	 */
	public function display($tpl = null) {
		AdvPortfolioHelper::addSubmenu('dashboard');

		$this->addToolbar();
		$this->sidebar = JHtmlSidebar::render();

		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar() {
		JToolBarHelper::title(JText::_('COM_ADVPORTFOLIO_DASHBOARD_MANAGER'), 'dashboard.png');

		$canDo	= AdvPortfolioHelper::getActions();

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_advportfolio');
		}
	}

	/**
	 * Display quick icon button.
	 * 
	 * @param	string	$link
	 * @param	string	$image
	 * @param	string	$text
	 */
	protected function _quickIcon($link, $image, $text) {
		$button	= array(
			'link'	=> JRoute::_($link),
			'image'	=> 'com_advportfolio/' . $image,
			'text'	=> JText::_($text)
		);

		$this->button	= $button;
		echo $this->loadTemplate('button');
	}
}