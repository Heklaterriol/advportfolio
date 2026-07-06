<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Administrator\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Extension\BootableExtensionInterface;
use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Psr\Container\ContainerInterface;

class AdvportfolioComponent extends MVCComponent implements BootableExtensionInterface
{
	public function boot(ContainerInterface $container): void
	{
		if (!Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_advportfolio')) {
			throw new \Exception(Text::_('JERROR_ALERTNOAUTHOR'), 404);
		}

		HTMLHelper::_('script', 'com_advportfolio/admin.script.js', ['relative' => false, 'version' => true]);
		HTMLHelper::_('stylesheet', 'com_advportfolio/admin.style.css', ['relative' => false, 'version' => true]);

		HTMLHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
	}
}