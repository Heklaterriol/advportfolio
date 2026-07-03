<?php
/**
 * @copyright	Copyright (c) 2013 Skyline Technology Ltd (http://extstore.com). All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */

// No direct access.
defined('_JEXEC') or die;

use JoomlaCMSFactory;
use JoomlaCMSLanguageText;
use JoomlaCMSMVCControllerBaseController;
use JoomlaCMSHelperContentHelper;

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
$controller->execute(Factory::getApplication()->getInput()->get('task'));
$controller->redirect();