<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\View\Project;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Component\Advportfolio\Administrator\Helper\AdvportfolioHelper;

/**
 * View to edit a Project.
 *
 * @package		Joomla.Administrator
 * @subpakage	Skyline.Portfolio
 */
class HtmlView extends BaseHtmlView
{
	protected $state;
	protected $item;
	protected $form;

	/**
	 * Display the view.
	 */
	public function display($tpl = null)
	{
		$this->state	= $this->get('State');
		$this->item		= $this->get('Item');
		$this->form		= $this->get('Form');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new \Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		Factory::getApplication()->getInput()->set('hidemainmenu', true);

		$user		= Factory::getApplication()->getIdentity();
		$isNew		= $this->item->id == 0;
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		$canDo		= AdvportfolioHelper::getActions($this->state->get('filter.category_id'), $this->item->id);

		ToolbarHelper::title(Text::_('COM_ADVPORTFOLIO_PROJECT_MANAGER'), 'project.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit') || (count($user->getAuthorisedCategories('com_advportfolio', 'core.create'))))) {
			ToolbarHelper::apply('project.apply');
			ToolbarHelper::save('project.save');
		}

		if (!$checkedOut && (count($user->getAuthorisedCategories('com_advportfolio', 'core.create')))) {
			ToolbarHelper::save2new('project.save2new');
		}

		// If an existing item, can save to a copy.
		if (!$isNew && (count($user->getAuthorisedCategories('com_advportfolio', 'core.create')))) {
			ToolbarHelper::save2copy('project.save2copy');
		}

		if (empty($this->item->id)) {
			ToolbarHelper::cancel('project.cancel');
		} else {
			ToolbarHelper::cancel('project.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}