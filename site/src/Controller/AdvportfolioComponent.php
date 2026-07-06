<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

namespace Joomla\Component\Advportfolio\Site\Extension;

defined('_JEXEC') or die;

use Joomla\CMS\Extension\MVCComponent;
use Joomla\CMS\Extension\RouterServiceInterface;
use Joomla\CMS\Extension\Service\Provider\RouterFactory;
use Joomla\CMS\HTML\HTMLHelper;

class AdvportfolioComponent extends MVCComponent implements RouterServiceInterface
{
	use \Joomla\CMS\Extension\Service\Provider\RouterFactory;

	public function boot(): void
	{
		HTMLHelper::addIncludePath(JPATH_ROOT . '/administrator/components/com_advportfolio/src/Helper/html');
	}
}