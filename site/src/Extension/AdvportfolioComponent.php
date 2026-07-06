<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Site\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Application\CMSApplicationInterface;
use Joomla\CMS\Categories\CategoryServiceInterface;
use Joomla\CMS\Categories\CategoryServiceTrait;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Extension\RouterServiceInterface;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Menu\AbstractMenu;
use Joomla\CMS\Router\RouterInterface;
use Joomla\Component\Advportfolio\Site\Service\Router;

class AdvportfolioComponent extends MVCComponent implements RouterServiceInterface, CategoryServiceInterface
{
	use CategoryServiceTrait;

	public function boot(): void
	{
		HTMLHelper::addIncludePath(JPATH_ROOT . '/administrator/components/com_advportfolio/src/Helper/html');
	}

	public function createRouter(CMSApplicationInterface $application, AbstractMenu $menu): RouterInterface
	{
		return new Router($application, $menu);
	}
}