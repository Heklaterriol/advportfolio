<?php
/**
 * @package     AdvPortfolio
 * @subpackage  Admin
 * @copyright   Copyright (C) 2026 Hekla Terriol. All rights reserved.
 * @license     GNU General Public License version 2 or later
 */

// No direct access.
defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Controller\BaseController;
use Joomla\CMS\Helper\ContentHelper;

// Access check.
if (!Factory::getApplication()->getIdentity()->authorise('core.manage', 'com_advportfolio')) {
	throw new Exception(Text::_('JERROR_ALERTNOAUTHOR'), 404);
}

// Include CSS and JS using WebAssetManager
$wa = Factory::getApplication()->getDocument()->getWebAssetManager();
$wa->useScript('com_advportfolio.admin.script');
$wa->useStyle('com_advportfolio.admin.style');

// Include dependencies
ContentHelper::addIncludePath(JPATH_COMPONENT . '/helpers/html');
require_once JPATH_COMPONENT . '/helpers/factory.php';
require_once JPATH_COMPONENT . '/helpers/imagelib.php';

$controller = BaseController::getInstance('AdvPortfolio', ['default_view' => 'projects']);
$controller->execute(Factory::getApplication()->getInput()->getCmd('task'));
$controller->redirect();