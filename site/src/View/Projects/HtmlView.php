<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Site\View\Projects;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

/**
 * View class for a list of Projects.
 *
 * @package		Joomla.Site
 * @subpakage	Skyline.Portfolio
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
		$app		= Factory::getApplication();

		// Get some data from the models
		$state		= $this->get('State');
		$items		= $this->get('Items');
		$pagination	= $this->get('Pagination');
		$params		= $state->params;

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new \Exception(implode("\n", $errors), 500);
		}

		// Compute all tags of list projects
		$tags	= array();

		foreach ($items as $item) {
			$tags	= array_merge($tags, $item->tags);
		}

		$tags	= array_unique($tags);
		asort($tags);

		$this->state			= $state;
		$this->items			= $items;
		$this->pagination		= $pagination;
		$this->tags				= $tags;
		$this->params			= $state->params;
		$this->pageclass_sfx	= htmlspecialchars($params->get('pageclass_sfx'));

		// Check for layout override only if this is not the active menu item
		// If it is the active menu item, then the view and category id will match
		$active		= $app->getMenu()->getActive();

		if ((!$active) || (strpos($active->link, 'view=projects') === false)) {
			if ($layout	= $params->get('projects_layout')) {
				$this->setLayout($layout);
			}
		} else if (isset($active->query['layout'])) {
			// We need to set the layout in case this is an alternative menu item (with an alternative layout)
			$this->setLayout($active->query['layout']);
		}

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document.
	 */
	protected function _prepareDocument()
	{
		$app		= Factory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title		= null;

		// Because the application sets the default page title,
		// we need to get it from the menu item itself
		$menu		= $menus->getActive();

		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', Text::_('COM_ADVPORTFOLIO_DEFAULT_PAGE_TITLE'));
		}

		$title = $this->params->get('page_title', '');

		if (empty($title)) {
			$title = $app->get('sitename');
		} else if ($app->get('sitename_pagetitles', 0) == 1) {
			$title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		} else if ($app->get('sitename_pagetitles', 0) == 2) {
			$title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		$this->document->setTitle($title);

		if ($this->params->get('menu-meta_description')) {
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->params->get('menu-meta_keywords')) {
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots')) {
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}
	}
}