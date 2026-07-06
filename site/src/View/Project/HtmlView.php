<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Site\View\Project;

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\View\HtmlView as BaseHtmlView;

/**
 * HTML Project View class for the Portfolio component.
 *
 * @package		Joomla.Site
 * @subpakage	Skyline.Portfolio
 */
class HtmlView extends BaseHtmlView
{
	protected $item;
	protected $nextItem;
	protected $prevItem;
	protected $params;
	protected $state;

	public function display($tpl = null)
	{
		$app			= Factory::getApplication();
		$user			= Factory::getApplication()->getIdentity();
		$this->item		= $this->get('Item');
		$this->nextItem	= $this->get('NextItem');
		$this->prevItem	= $this->get('PrevItem');
		$this->state	= $this->get('State');

		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			$app->enqueueMessage(implode("\n", $errors), 'warning');

			return false;
		}

		// Create a shortcut for $item.
		$item = &$this->item;

		// Merge project params. If this is single-project view, menu params override project params
		// Otherwise, project params override menu item params
		$this->params	= $this->state->get('params');
		$active			= $app->getMenu()->getActive();
		$temp			= clone $this->params;

		// Check to see which parameters should take priority
		if ($active) {
			$currentLink = $active->link;
			// If the current view is the active item and an project view for this project, then the menu item params take priority
			if (strpos($currentLink, 'view=project') && (strpos($currentLink, '&id=' . (string) $item->id))) {
				// $item->params are the project params, $temp are the menu item params
				// Merge so that the menu item params take priority
				$item->params->merge($temp);
				// Load layout from active query (in case it is an alternative menu item)
				if (isset($active->query['layout'])) {
					$this->setLayout($active->query['layout']);
				}
			} else {
				// Current view is not a single project, so the project params take priority here
				// Merge the menu item params with the project params so that the project params take priority
				$temp->merge($item->params);
				$item->params = $temp;

				// Check for alternative layouts (since we are not in a single-project menu item)
				// Single-project menu item layout takes priority over alt layout for an project
				if ($layout = $item->params->get('project_layout')) {
					$this->setLayout($layout);
				}
			}
		} else {
			// Merge so that project params take priority
			$temp->merge($item->params);
			$item->params = $temp;
			// Check for alternative layouts (since we are not in a single-project menu item)
			// Single-project menu item layout takes priority over alt layout for an project
			if ($layout = $item->params->get('project_layout')) {
				$this->setLayout($layout);
			}
		}

		// Check the view access to the project (the model has already computed the values).
		if ($item->params->get('access-view') != true && (($item->params->get('show_noauth') != true && $user->get('guest')))) {
			$app->enqueueMessage(Text::_('JERROR_ALERTNOAUTHOR'), 'warning');
			return;
		}

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->item->params->get('pageclass_sfx'));

		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document
	 */
	protected function _prepareDocument()
	{
		$app		= Factory::getApplication();
		$menus		= $app->getMenu();
		$pathway	= $app->getPathway();
		$title		= null;

		// Because the application sets a default page title,
		// we need to get it from the menu item itself
		$menu = $menus->getActive();
		if ($menu) {
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		} else {
			$this->params->def('page_heading', Text::_('COM_ADVPORTFOLIO_PROJECTS'));
		}

		$title	= $this->params->get('page_title', '');
		$id		= (int) @$menu->query['id'];

		// if the menu item does not concern this project
		if ($menu && ($menu->query['option'] != 'com_advportfolio' || $menu->query['view'] != 'project' || $id != $this->item->id)) {
			// If this is not a single project menu item, set the page title to the project title
			if ($this->item->title) {
				$title = $this->item->title;
			}

			$path = array(array('title' => $this->item->title, 'link' => ''));
		}

		// Check for empty title and add site name if param is set
		if (empty($title)) {
			$title = $app->get('sitename');
		} elseif ($app->get('sitename_pagetitles', 0) == 1) {
			$title = Text::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		} elseif ($app->get('sitename_pagetitles', 0) == 2) {
			$title = Text::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		if (empty($title)) {
			$title = $this->item->title;
		}

		$this->document->setTitle($title);

		if ($this->item->metadesc) {
			$this->document->setDescription($this->item->metadesc);
		} elseif (!$this->item->metadesc && $this->params->get('menu-meta_description')) {
			$this->document->setDescription($this->params->get('menu-meta_description'));
		}

		if ($this->item->metakey) {
			$this->document->setMetadata('keywords', $this->item->metakey);
		} elseif (!$this->item->metakey && $this->params->get('menu-meta_keywords')) {
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots')) {
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

		if ($app->get('MetaAuthor') == '1') {
			$this->document->setMetaData('author', $this->item->author);
		}

		$mdata = $this->item->metadata->toArray();
		foreach ($mdata as $k => $v) {
			if ($v) {
				$this->document->setMetadata($k, $v);
			}
		}
	}
}