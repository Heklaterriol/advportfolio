<?php
/**
 * @copyright    Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license        http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\View\Projects;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\HTML\Helpers\Sidebar;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;
use Joomla\CMS\Toolbar\Toolbar;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\Component\Advportfolio\Administrator\Helper\AdvportfolioHelper;

/**
 * View class for a list of Projects.
 *
 * @package        Joomla.Administrator
 * @subpakage    Skyline.Portfolio
 */
class HtmlView extends BaseHtmlView
{
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view.
	 */
	public function display($tpl = null)
	{
		$this->state = $this->get('State');
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');

		AdvportfolioHelper::addSubmenu('projects');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new \Exception(implode("\n", $errors), 500);
		}

		$this->addToolbar();
		$this->sidebar = Sidebar::render();
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 */
	protected function addToolbar()
	{
		$state = $this->get('State');
		$canDo = AdvportfolioHelper::getActions($state->get('filter.category_id'));
		$user = Factory::getApplication()->getIdentity();
		$bar = Toolbar::getInstance('toolbar');

		ToolbarHelper::title(Text::_('COM_ADVPORTFOLIO_PROJECTS_MANAGER'), 'projects.png');

		if (count($user->getAuthorisedCategories('com_advportfolio', 'core.create'))) {
			ToolbarHelper::addNew('project.add');
		}

		if ($canDo->get('core.edit')) {
			ToolbarHelper::editList('project.edit');
		}

		if ($canDo->get('core.edit.state')) {
			ToolbarHelper::publish('projects.publish', 'JTOOLBAR_PUBLISH', true);
			ToolbarHelper::unpublish('projects.unpublish', 'JTOOLBAR_UNPUBLISH', true);

			ToolbarHelper::archiveList('projects.archive');
			ToolbarHelper::checkin('projects.checkin');
		}

		if ($state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			ToolbarHelper::deleteList('', 'projects.delete', 'JTOOLBAR_EMPTY_TRASH');
		} else if ($canDo->get('core.edit.state')) {
			ToolbarHelper::trash('projects.trash');
		}


		// Add a batch button
		if ($canDo->get('core.edit')) {
			HTMLHelper::_('bootstrap.modal', 'collapseModal');
			$title = Text::_('JTOOLBAR_BATCH');
			$dhtml = "<button data-toggle=\"modal\" data-target=\"#collapseModal\" class=\"btn btn-small\">
						<i class=\"icon-checkbox-partial\" title=\"$title\"></i>
						$title</button>";
			$bar->appendButton('Custom', $dhtml, 'batch');
		}

		if ($canDo->get('core.admin')) {
			ToolbarHelper::preferences('com_advportfolio');
		}

		Sidebar::setAction('index.php?option=com_advportfolio&view=projects');

		Sidebar::addFilter(
			Text::_('JOPTION_SELECT_PUBLISHED'),
			'filter_state',
			HTMLHelper::_('select.options', HTMLHelper::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.state'), true)
		);

		Sidebar::addFilter(
			Text::_('JOPTION_SELECT_CATEGORY'),
			'filter_category_id',
			HTMLHelper::_('select.options', HTMLHelper::_('category.options', 'com_advportfolio'), 'value', 'text', $this->state->get('filter.category_id'))
		);

		Sidebar::addFilter(
			Text::_('JOPTION_SELECT_ACCESS'),
			'filter_access',
			HTMLHelper::_('select.options', HTMLHelper::_('access.assetgroups'), 'value', 'text', $this->state->get('filter.access'))
		);

		Sidebar::addFilter(
			Text::_('JOPTION_SELECT_LANGUAGE'),
			'filter_language',
			HTMLHelper::_('select.options', HTMLHelper::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'))
		);
		Sidebar::addFilter(
			'-' . Text::_('JSELECT') . ' ' . Text::_('JTAG') . '-',
			'filter_tag',
			HTMLHelper::_('select.options', HTMLHelper::_('tag.options', true, true), 'value', 'text', $this->state->get('filter.tag'))
		);
	}

	/**
	 * Returns an array of fields the table can be sorted by
	 *
	 * @return  array  Array containing the field name to sort by as the key and display text as value
	 */
	protected function getSortFields()
	{
		return array(
			'a.ordering' => Text::_('JGRID_HEADING_ORDERING'),
			'a.state' => Text::_('JSTATUS'),
			'a.title' => Text::_('JGLOBAL_TITLE'),
			'a.access' => Text::_('JGRID_HEADING_ACCESS'),
			'a.language' => Text::_('JGRID_HEADING_LANGUAGE'),
			'a.id' => Text::_('JGRID_HEADING_ID')
		);
	}
}