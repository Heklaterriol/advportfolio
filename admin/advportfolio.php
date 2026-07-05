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
use Joomla\CMS\WebAsset\WebAssetManager;
use Joomla\CMS\Access\Exception\NotAllowed;
use Joomla\CMS\HTML\HTMLHelper;

// Get application
$app = Factory::getApplication();

// Access check.
if (!$app->getIdentity()->authorise('core.manage', 'com_advportfolio')) {
    throw new NotAllowed(Text::_('JERROR_ALERTNOAUTHOR'), 403);
}

// Get WebAssetManager
$wa = $app->getDocument()->getWebAssetManager();

// REGISTER assets (correct media paths for component)
$wa->registerScript(
    'com_advportfolio.admin.script',
    'com_advportfolio/js/admin.script.js',
    [],
    ['defer' => true]
);

$wa->registerStyle(
    'com_advportfolio.admin.style',
    'com_advportfolio/css/admin.style.css'
);

// USE assets
$wa->useScript('com_advportfolio.admin.script');
$wa->useStyle('com_advportfolio.admin.style');

/*
 * Legacy helper includes removed.
 * Joomla 4+ does not support ContentHelper::addIncludePath anymore.
 */
require_once JPATH_COMPONENT . '/helpers/factory.php';
require_once JPATH_COMPONENT . '/helpers/imagelib.php';

// Get controller
$controller = BaseController::getInstance('AdvPortfolio', [
    'default_view' => 'projects'
]);

// Execute task
$task = $app->getInput()->getCmd('task', '');
$controller->execute($task);

// Redirect
$controller->redirect();